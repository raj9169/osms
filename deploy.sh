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

echo "Starting PHP Application Deployment..."

# Create backup directory
sudo mkdir -p $BACKUP_DIR

# Create backup if directory exists and has content
if [ -d "$WEB_DIR" ] && [ "$(ls -A $WEB_DIR 2>/dev/null)" ]; then
    echo "Creating backup..."
    sudo tar -czf $BACKUP_DIR/backup-$(date +%Y%m%d-%H%M%S).tar.gz $WEB_DIR/ 2>/dev/null || true
fi

# Stop web server
echo "Stopping Apache..."
sudo systemctl stop httpd

# Deploy new files (more careful approach)
echo "Deploying files..."
sudo mkdir -p $WEB_DIR

# Copy files excluding deployment scripts
for item in *; do
    if [ "$item" != "Jenkinsfile" ] && [ "$item" != "deploy.sh" ]; then
        sudo cp -r "$item" $WEB_DIR/ 2>/dev/null || true
    fi
done

# Set permissions
echo "Setting permissions..."
sudo chown -R apache:apache $WEB_DIR
sudo find $WEB_DIR -type d -exec chmod 755 {} \;
sudo find $WEB_DIR -type f -exec chmod 644 {} \;

# Create and set permissions for writable directories
sudo mkdir -p $WEB_DIR/sessions $WEB_DIR/uploads
sudo chmod 775 $WEB_DIR/sessions $WEB_DIR/uploads

# Start web server
echo "Starting Apache..."
sudo systemctl start httpd

# Test deployment
echo "Testing deployment..."
sleep 3

if curl -s http://localhost/index.php > /dev/null; then
    echo "✅ Deployment successful!"
else
    echo "⚠️ Deployment completed but website test failed"
    # Show Apache status for debugging
    sudo systemctl status httpd --no-pager -l
fi

echo "🎉 Deployment process completed!"
