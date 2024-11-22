<?php
session_start();
include 'userconnection.php';

$post_id = $_POST['post_id'] ?? null;
$user_id = $_POST['user_id'] ?? null;
$comment_content = $_POST['comment_content'] ?? '';

if (!$post_id || !$user_id || !$comment_content) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit();
}

try {
    // Open database connection
    $connection = new Connection();
    $con = $connection->openConnection();

    // Prepare SQL statement to insert the comment
    $stmt = $con->prepare("INSERT INTO comments (post_id, user_id, comment_content, comment_date) VALUES (:post_id, :user_id, :comment_content, NOW())");
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':comment_content', $comment_content);
    $stmt->execute();

    // Get the user details to return (e.g., username)
    $userStmt = $con->prepare("SELECT firstname, lastname FROM users WHERE id = :user_id");
    $userStmt->bindParam(':user_id', $user_id);
    $userStmt->execute();
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    // Return success response with user details
    echo json_encode([
        'status' => 'success',
        'username' => $user['firstname'] . ' ' . $user['lastname'],
        'comment_content' => $comment_content,
        'comment_date' => date('Y-m-d H:i:s') // Current timestamp for the comment
    ]);
} catch (Exception $e) {
    // Error handling
    echo json_encode(['status' => 'error', 'message' => 'Error adding comment: ' . $e->getMessage()]);
}
?>
