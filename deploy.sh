#!/bin/bash

set -e

LOG_FILE="/tmp/osms-deployment.log"

log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

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
