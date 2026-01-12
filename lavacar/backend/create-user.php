<?php
/**
 * Command-line user creation script
 * Usage: php create-user.php --username=john --email=john@company.com --password=secret --role=admin
 */

require_once 'Database.php';

function createUser($username, $email, $password, $role = 'user', $first_name = '', $last_name = '', $department = '') {
    $database = new Database();
    $db = $database->getConnection();
    
    // Check if user already exists
    $check_query = "SELECT id FROM users WHERE username = ? OR email = ?";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->execute([$username, $email]);
    
    if($check_stmt->rowCount() > 0) {
        return "Error: Username or email already exists\n";
    }
    
    // Create user
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    $query = "INSERT INTO users (username, email, password_hash, first_name, last_name, role, department) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($query);
    
    if($stmt->execute([$username, $email, $password_hash, $first_name, $last_name, $role, $department])) {
        return "User created successfully: $username ($role)\n";
    } else {
        return "Error creating user\n";
    }
}

// If running from command line
if(php_sapi_name() === 'cli') {
    $options = getopt("", [
        "username:",
        "email:",
        "password:",
        "role:",
        "first_name:",
        "last_name:",
        "department:"
    ]);
    
    if(empty($options['username']) || empty($options['email']) || empty($options['password'])) {
        echo "Usage: php create-user.php --username=USERNAME --email=EMAIL --password=PASSWORD [--role=ROLE] [--first_name=FNAME] [--last_name=LNAME] [--department=DEPT]\n";
        echo "Roles: user, auditor, compliance_manager, admin (default: user)\n";
        exit(1);
    }
    
    $username = $options['username'];
    $email = $options['email'];
    $password = $options['password'];
    $role = $options['role'] ?? 'user';
    $first_name = $options['first_name'] ?? '';
    $last_name = $options['last_name'] ?? '';
    $department = $options['department'] ?? '';
    
    $result = createUser($username, $email, $password, $role, $first_name, $last_name, $department);
    echo $result;
    
} else {
    // If accessed via web, return JSON response
    header('Content-Type: application/json');
    
    if($_POST && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        $result = createUser(
            $_POST['username'],
            $_POST['email'],
            $_POST['password'],
            $_POST['role'] ?? 'user',
            $_POST['first_name'] ?? '',
            $_POST['last_name'] ?? '',
            $_POST['department'] ?? ''
        );
        
        echo json_encode(['message' => $result]);
    } else {
        echo json_encode(['error' => 'Missing required parameters']);
    }
}
?>