#!/bin/bash
set -e

echo "🚀 Starting OSMS Deployment on Ubuntu..."

# Install required packages
sudo apt update -y
sudo apt install -y apache2 php php-mysql

# Stop Apache
sudo systemctl stop apache2

# Copy files to web directory
sudo cp -r *.php Admin/ User/ images/ /var/www/html/ 2>/dev/null || true

# Set permissions
sudo chown -R www-data:www-data /var/www/html
sudo find /var/www/html -type d -exec chmod 755 {} \;
sudo find /var/www/html -type f -exec chmod 644 {} \;

# Start Apache
sudo systemctl start apache2

echo "✅ Basic deployment completed!"
echo "🌐 Visit: http://$(curl -s http://169.254.169.254/latest/meta-data/public-ipv4)"
