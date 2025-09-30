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

try {
    // Get profile from MongoDB
    $mongoCollection = getMongoDBConnection();
    
    if (!$mongoCollection) {
        throw new Exception('MongoDB connection failed');
    }
    
    $profile = $mongoCollection->findOne(['email' => $email]);
    
    if (!$profile) {
        // Create default profile if not exists
        $profile = [
            'email' => $email,
            'fullName' => '',
            'age' => null,
            'dob' => '',
            'contact' => '',
            'address' => '',
            'createdAt' => new MongoDB\BSON\UTCDateTime(),
            'updatedAt' => new MongoDB\BSON\UTCDateTime()
        ];
        
        $mongoCollection->insertOne($profile);
    }
    
    // Convert MongoDB document to array
    $profileData = [
        'email' => $email,
        'fullName' => isset($profile['fullName']) ? $profile['fullName'] : '',
        'age' => isset($profile['age']) ? $profile['age'] : null,
        'dob' => isset($profile['dob']) ? $profile['dob'] : '',
        'contact' => isset($profile['contact']) ? $profile['contact'] : '',
        'address' => isset($profile['address']) ? $profile['address'] : ''
    ];
    
    echo json_encode([
        'success' => true,
        'data' => $profileData
    ]);
    
} catch (Exception $e) {
    error_log($e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch profile'
    ]);
}
?>
