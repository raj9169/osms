#!/bin/bash

# ==========================================
# 🚀 OSMS Deployment Script for Ubuntu
# ==========================================


# Fix log file permissions
LOG_FILE="/var/log/osms-deployment.log"
sudo touch "$LOG_FILE"
sudo chown jenkins:jenkins "$LOG_FILE" 2>/dev/null || true
sudo chmod 666 "$LOG_FILE" 2>/dev/null || true

# If still no permission, use tmp location
if [ ! -w "$LOG_FILE" ]; then
    LOG_FILE="/tmp/osms-deployment.log"
fi

# Logging function
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}



set -e  # Exit on any error

# Configuration
WEB_DIR="/var/www/html"
BACKUP_DIR="/var/www/backups"
LOG_FILE="/var/log/osms-deployment.log"
DEPLOYMENT_DATE=$(date +%Y%m%d-%H%M%S)

# Logging function
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

# Function to get the public IP
get_ec2_ip() {
    local ip=""
    ip=$(curl -s --connect-timeout 2 http://169.254.169.254/latest/meta-data/public-ipv4)
    if [ -z "$ip" ]; then
        ip=$(curl -s --connect-timeout 2 http://checkip.amazonaws.com/)
    fi
    if [ -z "$ip" ]; then
        ip=$(curl -s --connect-timeout 2 http://icanhazip.com/)
    fi
    if [ -z "$ip" ]; then
        ip=$(hostname -I | awk '{print $1}')
    fi
    echo "$ip"
}

# Validation functions
validate_required_files() {
    log "🔍 Validating required files..."
    local required_files=("index.php" "dbConnection.php")
    local missing_files=()
    
    for file in "${required_files[@]}"; do
        if [ ! -f "$file" ]; then
            missing_files+=("$file")
        fi
    done
    
    if [ ${#missing_files[@]} -gt 0 ]; then
        log "❌ Missing required files: ${missing_files[*]}"
        return 1
    fi
    log "✅ All required files present"
}

check_service_status() {
    local service=$1
    if ! sudo systemctl is-active --quiet "$service"; then
        log "❌ $service is not running"
        sudo systemctl status "$service" | tee -a "$LOG_FILE"
        return 1
    fi
    log "✅ $service is running"
}

# Main deployment
main() {
    log "=========================================="
    log "🚀 Starting OSMS Application Deployment"
    log "=========================================="

    # Get server IP
    EC2_IP=$(get_ec2_ip)
    if [ -z "$EC2_IP" ] || [ "$EC2_IP" = "127.0.0.1" ]; then
        EC2_IP="localhost"
        log "⚠️ Using localhost for testing (public IP not detected)"
    else
        log "✅ Public IP: $EC2_IP"
    fi

    SITE_URL="http://$EC2_IP"
    log "🌐 Target URL: $SITE_URL"
    log "📁 Web Directory: $WEB_DIR"

    # Validate required files before starting
    validate_required_files

    # Ensure necessary packages are installed
    log "📦 Installing required packages..."
    sudo apt update -y
    sudo apt install -y apache2 php php-mysql php-curl php-gd php-mbstring php-xml composer curl unzip

    # MySQL setup
    log "🗄️ Setting up MySQL..."
    if ! dpkg -l | grep -q mysql-server; then
        sudo apt install -y mysql-server
    fi
    sudo systemctl start mysql
    sudo systemctl enable mysql
    
    # Wait for MySQL to be ready
    for i in {1..30}; do
        if mysql -u root -e "SELECT 1;" &>/dev/null; then
            log "✅ MySQL is ready"
            break
        fi
        if [ $i -eq 30 ]; then
            log "❌ MySQL failed to start within 30 seconds"
            exit 1
        fi
        sleep 1
    done

    # Create backup directory
    sudo mkdir -p "$BACKUP_DIR"

    # Create backup if directory exists
    if [ -d "$WEB_DIR" ] && [ "$(ls -A $WEB_DIR 2>/dev/null)" ]; then
        log "💾 Creating backup..."
        sudo tar -czf "$BACKUP_DIR/backup-$DEPLOYMENT_DATE.tar.gz" -C /var/www html/ 2>/dev/null && \
        log "✅ Backup created: $BACKUP_DIR/backup-$DEPLOYMENT_DATE.tar.gz" || \
        log "⚠️ Backup creation failed or no files to backup"
    fi

    # Stop Apache
    log "🛑 Stopping Apache..."
    sudo systemctl stop apache2

    # Clean old files
    log "🧹 Cleaning web directory..."
    sudo find "$WEB_DIR" -maxdepth 1 -name "*.php" -delete
    sudo find "$WEB_DIR" -maxdepth 1 -name "*.html" -delete
    sudo rm -rf "$WEB_DIR"/{Admin,User,images,js,css,vendor,PHPMailer,sessions} 2>/dev/null || true

    # Deploy new files with specific copy
    log "📦 Deploying files..."
    for item in *.php *.html *.css Admin User images js css vendor PHPMailer; do
        if [ -e "$item" ]; then
            sudo cp -r "$item" "$WEB_DIR/" 2>/dev/null && \
            log "✅ Copied: $item" || \
            log "⚠️ Failed to copy: $item"
        fi
    done

    # Remove deployment scripts from web directory
    sudo rm -f "$WEB_DIR"/{Jenkinsfile,deploy.sh,delpy.sh} 2>/dev/null || true

    # Set permissions
    log "🔐 Setting permissions..."
    sudo chown -R www-data:www-data "$WEB_DIR"
    sudo find "$WEB_DIR" -type d -exec chmod 755 {} \;
    sudo find "$WEB_DIR" -type f -exec chmod 644 {} \;

    # Ensure specific directories have correct access
    sudo mkdir -p "$WEB_DIR/sessions"
    sudo chown www-data:www-data "$WEB_DIR/sessions"
    sudo chmod 755 "$WEB_DIR/sessions"
    
    # Set executable permissions for specific PHP files if needed
    for dir in Admin User; do
        if [ -d "$WEB_DIR/$dir" ]; then
            sudo chmod 755 "$WEB_DIR/$dir"
        fi
    done

    # Composer dependencies
    log "📦 Installing Composer dependencies..."
    cd "$WEB_DIR"
    if [ -f "composer.json" ]; then
        sudo composer install --no-dev --optimize-autoloader --no-interaction && \
        log "✅ Composer dependencies installed" || \
        log "❌ Composer installation failed"
    else
        log "⚠️ No composer.json found"
    fi

    # Start Apache
    log "🚀 Starting Apache..."
    sudo systemctl start apache2
    sudo systemctl enable apache2

    # Verify services are running
    log "🔍 Verifying services..."
    check_service_status apache2
    check_service_status mysql

    # Test deployment
    log "🌐 Testing deployment..."
    sleep 5

    FILES_TO_TEST=(
        "index.php"
        "db_test.php"
        "contactform.php"
        "Admin/"
        "User/"
    )

    for item in "${FILES_TO_TEST[@]}"; do
        HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" "$SITE_URL/$item" || echo "000")
        if [[ "$HTTP_STATUS" =~ ^(200|301|302)$ ]]; then
            log "✅ $item is accessible (HTTP $HTTP_STATUS)"
        else
            log "❌ $item returned HTTP $HTTP_STATUS"
        fi
    done

    # Final cleanup
    log "🧹 Performing final cleanup..."
    cd /tmp
    log "✅ Cleanup completed"

    log "=========================================="
    log "🎉 Deployment completed successfully!"
    log "🌐 Application URL: $SITE_URL"
    log "📝 Log file: $LOG_FILE"
    log "💾 Backup: $BACKUP_DIR/backup-$DEPLOYMENT_DATE.tar.gz"
    log "=========================================="
}

# # Database setup function (optional - run separately)
# setup_database() {
#     log "🗄️ Setting up database schema..."
#     if [ -f "database/schema.sql" ]; then
#         mysql -u root -p < database/schema.sql && \
#         log "✅ Database schema imported" || \
#         log "❌ Database schema import failed"
#     else
#         log "⚠️ No database/schema.sql file found"
#     fi
# }

# # Run main function
main "$@"

# Uncomment the line below if you want to run database setup after deployment
# setup_database
