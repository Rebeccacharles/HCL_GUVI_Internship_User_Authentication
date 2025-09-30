<?php
require_once 'config.php';

// Get session token from header
$headers = getallheaders();
$sessionToken = isset($headers['X-Session-Token']) ? $headers['X-Session-Token'] : '';

if (empty($sessionToken)) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Session token is required'
    ]);
    exit();
}

// Validate session
$email = validateSession($sessionToken);

if (!$email) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid or expired session'
    ]);
    exit();
}

// Get JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validate input
if (!$data) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request data'
    ]);
    exit();
}

try {
    // Get MongoDB connection
    $mongoCollection = getMongoDBConnection();
    
    if (!$mongoCollection) {
        throw new Exception('MongoDB connection failed');
    }
    
    // Prepare update data
    $updateData = [
        'fullName' => isset($data['fullName']) ? trim($data['fullName']) : '',
        'age' => isset($data['age']) && $data['age'] !== '' ? (int)$data['age'] : null,
        'dob' => isset($data['dob']) ? trim($data['dob']) : '',
        'contact' => isset($data['contact']) ? trim($data['contact']) : '',
        'address' => isset($data['address']) ? trim($data['address']) : '',
        'updatedAt' => new MongoDB\BSON\UTCDateTime()
    ];
    
    // Update profile in MongoDB
    $result = $mongoCollection->updateOne(
        ['email' => $email],
        ['$set' => $updateData],
        ['upsert' => true]
    );
    
    if ($result->getModifiedCount() > 0 || $result->getUpsertedCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'message' => 'No changes made to profile'
        ]);
    }
    
} catch (Exception $e) {
    error_log($e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to update profile'
    ]);
}
?>
