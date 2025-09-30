🚀 Quick Start Guide
Get your project running in 5 minutes!
Step 1: Download/Clone Project
bash# If using git
git clone <your-repo-url>
cd internship-project

# Or download and extract ZIP
Step 2: Install Dependencies
bash# Install PHP dependencies with Composer
composer install

# If you don't have Composer, install from https://getcomposer.org
# Or manually install:
composer require predis/predis
composer require mongodb/mongodb
Step 3: Start Required Services
On Linux/Mac:
bash# Start MySQL
sudo systemctl start mysql
# or
sudo service mysql start

# Start MongoDB
sudo systemctl start mongod
# or
mongod

# Start Redis
redis-server
On Windows:
bash# Start services from Services panel
# Or if using XAMPP, start MySQL from XAMPP Control Panel

# Start MongoDB
"C:\Program Files\MongoDB\Server\5.0\bin\mongod.exe"

# Start Redis
redis-server.exe
Step 4: Create Database
bash# Login to MySQL
mysql -u root -p

# Run these commands:
sqlCREATE DATABASE internship_db;
USE internship_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
);

EXIT;
Step 5: Configure Settings
bash# Copy config template
cp php/config.example.php php/config.php

# Edit config.php with your credentials
Update these in php/config.php:
phpdefine('DB_USER', 'root');        // Your MySQL username
define('DB_PASS', 'your_password'); // Your MySQL password
Step 6: Start Application
bash# Start PHP built-in server
php -S localhost:8000
Open browser: http://localhost:8000

🎉 That's It!
You should now see the registration page. Try these steps:

Register: Create a new account
Login: Use your credentials
Profile: Add your details
Update: Modify your profile
Logout: Test session management


🐛 Quick Troubleshooting
"Connection refused" errors?
bash# Check if services are running:
mysql -u root -p  # Test MySQL
redis-cli ping     # Should return PONG
mongosh            # Test MongoDB
"Class not found" errors?
bashcomposer install
"Permission denied" errors?
bashchmod -R 755 .
chmod -R 777 vendor/  # If needed
Port 8000 already in use?
bash# Use different port
php -S localhost:8080
# Then open http://localhost:8080

📱 Test the Application
Test Registration

Email: test@example.com
Password: test123 (minimum 6 characters)

Test Profile Update

Full Name: John Doe
Age: 25
DOB: 1999-01-15
Contact: +1234567890
Address: 123 Main St, City


🚀 Next Steps

✅ Application working locally
📖 Read full README.md
🌐 Deploy to hosting (see DEPLOYMENT.md)
📝 Submit your project!


💡 Pro Tips

Chrome DevTools: Press F12 to see AJAX requests in Network tab
Check Logs: Monitor PHP errors in browser console
Redis Monitor: Run redis-cli monitor to see session operations
MongoDB: Use MongoDB Compass to view data visually


📚 File Locations
Your Files:
├── index.html          → Registration page
├── login.html          → Login page
├── profile.html        → Profile page
├── css/styles.css      → Your styles
├── js
