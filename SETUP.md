Quick Setup Guide
🚀 Fast Track Setup (5 minutes)
Prerequisites Check
bash# Check PHP version (need 7.4+)
php -v

# Check MySQL
mysql --version

# Check MongoDB
mongod --version

# Check Redis
redis-cli --version
Step 1: Clone & Install (2 minutes)
bash# Clone repository
git clone https://github.com/yourusername/user-auth-system.git
cd user-auth-system

# Install PHP dependencies
composer install

# Or manually install
composer require predis/predis
composer require mongodb/mongodb
Step 2: Database Setup (2 minutes)
MySQL:
bash# Login to MySQL
mysql -u root -p

# Run these commands
CREATE DATABASE internship_db;
USE internship_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
);

EXIT;
Redis & MongoDB:
bash# Start Redis
redis-server

# Start MongoDB
mongod
# OR on macOS with homebrew:
brew services start mongodb-community
Step 3: Configure (30 seconds)
bash# Copy config template
cp php/config.example.php php/config.php

# Edit with your credentials
nano php/config.php
# OR use any text editor
Update these lines in config.php:
phpdefine('DB_USER', 'root');        // Your MySQL username
define('DB_PASS', '');            // Your MySQL password
define('DB_NAME', 'internship_db');
Step 4: Run (30 seconds)
bash# Start PHP server
php -S localhost:8000
Open browser: http://localhost:8000

🎯 Testing Your Setup

Register: Create account with email & password
Login: Use same credentials
Profile: Add age, DOB, contact info
Update: Modify profile details
Logout: Clear session
Re-login: Verify data persists


❌ Common Issues & Fixes
"Connection refused" Error
MySQL:
bashsudo systemctl start mysql
# OR
brew services start mysql
Redis:
bashsudo systemctl start redis-server
# OR
brew services start redis
MongoDB:
bashsudo systemctl start mongod
# OR
brew services start mongodb-community
"Class not found" Error
bash# Install Composer dependencies
composer install

# OR install manually
composer require predis/predis
composer require mongodb/mongodb
CORS Issues
Update php/config.php:
phpdefine('ALLOWED_ORIGINS', [
    'http://localhost:8000',
    'http://127.0.0.1:8000',
    // Add your domain
]);
MongoDB Connection Error
bash# Check if MongoDB is running
pgrep mongod

# Start MongoDB
mongod --dbpath /usr/local/var/mongodb

🔧 Configuration Examples
Local Development
php// php/config.php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('MONGO_HOST', 'localhost');
define('REDIS_HOST', '127.0.0.1');
Production (Example)
php// php/config.php
define('DB_HOST', 'mysql.yourhost.com');
define('DB_USER', 'your_user');
define('DB_PASS', 'your_secure_password');
define('MONGO_HOST', 'mongodb.yourhost.com');
define('REDIS_HOST', 'redis.yourhost.com');

📋 Verification Checklist

 PHP 7.4+ installed
 MySQL running and accessible
 MongoDB running
 Redis running
 Composer dependencies installed
 Database created
 Users table created
 Config file updated
 Server started on port 8000
 Application opens in browser


🆘 Still Having Issues?

Check all services are running:

bash# MySQL
sudo systemctl status mysql

# MongoDB
sudo systemctl status mongod

# Redis
sudo systemctl status redis-server

Check PHP extensions:

bashphp -m | grep -E "pdo|mongodb|redis"

Check port 8000 is free:

bashlsof -i :8000
# If something is using it, kill it or use different port
php -S localhost:8080

Check error logs:


Browser Console (F12)
PHP Error Log
MySQL Error Log
MongoDB Log


🎓 Next Steps
Once setup is complete:

Read the full README.md
Review code structure
Test all features
Deploy to hosting (see DEPLOYMENT.md)
Submit your project!


Estimated Total Setup Time: 5-10 minutes
Need Help? Create an issue on GitHub or check the detailed README.md
