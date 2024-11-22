<?php
require 'userconnection.php';
session_start();

if (!isset($_SESSION['USER'])) {
    http_response_code(401); 
    echo json_encode(['message' => 'User not logged in.']);
    exit();
}

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['USER']->id;
    $old_password = $_POST['oldPassword'] ?? '';
    $new_password = $_POST['newPassword'] ?? '';

    $conn = new Connection();
    $pdo = $conn->getConnection(); 
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch();

    if (hash('sha256', $old_password) !== $user->password) {
        http_response_code(400); 
        $response['message'] = 'Old password is incorrect.';
    } else {
        $hashed_password = hash('sha256', $new_password); 

        // Update password in database
        $update_stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
        $update_stmt->execute(['password' => $hashed_password, 'id' => $user_id]);

        $response['success'] = true;
        $response['message'] = 'Your password has been updated successfully.';
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
