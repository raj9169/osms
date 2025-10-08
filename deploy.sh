#!/bin/bash

# Configuration
WEB_DIR="/var/www/html"
BACKUP_DIR="/var/www/backups"

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
