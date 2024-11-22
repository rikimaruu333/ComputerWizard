<?php
session_start();
include 'userconnection.php';

$comment_id = $_POST['comment_id'] ?? null;
$new_content = $_POST['content'] ?? null;

if (!$comment_id || !$new_content) {
    echo json_encode(['status' => 'error', 'message' => 'Comment ID and content are required.']);
    exit();
}

try {
    // Open database connection
    $connection = new Connection();
    $con = $connection->openConnection();

    // Check if the logged-in user owns the comment
    $stmt = $con->prepare("
        SELECT comment_id 
        FROM comments 
        WHERE comment_id = :comment_id AND user_id = :user_id
    ");
    $stmt->bindParam(':comment_id', $comment_id);
    $stmt->bindParam(':user_id', $_SESSION['USER']->id);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        echo json_encode(['status' => 'error', 'message' => 'You are not authorized to update this comment.']);
        exit();
    }

    // Update the comment
    $updateStmt = $con->prepare("UPDATE comments SET comment_content = :content WHERE comment_id = :comment_id");
    $updateStmt->bindParam(':content', $new_content);
    $updateStmt->bindParam(':comment_id', $comment_id);
    $updateStmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Comment updated successfully.']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error updating comment: ' . $e->getMessage()]);
}
?>
