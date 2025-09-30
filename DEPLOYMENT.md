Deployment Guide
Complete guide to deploy your User Authentication System to various hosting platforms.
🌐 Hosting Options
Option 1: Free Hosting (InfinityFree / 000webhost)
InfinityFree Setup

Sign Up

Visit infinityfree.net
Create free account
Create new hosting account


Upload Files

Use File Manager or FTP
Upload all project files to htdocs folder
Maintain folder structure


Database Setup

Go to MySQL Databases in control panel
Create database: internship_db
Note database name, username, password
Import SQL schema using phpMyAdmin


Configuration

Update php/config.php with hosting credentials
Database host is usually sqlXXX.infinityfree.net


Limitations

⚠️ Most free hosts don't support Redis
⚠️ Most free hosts don't support MongoDB
Consider paid hosting for full functionality




Option 2: Railway.app (Recommended)
Railway supports all required technologies (PHP, MySQL, Redis, MongoDB).
Step 1: Prepare Repository
bash# Ensure your code is pushed to GitHub
git add .
git commit -m "Ready for deployment"
git push origin main
Step 2: Create railway.json
json{
  "build": {
    "builder": "NIXPACKS"
  },
  "deploy": {
    "startCommand": "php -S 0.0.0.0:$PORT",
    "restartPolicyType": "ON_FAILURE",
    "restartPolicyMaxRetries": 10
  }
}
Step 3: Deploy

Visit railway.app
Sign in with GitHub
Click "New Project" → "Deploy from GitHub repo"
Select your repository
Add services:

MySQL
Redis
MongoDB


Set environment variables in Railway dashboard
Get your deployment URL


Option 3: Heroku
Prerequisites
bash# Install Heroku CLI
brew install heroku/brew/heroku  # macOS
# or download from heroku.com
Step 1: Create Heroku Files
Procfile
web: php -S 0.0.0.0:$PORT
composer.json
json{
  "require": {
    "php": "^7.4 || ^8.0",
    "predis/predis": "^1.1",
    "mongodb/mongodb": "^1.12",
    "ext-mongodb": "*",
    "ext-redis": "*"
  }
}
Step 2: Deploy
bash# Login to Heroku
heroku login

# Create app
heroku create your-app-name

# Add databases
heroku addons:create cleardb:ignite  # MySQL
heroku addons:create heroku-redis:hobby-dev  # Redis
heroku addons:create mongolab:sandbox  # MongoDB

# Push to Heroku
git push heroku main

# Open app
heroku open
Step 3: Configure
bash# Set environment variables
heroku config:set DB_HOST=your-cleardb-host
heroku config:set DB_USER=your-cleardb-user
heroku config:set DB_PASS=your-cleardb-password

Option 4: Shared Hosting (cPanel)
Requirements

PHP 7.4+
MySQL access
SSH access (for Redis and MongoDB)

Steps

Upload via FTP

Use FileZilla or cPanel File Manager
Upload all files maintaining structure


Create MySQL Database

Go to cPanel → MySQL Databases
Create database and user
Import schema using phpMyAdmin


MongoDB Setup

Contact hosting provider for MongoDB support
Or use MongoDB Atlas (cloud)


Redis Setup

If available through hosting provider
Or use Redis Labs (cloud)


Update Configuration

Edit php/config.php with hosting credentials




Option 5: VPS (DigitalOcean, Linode, AWS EC2)
Initial Server Setup (Ubuntu 20.04)
bash# Update system
sudo apt update && sudo apt upgrade -y

# Install Apache
sudo apt install apache2 -y

# Install PHP and extensions
sudo apt install php php-mysql php-mongodb php-redis php-curl php-json -y

# Install MySQL
sudo apt install mysql-server -y

# Install Redis
sudo apt install redis-server -y

# Install MongoDB
wget -qO - https://www.mongodb.org/static/pgp/server-5.0.asc | sudo apt-key add -
echo "deb [ arch=amd64,arm64 ] https://repo.mongodb.org/apt/ubuntu focal/mongodb-org/5.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-5.0.list
sudo apt update
sudo apt install mongodb-org -y

# Start services
sudo systemctl start redis-server
sudo systemctl start mongod
sudo systemctl start mysql
sudo systemctl start apache2

# Enable services on boot
sudo systemctl enable redis-server
sudo systemctl enable mongod
sudo systemctl enable mysql
sudo systemctl enable apache2
Deploy Application
bash# Clone repository
cd /var/www/html
sudo git clone https://github.com/yourusername/your-repo.git
sudo chown -R www-data:www-data your-repo

# Configure MySQL
sudo mysql -u root
CREATE DATABASE internship_db;
CREATE USER 'internship_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON internship_db.* TO 'internship_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Import schema
mysql -u internship_user -p internship_db < schema.sql

# Update configuration
sudo nano /var/www/html/your-repo/php/config.php
Configure Apache
bashsudo nano /etc/apache2/sites-available/your-site.conf
Add:
apache<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/html/your-repo
    
    <Directory /var/www/html/your-repo>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
bash# Enable site
sudo a2ensite your-site.conf
sudo systemctl reload apache2

🔒 Security Checklist

 Change default database passwords
 Use environment variables for sensitive data
 Enable HTTPS/SSL certificate
 Configure CORS properly
 Set proper file permissions (644 for files, 755 for directories)
 Disable directory listing
 Keep software updated
 Use strong session tokens
 Implement rate limiting


📊 Post-Deployment Checklist

 Test user registration
 Test user login
 Test profile update
 Test session persistence
 Verify database connections
 Check Redis connectivity
 Verify MongoDB operations
 Test on mobile devices
 Check browser console for errors
 Monitor server logs


🐛 Troubleshooting
Redis Connection Issues
bash# Check if Redis is running
redis-cli ping

# Check Redis logs
tail -f /var/log/redis/redis-server.log
MongoDB Connection Issues
bash# Check MongoDB status
sudo systemctl status mongod

# Check MongoDB logs
tail -f /var/log/mongodb/mongod.log
MySQL Connection Issues
bash# Check MySQL status
sudo systemctl status mysql

# Test connection
mysql -u username -p -h host database_name
PHP Errors
bash# Check PHP error log
tail -f /var/log/apache2/error.log

# Enable error display (development only)
# In php.ini:
display_errors = On
error_reporting = E_ALL

📱 Testing Your Deployed Application

Open your hosted URL
Register a new user
Login with credentials
Update profile information
Logout and login again
Verify data persistence


📝 Submission Notes
When submitting your project:

GitHub Repository URL: https://github.com/yourusername/your-repo
Hosted Application URL: https://your-app.herokuapp.com or your hosting URL
Test Credentials: Provide a test account (optional)

Email: test@example.com
Password: Test@123




💡 Tips

Use MongoDB Atlas for free cloud MongoDB
Use Redis Labs for free cloud Redis
Use PlanetScale or ClearDB for free MySQL
Always test locally before deploying
Keep your GitHub repository updated
Document any deployment-specific changes


Need help? Check the main README.md or create an issue in the repository.
