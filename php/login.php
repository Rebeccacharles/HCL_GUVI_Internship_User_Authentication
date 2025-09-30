<?php
require_once 'config.php';

// Get JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validate input
if (!isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Email and password are required'
    ]);
    exit();
}

$email = trim($data['email']);
$password = $data['password'];

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid email format'
    ]);
    exit();
}

// Get MySQL connection
$mysqli = getMySQLConnection();

if (!$mysqli) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed'
    ]);
    exit();
}

try {
    // Fetch user using prepared statement
    $stmt = $mysqli->prepare("SELECT id, email, password FROM users WHERE email = ?");
    
    if (!$stmt) {
        throw new Exception('Prepare statement failed: ' . $mysqli->error);
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $stmt->close();
        $mysqli->close();
        
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email or password'
        ]);
        exit();
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    $mysqli->close();
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email or password'
        ]);
        exit();
    }
    
    // Create session in Redis
    $redis = getRedisConnection();
    
    if (!$redis) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Session storage failed'
        ]);
        exit();
    }
    
    // Generate session token
    $sessionToken = generateSessionToken();
    
    // Store session in Redis with expiry
    $redis->setex('session:' . $sessionToken, SESSION_EXPIRY, $email);
    
    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'sessionToken' => $sessionToken
    ]);
    
} catch (Exception $e) {
    error_log($e->getMessage());
    
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($mysqli)) {
        $mysqli->close();
    }
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred during login'
    ]);
}
?>
