Complete Project Structure
internship-project/
│
├── index.html                      # Registration page
├── login.html                      # Login page
├── profile.html                    # Profile page
│
├── css/
│   └── styles.css                  # Custom CSS styles
│
├── js/
│   ├── register.js                 # Registration logic (jQuery AJAX)
│   ├── login.js                    # Login logic (jQuery AJAX)
│   └── profile.js                  # Profile management logic (jQuery AJAX)
│
├── php/
│   ├── config.php                  # Database configurations
│   ├── register.php                # Registration endpoint
│   ├── login.php                   # Login endpoint
│   ├── profile.php                 # Get profile endpoint
│   └── update_profile.php          # Update profile endpoint
│
├── vendor/                         # Composer dependencies (auto-generated)
│
├── .gitignore                      # Git ignore file
├── composer.json                   # PHP dependencies
├── composer.lock                   # Composer lock file (auto-generated)
├── schema.sql                      # MySQL database schema
├── README.md                       # Project documentation
├── SETUP.md                        # Quick setup guide
├── DEPLOYMENT.md                   # Deployment guide
└── SUBMISSION_CHECKLIST.md         # Submission checklist
File Descriptions
HTML Files (Frontend)
index.html

User registration page
Bootstrap form design
Email and password input
Client-side validation
Links to login page

login.html

User login page
Bootstrap form design
Email and password input
Client-side validation
Links to registration page

profile.html

User profile management page
Displays user information
Allows profile updates (name, age, DOB, contact, address)
Logout functionality
Session-protected

CSS Files
css/styles.css

Custom styling for all pages
Gradient backgrounds
Card designs
Form styling
Responsive design enhancements
Button animations

JavaScript Files (jQuery AJAX)
js/register.js

Handles registration form submission
Client-side validation
AJAX call to register.php
Redirects to login on success
No form submission, only AJAX

js/login.js

Handles login form submission
Client-side validation
AJAX call to login.php
Stores session token in localStorage
Redirects to profile on success
No form submission, only AJAX

js/profile.js

Checks session token from localStorage
Loads user profile via AJAX
Updates profile via AJAX
Handles logout
Session management
No form submission, only AJAX

PHP Files (Backend)
php/config.php

Database connection configurations
MySQL, MongoDB, Redis settings
CORS headers
Helper functions:

getMySQLConnection() - Returns MySQL connection
getMongoDBConnection() - Returns MongoDB collection
getRedisConnection() - Returns Redis client
generateSessionToken() - Generates secure token
validateSession() - Validates session from Redis



php/register.php

Handles user registration
Validates input
Checks for duplicate email (prepared statement)
Hashes password using bcrypt
Inserts user to MySQL (prepared statement)
Creates profile in MongoDB
Returns JSON response

php/login.php

Handles user login
Validates credentials
Uses prepared statements
Verifies password
Creates session in Redis
Returns session token
Returns JSON response

php/profile.php

Fetches user profile from MongoDB
Validates session token from header
Returns profile data as JSON
Protected endpoint

php/update_profile.php

Updates user profile in MongoDB
Validates session token from header
Updates: fullName, age, dob, contact, address
Returns JSON response
Protected endpoint

Database Files
schema.sql

MySQL database creation script
Creates internship_db database
Creates users table with:

id (PRIMARY KEY)
email (UNIQUE)
password (hashed)
created_at, updated_at timestamps
Index on email



Configuration Files
composer.json

PHP dependencies:

predis/predis (Redis client)
mongodb/mongodb (MongoDB driver)


PHP extensions required

.gitignore

Excludes sensitive files
Excludes vendor directory
Excludes config.php with credentials

Documentation Files
README.md

Complete project documentation
Features list
Tech stack
Installation guide
API endpoints
Usage instructions

SETUP.md

Quick 5-minute setup guide
Step-by-step instructions
Troubleshooting tips

DEPLOYMENT.md

Multiple hosting options
Railway, Heroku, VPS guides
Configuration examples
Security checklist

SUBMISSION_CHECKLIST.md

Pre-submission requirements
Code quality checks
Testing checklist
What to submit

Technology Stack
Frontend

HTML5
CSS3
JavaScript (ES6)
jQuery 3.6.0
Bootstrap 5.3.0

Backend

PHP 7.4+
MySQL 5.7+
MongoDB 4.0+
Redis

Libraries

Predis (PHP Redis client)
MongoDB PHP Library

Key Features
✅ Separate HTML, CSS, JS, PHP files
✅ jQuery AJAX only (no form submission)
✅ Bootstrap responsive design
✅ MySQL with prepared statements
✅ MongoDB for profile storage
✅ Redis for session management
✅ LocalStorage for browser session
✅ No PHP sessions
✅ Password hashing (bcrypt)
✅ Input validation
✅ Error handling
✅ CORS configured
Data Flow
Registration Flow

User fills registration form (index.html)
JavaScript validates input (register.js)
AJAX POST to php/register.php
PHP validates and checks MySQL for existing email (prepared statement)
Password hashed with bcrypt
User inserted to MySQL (prepared statement)
Initial profile created in MongoDB
Success response, redirect to login

Login Flow

User fills login form (login.html)
JavaScript validates input (login.js)
AJAX POST to php/login.php
PHP validates credentials from MySQL (prepared statement)
Password verified
Session token generated and stored in Redis
Token returned to client
Token stored in localStorage
Redirect to profile page

Profile Management Flow

Profile page checks localStorage for token (profile.js)
AJAX GET to php/profile.php with token in header
PHP validates token with Redis
Profile data fetched from MongoDB
Data displayed in form
User updates fields
AJAX POST to php/update_profile.php with token
PHP validates token with Redis
MongoDB updated with new data
Success message displayed

Session Management

Token stored in browser localStorage
Token stored in Redis with expiry (1 hour)
Every request validates token with Redis
Token expiry extended on each request
Logout clears localStorage and redirects

Security Features

Password hashing (bcrypt)
Prepared statements (SQL injection prevention)
Session tokens (secure random)
Input validation (client and server)
CORS configuration
XSS prevention
Secure headers

Database Schema
MySQL - users table
sqlid: INT (PK, AUTO_INCREMENT)
email: VARCHAR(255) (UNIQUE, NOT NULL)
password: VARCHAR(255) (NOT NULL, HASHED)
created_at: TIMESTAMP
updated_at: TIMESTAMP
MongoDB - user_profiles collection
javascript{
  email: String,
  userId: Number,
  fullName: String,
  age: Number,
  dob: String,
  contact: String,
  address: String,
  createdAt: Date,
  updatedAt: Date
}
Redis - session storage
Key: session:{token}
Value: {email}
Expiry: 3600 seconds (1 hour)
