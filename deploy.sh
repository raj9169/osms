#!/bin/bash

# Function to get EC2 public IP
get_ec2_ip() {
    # Try multiple methods to get the public IP
    local ip=""
    
    # Method 1: EC2 instance metadata
    ip=$(curl -s --connect-timeout 2 http://169.254.169.254/latest/meta-data/public-ipv4)
    
    # Method 2: Check external service
    if [ -z "$ip" ]; then
        ip=$(curl -s --connect-timeout 2 http://checkip.amazonaws.com/)
    fi
    
    # Method 3: Get from hostname
    if [ -z "$ip" ]; then
        ip=$(curl -s --connect-timeout 2 http://icanhazip.com/)
    fi
    
    # Method 4: From network interface
    if [ -z "$ip" ]; then
        ip=$(ip addr show | grep -E 'inet.*global' | awk '{print $2}' | cut -d'/' -f1 | head -1)
    fi
    
    echo "$ip"
}

# Get EC2 IP
EC2_IP=$(get_ec2_ip)

if [ -z "$EC2_IP" ] || [ "$EC2_IP" = "127.0.0.1" ]; then
    EC2_IP="localhost"
    echo "⚠️  Using localhost for testing - actual deployment will use real IP"
else
    echo "✅ EC2 Public IP: $EC2_IP"
fi

# Configuration
WEB_DIR="/var/www/html"
BACKUP_DIR="/var/www/backups"
SITE_URL="http://$EC2_IP"

echo "=========================================="
echo "🚀 OSMS Application Deployment"
echo "🌐 Target: $SITE_URL"
echo "📁 Web Directory: $WEB_DIR"
echo "=========================================="

# Create backup directory
sudo mkdir -p $BACKUP_DIR

# Create backup if directory exists
if [ -d "$WEB_DIR" ] && [ "$(ls -A $WEB_DIR 2>/dev/null)" ]; then
    echo "Creating backup..."
    sudo tar -czf $BACKUP_DIR/backup-$(date +%Y%m%d-%H%M%S).tar.gz $WEB_DIR/ 2>/dev/null || true
fi

# Stop web server
echo "Stopping Apache..."
sudo systemctl stop httpd

# Clean the web directory (remove old files to avoid conflicts)
echo "Cleaning web directory..."
sudo find $WEB_DIR -maxdepth 1 -name "*.php" -delete
sudo find $WEB_DIR -maxdepth 1 -name "*.html" -delete
sudo rm -rf $WEB_DIR/Admin $WEB_DIR/User $WEB_DIR/images $WEB_DIR/js $WEB_DIR/vendor $WEB_DIR/PHPMailer 2>/dev/null || true

# Deploy new files
echo "Deploying files..."
sudo cp -r * $WEB_DIR/ 2>/dev/null || true

# Remove deployment scripts from web directory
sudo rm -f $WEB_DIR/Jenkinsfile $WEB_DIR/deploy.sh 2>/dev/null || true

# Set proper ownership and permissions
echo "Setting permissions..."

# Set ownership to apache
sudo chown -R apache:apache $WEB_DIR

# Set directory permissions
sudo find $WEB_DIR -type d -exec chmod 755 {} \;

# Set file permissions
sudo find $WEB_DIR -type f -exec chmod 644 {} \;

# Special permissions for specific directories
sudo chmod 755 $WEB_DIR/Admin $WEB_DIR/User $WEB_DIR/images $WEB_DIR/js

# Create sessions directory if it doesn't exist
sudo mkdir -p $WEB_DIR/sessions
sudo chown apache:apache $WEB_DIR/sessions
sudo chmod 755 $WEB_DIR/sessions

# Install Composer dependencies in web directory
echo "Installing Composer dependencies..."
cd $WEB_DIR
if [ -f "composer.json" ]; then
    sudo composer install --no-dev --optimize-autoloader --no-interaction
    echo "✅ Composer dependencies installed"
else
    echo "⚠️ No composer.json found"
fi

# Start web server
echo "Starting Apache..."
sudo systemctl start httpd

# Test the deployment
echo "Testing deployment..."
sleep 5

echo "Testing individual files and directories:"
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
    if [ "$HTTP_STATUS" = "200" ] || [ "$HTTP_STATUS" = "302" ] || [ "$HTTP_STATUS" = "301" ]; then
        echo "✅ $item is accessible (HTTP $HTTP_STATUS)"
    else
        echo "❌ $item returned HTTP $HTTP_STATUS"
    fi
done

echo "=========================================="
echo "🎉 Deployment completed!"
echo "🌐 Application URL: $SITE_URL"
echo "=========================================="