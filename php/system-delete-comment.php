<?php
session_start();
include 'userconnection.php';

$comment_id = $_POST['comment_id'] ?? null;

if (!$comment_id) {
    echo json_encode(['status' => 'error', 'message' => 'Comment ID is required.']);
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
        WHERE comment_id = :comment_id
    ");
    $stmt->bindParam(':comment_id', $comment_id);
    // $stmt->bindParam(':user_id', $_SESSION['USER']->id);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        echo json_encode(['status' => 'error', 'message' => 'You are not authorized to delete this comment.']);
        exit();
    }

    // Delete the comment
    $deleteStmt = $con->prepare("DELETE FROM comments WHERE comment_id = :comment_id");
    $deleteStmt->bindParam(':comment_id', $comment_id);
    $deleteStmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Comment deleted successfully.']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error deleting comment: ' . $e->getMessage()]);
}
?>
