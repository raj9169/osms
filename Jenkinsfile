pipeline {
    agent any
    
    stages {
        stage('Checkout Code') {
            steps {
                checkout scm
                sh 'echo "📦 Repository checked out successfully"'
                sh 'ls -la'  // This will show all files including Jenkinsfile
            }
        }
        
        stage('Validate Structure') {
            steps {
                sh '''
                    echo "🔍 Validating project structure..."
                    echo "Current directory:"
                    pwd
                    echo "Files in repository:"
                    ls -la
                    
                    # Check if Jenkinsfile exists
                    if [ -f "Jenkinsfile" ]; then
                        echo "✅ Jenkinsfile found"
                        cat Jenkinsfile | head -5
                    else
                        echo "❌ Jenkinsfile NOT found in current directory"
                        echo "Creating Jenkinsfile now..."
                    fi
                    
                    # Check essential project files
                    if [ -f "index.php" ]; then
                        echo "✅ index.php - Main entry point"
                    else
                        echo "❌ index.php missing!"
                        exit 1
                    fi
                    
                    if [ -f "dbConnection.php" ]; then
                        echo "✅ dbConnection.php - Database config"
                    fi
                    
                    if [ -d "vendor" ]; then
                        echo "✅ vendor/ - Composer dependencies"
                    fi
                '''
            }
        }
        
        stage('Install Dependencies') {
            steps {
                sh '''
                    echo "📦 Installing Composer dependencies..."
                    if [ -f "composer.json" ]; then
                        composer install --no-dev --optimize-autoloader --no-interaction
                        echo "✅ Composer dependencies installed"
                    else
                        echo "⚠️ No composer.json found"
                    fi
                '''
            }
        }
        
        stage('Deploy to EC2') {
            steps {
                sh '''
                    echo "🚀 Deploying to EC2..."
                    if [ -f "deploy.sh" ]; then
                        chmod +x deploy.sh
                        ./deploy.sh
                    else
                        echo "❌ deploy.sh not found - creating basic deployment"
                        # Basic deployment as fallback
                        sudo systemctl stop httpd
                        sudo cp -r * /var/www/html/ 2>/dev/null || true
                        sudo chown -R apache:apache /var/www/html
                        sudo chmod -R 755 /var/www/html
                        sudo systemctl start httpd
                        echo "✅ Basic deployment completed"
                    fi
                '''
            }
        }
        
        stage('Verify Deployment') {
            steps {
                sh '''
                    echo "🔍 Verifying deployment..."
                    sleep 5
                    
                    # Test if application is accessible
                    if curl -s -f "http://localhost/index.php" > /dev/null; then
                        echo "🎉 Application deployed successfully!"
                    else
                        echo "⚠️ Application might have issues - checking Apache status"
                        sudo systemctl status httpd --no-pager -l
                        exit 1
                    fi
                '''
            }
        }
    }
    
    post {
        always {
            cleanWs()
            echo "🧹 Workspace cleaned"
        }
        success {
            echo "🚀 SUCCESS: Application deployed to EC2"
            echo "📍 Access at: http://your-ec2-ip"
        }
        failure {
            echo "❌ FAILED: Deployment unsuccessful"
            echo "Check Jenkins logs for details"
        }
    }
}
