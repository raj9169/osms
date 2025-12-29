#!/bin/bash

# User Data Script for EC2 Deployment
set -e

# Configuration
GITHUB_REPO="https://github.com/raj9169/osms.git"
LOG_FILE="/var/log/user-data-deploy.log"
DEPLOY_DIR="/tmp/git-deploy"

# Create log file early
exec > >(tee -a "$LOG_FILE") 2>&1

echo "=========================================="
echo "Starting EC2 User Data Deployment Script"
echo "Time: $(date)"
echo "Instance ID: $(curl -s http://169.254.169.254/latest/meta-data/instance-id)"
echo "=========================================="

log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1"
}

get_ec2_host() {
    # Get public IP
    local ip=$(curl -s --max-time 3 http://169.254.169.254/latest/meta-data/public-ipv4 2>/dev/null || echo "")
    
    if [ -z "$ip" ]; then
        # Fallback to private IP
        ip=$(curl -s --max-time 3 http://169.254.169.254/latest/meta-data/local-ipv4 2>/dev/null || echo "")
    fi
    
    if [ -z "$ip" ]; then
        ip="localhost"
    fi
    
    echo "$ip"
}

clone_github_repo() {
    local repo_url="$1"
    local target_dir="$2"
    
    if [ -z "$repo_url" ]; then
        log "ERROR: GitHub repository URL not set"
        echo "Please set GITHUB_REPO in the script" >&2
        return 1
    fi
    
    log "Cloning GitHub repository: $repo_url"
    
    # Install git if not present
    if ! command -v git &> /dev/null; then
        log "Installing git..."
        apt-get install -y git
    fi
    
    # Clean and clone
    rm -rf "$target_dir" 2>/dev/null || true
    mkdir -p "$target_dir"
    
    if git clone "$repo_url" "$target_dir"; then
        log "Repository cloned successfully to $target_dir"
        return 0
    else
        log "ERROR: Failed to clone repository"
        return 1
    fi
}

echo "🔄 Installing dependencies..."
sudo apt update -y
sudo apt install -y jq unzip curl

echo "⬇️ Downloading AWS CLI..."
curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"

echo "📦 Unzipping AWS CLI..."
unzip -o awscliv2.zip

echo "⚙️ Installing AWS CLI..."
sudo ./aws/install

sudo ln -sf /usr/local/bin/aws /usr/bin/aws
aws --version



# Main deployment
log "🚀 Beginning deployment process"

# Update and install packages
log "Updating package lists..."
apt-get update -y
log "Installing apt install mysql-client-core-8.0"
apt install mysql-client-core-8.0
log "Installing required packages..."
apt-get install -y \
    apache2 \
    php \
    php-mysql \
    git \
    curl \
    unzip

# Get host information
EC2_HOST=$(get_ec2_host)
log "Instance host/IP: $EC2_HOST"
# Clone repository
if clone_github_repo "$GITHUB_REPO" "$DEPLOY_DIR"; then
    SOURCE_DIR="$DEPLOY_DIR"
    log "Using GitHub repository as source"
else
    SOURCE_DIR="/tmp"
    log "Using /tmp as fallback source"
fi

# Stop Apache if running
log "Stopping Apache service..."
systemctl stop apache2 2>/dev/null || true

# Prepare web directory
log "Cleaning web directory..."
if [ -d "$SOURCE_DIR" ] && [ "$(ls -A "$SOURCE_DIR")" ]; then
    rm -rf /var/www/html/*
    cp -r "$SOURCE_DIR"/* /var/www/html/
else
    log "❌ Git repo empty – skipping delete to avoid blank server"
fi  
# Copy application files
log "Copying application files from $SOURCE_DIR..."
if [ -d "$SOURCE_DIR" ] && [ "$(ls -A $SOURCE_DIR 2>/dev/null)" ]; then
    cp -r "$SOURCE_DIR"/* /var/www/html/ 2>/dev/null || true
    
    # Remove .git directory if present
    rm -rf /var/www/html/.git 2>/dev/null || true
    
    log "Files copied successfully"
else
    log "WARNING: Source directory is empty"
fi


# Set permissions
log "Setting file permissions..."
chown -R www-data:www-data /var/www/html
find /var/www/html -type d -exec chmod 755 {} \;
find /var/www/html -type f -exec chmod 644 {} \;

# Create sessions directory
mkdir -p /var/www/html/sessions
chown www-data:www-data /var/www/html/sessions
chmod 755 /var/www/html/sessions


echo "===== Starting DB Secret Fetch Script ====="

log "🔐 Fetching DB credentials from Secrets Manager"

SECRET_NAME="prod/rds/app-dbase"
REGION="us-east-1"

SECRET=$(aws secretsmanager get-secret-value \
  --secret-id "$SECRET_NAME" \
  --region "$REGION" \
  --query SecretString \
  --output text)

DB_HOST=$(echo "$SECRET" | jq -r .host)
DB_USER=$(echo "$SECRET" | jq -r .username)
DB_PASS=$(echo "$SECRET" | jq -r .password)
DB_NAME=$(echo "$SECRET" | jq -r .dbname)

log "🧪 DB_HOST=$DB_HOST"
log "🧪 DB_NAME=$DB_NAME"

log "📁 Injecting DB credentials into Apache envvars"

sudo sed -i '/DB_HOST=/d;/DB_USER=/d;/DB_PASS=/d;/DB_NAME=/d' /etc/apache2/envvars

sudo tee -a /etc/apache2/envvars > /dev/null <<EOF
export DB_HOST=$DB_HOST
export DB_USER=$DB_USER
export DB_PASS=$DB_PASS
export DB_NAME=$DB_NAME
EOF

sudo systemctl restart apache2

echo "🎯 DB credentials loaded successfully"
echo "===== Script Finished at $(date) ====="


# Start Apache
log "Starting Apache service..."
systemctl start apache2

# Enable Apache to start on boot
systemctl enable apache2

# Test deployment
sleep 3

if systemctl is-active --quiet apache2; then
    log "✅ DEPLOYMENT SUCCESSFUL"
    log "🌐 Application URL: http://$EC2_HOST/"
    log "🔗 Test page: http://$EC2_HOST/test-connection.php"
    
    # Create a simple test page if not exists
    if [ ! -f /var/www/html/test-connection.php ]; then
        cat > /var/www/html/test-connection.php << 'EOF'
<?php
require_once 'dbConnection.php';
echo "<h2>Deployment Successful!</h2>";
echo "<p>Server: " . $_SERVER['SERVER_NAME'] . "</p>";
echo "<p>Time: " . date('Y-m-d H:i:s') . "</p>";
if ($con) {
    echo "<p style='color:green;'>✅ Database Connected</p>";
}
?>
EOF
    fi
else
    log "❌ Apache failed to start"
    systemctl status apache2 --no-pager
    exit 1
fi

log "=========================================="
log "Deployment completed at $(date)"
log "Check logs: tail -f $LOG_FILE"
log "=========================================="

# Optional: Clean up
rm -rf "$DEPLOY_DIR" 2>/dev/null || true

echo "User Data script execution completed"
