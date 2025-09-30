<?php
/**
 * Configuration File
 * Update these values with your database credentials
 */

// MySQL Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'internship_db');
define('DB_PORT', 3306);

// MongoDB Configuration
define('MONGO_HOST', 'localhost');
define('MONGO_PORT', 27017);
define('MONGO_DB', 'internship_db');
define('MONGO_COLLECTION', 'user_profiles');

// Redis Configuration
define('REDIS_HOST', '127.0.0.1');
define('REDIS_PORT', 6379);
define('REDIS_TIMEOUT', 0);

// Session Configuration
define('SESSION_EXPIRY', 3600); // 1 hour in seconds

// CORS Configuration
$allowedOrigins = [
    'http://localhost:8000',
    'http://127.0.0.1:8000',
    'http://localhost',
    'http://127.0.0.1'
];

// Set CORS headers
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

if (in_array($origin, $allowedOrigins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
}

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Session-Token, Authorization');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// MySQL Connection Function
function getMySQLConnection() {
    try {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        
        if ($mysqli->connect_error) {
            throw new Exception('MySQL Connection failed: ' . $mysqli->connect_error);
        }
        
        $mysqli->set_charset('utf8mb4');
        return $mysqli;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return null;
    }
}

// MongoDB Connection Function
function getMongoDBConnection() {
    try {
        require_once __DIR__ . '/../vendor/autoload.php';
        
        $mongoClient = new MongoDB\Client("mongodb://" . MONGO_HOST . ":" . MONGO_PORT);
        $database = $mongoClient->selectDatabase(MONGO_DB);
        $collection = $database->selectCollection(MONGO_COLLECTION);
        
        return $collection;
    } catch (Exception $e) {
        error_log('MongoDB Connection failed: ' . $e->getMessage());
        return null;
    }
}

// Redis Connection Function
function getRedisConnection() {
    try {
        require_once __DIR__ . '/../vendor/autoload.php';
        
        $redis = new Predis\Client([
            'scheme' => 'tcp',
            'host'   => REDIS_HOST,
            'port'   => REDIS_PORT,
            'timeout' => REDIS_TIMEOUT
        ]);
        
        $redis->connect();
        return $redis;
    } catch (Exception $e) {
        error_log('Redis Connection failed: ' . $e->getMessage());
        return null;
    }
}

// Generate secure session token
function generateSessionToken() {
    return bin2hex(random_bytes(32));
}

// Validate session token
function validateSession($token) {
    $redis = getRedisConnection();
    
    if (!$redis) {
        return false;
    }
    
    $email = $redis->get('session:' . $token);
    
    if ($email) {
        // Extend session expiry
        $redis->expire('session:' . $token, SESSION_EXPIRY);
        return $email;
    }
    
    return false;
}
?>
