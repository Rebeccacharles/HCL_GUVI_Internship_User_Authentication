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

// Validate password length
if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Password must be at least 6 characters long'
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
    // Check if email already exists using prepared statement
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    
    if (!$stmt) {
        throw new Exception('Prepare statement failed: ' . $mysqli->error);
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $mysqli->close();
        
        http_response_code(409);
        echo json_encode([
            'success' => false,
            'message' => 'Email already registered'
        ]);
        exit();
    }
    
    $stmt->close();
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // Insert new user using prepared statement
    $stmt = $mysqli->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    
    if (!$stmt) {
        throw new Exception('Prepare statement failed: ' . $mysqli->error);
    }
    
    $stmt->bind_param("ss", $email, $hashedPassword);
    
    if ($stmt->execute()) {
        $userId = $mysqli->insert_id;
        
        // Create initial profile in MongoDB
        $mongoCollection = getMongoDBConnection();
        
        if ($mongoCollection) {
            try {
                $mongoCollection->insertOne([
                    'email' => $email,
                    'userId' => $userId,
                    'fullName' => '',
                    'age' => null,
                    'dob' => '',
                    'contact' => '',
                    'address' => '',
                    'createdAt' => new MongoDB\BSON\UTCDateTime(),
                    'updatedAt' => new MongoDB\BSON\UTCDateTime()
                ]);
            } catch (Exception $e) {
                error_log('MongoDB insert failed: ' . $e->getMessage());
            }
        }
        
        $stmt->close();
        $mysqli->close();
        
        echo json_encode([
            'success' => true,
            'message' => 'Registration successful'
        ]);
    } else {
        throw new Exception('Registration failed: ' . $stmt->error);
    }
    
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
        'message' => 'An error occurred during registration'
    ]);
}
?>
