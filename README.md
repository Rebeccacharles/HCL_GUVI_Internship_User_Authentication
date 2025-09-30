User Authentication & Profile Management System

A full-stack web application implementing user registration, login, and profile management with secure session handling.

📋 Features

User Registration with validation

Secure Login System

Profile Management (view & update)

Session Management using Redis

Responsive Bootstrap UI

AJAX-based communication (no form submissions)

Dual database architecture (MySQL + MongoDB)

🛠️ Tech Stack

Frontend: HTML5, CSS3, JavaScript, jQuery, Bootstrap

Backend: PHP 7.4+

Databases:

MySQL (User credentials)

MongoDB (User profiles)


Session Storage: Redis

Browser Storage: LocalStorage

📁 Project Structure

project-root/
│

├── index.html                 # Landing/Registration page

├── login.html                 # Login page

├── profile.html               # Profile page

├── css/

│   └── styles.css            # Custom styles

├── js/

│   ├── register.js           # Registration logic

│   ├── login.js              # Login logic

│   └── profile.js            # Profile management logic
│

├── php/

│   ├── config.php            # Database configurations

│   ├── register.php          # Registration endpoint

│   ├── login.php             # Login endpoint

│   ├── profile.php           # Get profile endpoint

│   └── update_profile.php    # Update profile endpoint

│

├── .gitignore

└── README.md

🔧 Installation & Setup

Prerequisites

PHP 7.4 or higher

MySQL 5.7 or higher

MongoDB 4.0 or higher

Redis Server

Apache/Nginx web server

Composer (for PHP dependencies)

Step 1: Clone Repository

bashgit clone https://github.com/Rebeccacharles/HCL_GUVI_Internship_User_Authentication/new/main

cd user-auth-system

Step 2: Install PHP Dependencies

bashcomposer require predis/predis

composer require mongodb/mongodb

Step 3: Database Setup

MySQL Database

sqlCREATE DATABASE internship_db;

USE internship_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
);
MongoDB Database

MongoDB collections are created automatically. The system uses:

Database: internship_db

Collection: user_profiles

Step 4: Redis Setup

Ensure Redis server is running:

bashredis-server

Test Redis connection:

bashredis-cli ping

# Should return: PONG

Step 5: Configure Database Connections

Update php/config.php with your database credentials:

php// MySQL Configuration

define('DB_HOST', 'localhost');

define('DB_USER', 'your_mysql_user');

define('DB_PASS', 'your_mysql_password');

define('DB_NAME', 'internship_db');

// MongoDB Configuration

define('MONGO_HOST', 'localhost');

define('MONGO_PORT', 27017);

// Redis Configuration

define('REDIS_HOST', '127.0.0.1');

define('REDIS_PORT', 6379);

Step 6: Start Application

🔐 Security Features

Password hashing using PHP's password_hash() (bcrypt)

Prepared statements to prevent SQL injection

Session tokens stored in Redis

Input validation and sanitization

CORS headers configured

📊 Application Flow

Registration: User registers with email and password

Login: User logs in with credentials

Session: Session token stored in localStorage and Redis

Profile: User can view and update profile information

🎯 Key Implementation Points

✅ Separate HTML, CSS, JS, and PHP files

✅ jQuery AJAX for all backend communication

✅ Bootstrap for responsive design

✅ MySQL with Prepared Statements only

✅ MongoDB for profile storage

✅ Redis for session management

✅ LocalStorage for browser-side session

✅ No PHP sessions used

📝 API Endpoints

Registration

URL: /php/register.php

Method: POST

Data: { email, password }

Login

URL: /php/login.php

Method: POST

Data: { email, password }

Get Profile

URL: /php/profile.php

Method: GET

Headers: X-Session-Token

Update Profile

URL: /php/update_profile.php

Method: POST

Headers: X-Session-Token

Data: { fullName, age, dob, contact, address }

🧪 Testing

Register a new user

Login with registered credentials

View profile page

Update profile information

Logout and login again to verify persistence

📦 Dependencies

json{
  "php": {
    "predis/predis": "^1.1",
    "mongodb/mongodb": "^1.12"
  },
  "frontend": {
    "bootstrap": "5.3.0",
    "jquery": "3.6.0"
  }
}
🚀 Deployment

For Hosting (Heroku, AWS, etc.)

Ensure all environment variables are set

Update database connection strings

Configure Redis connection

Set proper file permissions

Update CORS settings for production domain

📄 License

This project is created as part of an internship application.

👤 Author

Rebecca Charles

GitHub: Rebeccacharles

Email: rebeccacharles2306@gmail.com

🙏 Acknowledgments

Bootstrap for UI components

jQuery for AJAX functionality

PHP community for excellent documentation


