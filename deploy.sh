#!/bin/bash

# ==========================================
# 🚀 OSMS Deployment Script for Ubuntu
# ==========================================

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

# Get server IP
EC2_IP=$(get_ec2_ip)

if [ -z "$EC2_IP" ] || [ "$EC2_IP" = "127.0.0.1" ]; then
    EC2_IP="localhost"
    echo "⚠️ Using localhost for testing (public IP not detected)"
else
    echo "✅ Public IP: $EC2_IP"
fi

# Configuration
WEB_DIR="/var/www/html"
BACKUP_DIR="/var/www/backups"
SITE_URL="http://$EC2_IP"

echo "=========================================="
echo "🚀 OSMS Application Deployment (Ubuntu)"
echo "🌐 Target: $SITE_URL"
echo "📁 Web Directory: $WEB_DIR"
echo "=========================================="

# Ensure necessary packages are installed
echo "📦 Checking Apache and Composer..."
sudo apt update -y
sudo apt install -y apache2 php composer curl unzip

# Create backup directory
sudo mkdir -p $BACKUP_DIR

# Create backup if directory exists
if [ -d "$WEB_DIR" ] && [ "$(ls -A $WEB_DIR 2>/dev/null)" ]; then
    echo "Creating backup..."
    sudo tar -czf "$BACKUP_DIR/backup-$(date +%Y%m%d-%H%M%S).tar.gz" $WEB_DIR/ 2>/dev/null || true
fi

# Stop Apache
echo "🛑 Stopping Apache..."
sudo systemctl stop apache2

# Clean old files
echo "🧹 Cleaning web directory..."
sudo find $WEB_DIR -maxdepth 1 -name "*.php" -delete
sudo find $WEB_DIR -maxdepth 1 -name "*.html" -delete
sudo rm -rf $WEB_DIR/Admin $WEB_DIR/User $WEB_DIR/images $WEB_DIR/js $WEB_DIR/vendor $WEB_DIR/PHPMailer 2>/dev/null || true

# Deploy new files
echo "📦 Deploying files..."
sudo cp -r * $WEB_DIR/ 2>/dev/null || true

# Remove Jenkins and deploy script from web directory
sudo rm -f $WEB_DIR/Jenkinsfile $WEB_DIR/deploy.sh 2>/dev/null || true

# Set permissions
echo "🔐 Setting permissions..."
sudo chown -R www-data:www-data $WEB_DIR
sudo find $WEB_DIR -type d -exec chmod 755 {} \;
sudo find $WEB_DIR -type f -exec chmod 644 {} \;

# Ensure specific directories have correct access
sudo chmod 755 $WEB_DIR/Admin $WEB_DIR/User $WEB_DIR/images $WEB_DIR/js 2>/dev/null || true
sudo mkdir -p $WEB_DIR/sessions
sudo chown www-data:www-data $WEB_DIR/sessions
sudo chmod 755 $WEB_DIR/sessions

# Composer dependencies
echo "📦 Installing Composer dependencies..."
cd $WEB_DIR
if [ -f "composer.json" ]; then
    sudo composer install --no-dev --optimize-autoloader --no-interaction
    echo "✅ Composer dependencies installed"
else
    echo "⚠️ No composer.json found"
fi

# Start Apache
echo "🚀 Starting Apache..."
sudo systemctl start apache2
sudo systemctl enable apache2

# Test deployment
echo "🔍 Verifying deployment..."
sleep 5

FILES_TO_TEST=(
    "index.php"
    "chat.php"
    "contactform.php"
    "logout.php"
    "Admin/"
    "User/"
)

for item in "${FILES_TO_TEST[@]}"; do
    HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" "$SITE_URL/$item")
    if [[ "$HTTP_STATUS" =~ ^(200|301|302)$ ]]; then
        echo "✅ $item is accessible (HTTP $HTTP_STATUS)"
    else
        echo "❌ $item returned HTTP $HTTP_STATUS"
    fi
done

echo "=========================================="
echo "🎉 Deployment completed successfully!"
echo "🌐 Application URL: $SITE_URL"
echo "=========================================="
