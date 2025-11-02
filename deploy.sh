#!/bin/bash

set -e

LOG_FILE="/tmp/osms-deployment.log"

log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

get_ec2_ip() {
    # Try multiple methods to get EC2 IP
    local ip=""#!/bin/bash

set -e

LOG_FILE="/tmp/osms-deployment.log"

log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

get_ec2_ip() {
    local ip=""
    ip=$(curl -s --connect-timeout 2 http://169.254.169.254/latest/meta-data/public-ipv4 2>/dev/null || echo "")
    if [ -z "$ip" ]; then
        ip=$(curl -s --connect-timeout 2 http://checkip.amazonaws.com/ 2>/dev/null || echo "")
    fi
    if [ -z "$ip" ]; then
        ip=$(curl -s --connect-timeout 2 http://icanhazip.com/ 2>/dev/null || echo "")
    fi
    if [ -z "$ip" ]; then
        ip=$(hostname -I | awk '{print $1}' 2>/dev/null || echo "localhost")
    fi
    echo "$ip"
}

run_privileged() {
    if [ "$EUID" -eq 0 ]; then
        "$@"
    elif command -v sudo >/dev/null 2>&1 && sudo -n true 2>/dev/null; then
        sudo "$@"
    else
        log "❌ Cannot execute: $* (no privileges)"
        return 1
    fi
}

# Check and install packages only if missing
install_packages() {
    log "📦 Checking required packages..."
    
    # List of required packages
    local packages=("apache2" "php" "php-mysql" "php-curl" "php-gd" "php-mbstring" "php-xml" "curl" "unzip")
    local missing_packages=()
    
    # Check which packages are missing
    for pkg in "${packages[@]}"; do
        if ! dpkg -l | grep -q "^ii  $pkg "; then
            missing_packages+=("$pkg")
        fi
    done
    
    # Install only missing packages
    if [ ${#missing_packages[@]} -gt 0 ]; then
        log "📥 Installing missing packages: ${missing_packages[*]}"
        
        # Wait for apt locks
        while sudo fuser /var/lib/dpkg/lock >/dev/null 2>&1 || \
              sudo fuser /var/lib/apt/lists/lock >/dev/null 2>&1 || \
              sudo fuser /var/cache/apt/archives/lock >/dev/null 2>&1; do
            log "⏳ Waiting for apt lock to be released..."
            sleep 5
        done
        
        run_privileged apt update -y
        run_privileged apt install -y "${missing_packages[@]}"
        log "✅ Packages installed successfully"
    else
        log "✅ All required packages are already installed"
    fi
}

# Enable Apache modules
enable_apache_modules() {
    log "🔧 Enabling Apache modules..."
    run_privileged a2enmod rewrite
    run_privileged a2enmod php8.3  # Adjust based on your PHP version
    log "✅ Apache modules enabled"
}

# Check MySQL (optional - don't fail if MySQL has issues)
check_mysql() {
    log "🗄️ Checking MySQL status..."
    
    if run_privileged systemctl is-active --quiet mysql; then
        log "✅ MySQL service is running"
        return 0
    else
        log "⚠️ MySQL is not running (this might be expected)"
        return 0  # Don't fail deployment if MySQL has issues
    fi
}

main() {
    log "=========================================="
    log "🚀 Starting OSMS Application Deployment"
    log "=========================================="

    EC2_IP=$(get_ec2_ip)
    WEB_DIR="/var/www/html"
    SITE_URL="http://$EC2_IP"
    
    log "🌐 Target URL: $SITE_URL"
    log "📁 Web Directory: $WEB_DIR"

    # Install required packages
    install_packages
    
    # Enable Apache modules
    enable_apache_modules

    # Check MySQL (optional)
    check_mysql

    # Stop Apache
    log "🛑 Stopping Apache..."
    run_privileged systemctl stop apache2

    # Create backup
    BACKUP_DIR="/var/www/backups"
    run_privileged mkdir -p "$BACKUP_DIR"
    if [ -d "$WEB_DIR" ] && [ "$(ls -A "$WEB_DIR" 2>/dev/null)" ]; then
        log "📦 Creating backup..."
        run_privileged tar -czf "$BACKUP_DIR/backup-$(date +%Y%m%d-%H%M%S).tar.gz" -C /var/www html/ 2>/dev/null || true
    fi

    # Clean web directory (preserve hidden files and specific directories)
    log "🧹 Cleaning web directory..."
    run_privileged find "$WEB_DIR" -maxdepth 1 -type f \( -name "*.php" -o -name "*.html" -o -name "*.css" \) -delete 2>/dev/null || true
    run_privileged rm -rf "$WEB_DIR"/Admin "$WEB_DIR"/User "$WEB_DIR"/images "$WEB_DIR"/js "$WEB_DIR"/vendor "$WEB_DIR"/PHPMailer 2>/dev/null || true

    # Deploy files
    log "📦 Deploying files..."
    
    # Copy all visible files and directories
    for item in *; do
        if [ -e "$item" ] && [ "$item" != "Jenkinsfile" ] && [ "$item" != "deploy.sh" ]; then
            run_privileged cp -r "$item" "$WEB_DIR/" 2>/dev/null && \
            log "✅ Copied: $item" || \
            log "⚠️ Failed to copy: $item"
        fi
    done

    # Set permissions
    log "🔐 Setting permissions..."
    run_privileged chown -R www-data:www-data "$WEB_DIR"
    run_privileged find "$WEB_DIR" -type d -exec chmod 755 {} \;
    run_privileged find "$WEB_DIR" -type f -exec chmod 644 {} \;

    # Special permissions for directories
    run_privileged chmod 755 "$WEB_DIR/Admin" "$WEB_DIR/User" 2>/dev/null || true

    # Ensure sessions directory exists
    run_privileged mkdir -p "$WEB_DIR/sessions"
    run_privileged chown www-data:www-data "$WEB_DIR/sessions"
    run_privileged chmod 755 "$WEB_DIR/sessions"

    # Start Apache
    log "🚀 Starting Apache..."
    run_privileged systemctl start apache2

    # Verify Apache is running
    if run_privileged systemctl is-active --quiet apache2; then
        log "✅ Apache is running"
    else
        log "❌ Apache failed to start"
        run_privileged systemctl status apache2 --no-pager -l
        return 1
    fi

    # Test deployment
    log "🔍 Testing deployment..."
    sleep 5
    
    # Test local access
    if curl -s http://localhost/ > /dev/null; then
        log "✅ Local access test passed"
    else
        log "⚠️ Local access test failed"
    fi
    
    # Test PHP files
    if curl -s http://localhost/index.php > /dev/null; then
        log "✅ PHP files are accessible"
    else
        log "⚠️ PHP files might have issues"
    fi

    log "🎉 Deployment completed successfully!"
    log "🌐 Application URL: $SITE_URL"
    log "👤 Admin Panel: $SITE_URL/Admin"
    log "💬 Chat: $SITE_URL/chat.php"
}

main "$@"
    
    # Method 1: EC2 metadata with token (for newer instances)
    if ip=$(curl -s -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/public-ipv4 2>/dev/null); then
        if [[ ! "$ip" =~ "<?xml" ]]; then
            echo "$ip"
            return
        fi
    fi
    
    # Method 2: Direct metadata access
    if ip=$(curl -s http://169.254.169.254/latest/meta-data/public-ipv4 2>/dev/null); then
        if [[ ! "$ip" =~ "<?xml" ]]; then
            echo "$ip"
            return
        fi
    fi
    
    # Method 3: External service
    ip=$(curl -s http://checkip.amazonaws.com/ 2>/dev/null || echo "localhost")
    echo "$ip"
}

run_privileged() {
    if [ "$EUID" -eq 0 ]; then
        "$@"
    elif command -v sudo >/dev/null 2>&1 && sudo -n true 2>/dev/null; then
        sudo "$@"
    else
        log "❌ Cannot execute: $* (no privileges)"
        return 1
    fi
}

# Simplified MySQL check
check_mysql() {
    log "🗄️ Checking MySQL status..."
    
    # Check if MySQL service is running
    if run_privileged systemctl is-active --quiet mysql; then
        log "✅ MySQL service is running"
        
        # Try to connect without password (might work for root)
        if mysql -u root -e "SELECT 1;" &>/dev/null; then
            log "✅ MySQL connection successful"
            return 0
        else
            log "⚠️ MySQL is running but connection failed - this might be expected"
            log "💡 MySQL might require password authentication"
            return 0
        fi
    else
        log "🔄 Starting MySQL..."
        run_privileged systemctl start mysql
        sleep 5
        
        if run_privileged systemctl is-active --quiet mysql; then
            log "✅ MySQL started successfully"
            return 0
        else
            log "❌ MySQL failed to start"
            return 1
        fi
    fi
}

main() {
    log "=========================================="
    log "🚀 Starting OSMS Application Deployment"
    log "=========================================="

    EC2_IP=$(get_ec2_ip)
    WEB_DIR="/var/www/html"
    SITE_URL="http://$EC2_IP"
    
    log "🌐 Target URL: $SITE_URL"
    log "📁 Web Directory: $WEB_DIR"

    # Install required packages
    log "📦 Installing required packages..."
    while sudo fuser /var/lib/dpkg/lock >/dev/null 2>&1 || \
      sudo fuser /var/lib/apt/lists/lock >/dev/null 2>&1 || \
      sudo fuser /var/cache/apt/archives/lock >/dev/null 2>&1; do
    log "⏳ Waiting for apt lock to be released..."
    sleep 5
    done
    run_privileged apt update -y
    run_privileged apt install -y apache2 php php-mysql php-curl php-gd php-mbstring php-xml curl unzip

    # Check MySQL (but don't fail deployment if it has issues)
    log "🗄️ Setting up database..."
    if ! check_mysql; then
        log "⚠️ MySQL has issues, but continuing with web deployment"
    fi

    # Stop Apache
    log "🛑 Stopping Apache..."
    run_privileged systemctl stop apache2

    # Clean web directory
    log "🧹 Cleaning web directory..."
    run_privileged rm -rf "$WEB_DIR"/* 2>/dev/null || true

    # Deploy files
    log "📦 Deploying files..."
    for item in *.php *.html *.css Admin User images js css vendor PHPMailer; do
        if [ -e "$item" ]; then
            run_privileged cp -r "$item" "$WEB_DIR/" 2>/dev/null && \
            log "✅ Copied: $item" || \
            log "⚠️ Failed to copy: $item"
        fi
    done

    # Set permissions
    log "🔐 Setting permissions..."
    run_privileged chown -R www-data:www-data "$WEB_DIR"
    run_privileged find "$WEB_DIR" -type d -exec chmod 755 {} \;
    run_privileged find "$WEB_DIR" -type f -exec chmod 644 {} \;

    # Ensure sessions directory exists
    run_privileged mkdir -p "$WEB_DIR/sessions"
    run_privileged chown www-data:www-data "$WEB_DIR/sessions"
    run_privileged chmod 755 "$WEB_DIR/sessions"

    # Start Apache
    log "🚀 Starting Apache..."
    run_privileged systemctl start apache2

    # Verify Apache is running
    if run_privileged systemctl is-active --quiet apache2; then
        log "✅ Apache is running"
    else
        log "❌ Apache failed to start"
        return 1
    fi

    log "✅ Deployment completed!"
    log "🌐 Application URL: $SITE_URL"
}

main "$@"
