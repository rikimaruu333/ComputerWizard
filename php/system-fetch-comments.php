<?php
session_start();
include 'userconnection.php';

$post_id = $_GET['post_id'] ?? null;

if (!$post_id) {
    echo json_encode(['status' => 'error', 'message' => 'Post ID is required.']);
    exit();
}

try {
    // Open database connection
    $connection = new Connection();
    $con = $connection->openConnection();

    // Fetch post owner ID
    $postOwnerStmt = $con->prepare("
        SELECT post_client_id AS post_owner_id 
        FROM jobposts 
        WHERE post_id = :post_id
    ");
    $postOwnerStmt->bindParam(':post_id', $post_id);
    $postOwnerStmt->execute();
    $postOwner = $postOwnerStmt->fetch(PDO::FETCH_ASSOC);

    if (!$postOwner) {
        echo json_encode(['status' => 'error', 'message' => 'Post not found.']);
        exit();
    }

    $currentUserId = $_SESSION['USER']->id;

    // Fetch comments for the specified post
    $stmt = $con->prepare("
        SELECT 
            c.comment_id AS comment_id,
            c.comment_content AS content, 
            c.comment_date AS comment_date, 
            CONCAT(u.firstname, ' ', u.lastname) AS username, 
            u.usertype, 
            u.profile,
            CASE WHEN c.user_id = :current_user_id THEN 1 ELSE 0 END AS is_owner
        FROM comments c 
        JOIN users u ON c.user_id = u.id 
        WHERE c.post_id = :post_id 
        ORDER BY c.comment_date DESC
    ");
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':current_user_id', $currentUserId);
    $stmt->execute();

    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return success response with comments
    echo json_encode([
        'status' => 'success',
        'comments' => $comments
    ]);
} catch (Exception $e) {
    // Error handling
    echo json_encode(['status' => 'error', 'message' => 'Error fetching comments: ' . $e->getMessage()]);
}
?>
