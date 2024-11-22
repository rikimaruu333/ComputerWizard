<?php
session_start();
include 'userconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Check if the post ID was provided
        if (!isset($_POST['post_id'])) {
            echo json_encode(['error' => 'Post ID is required']);
            exit;
        }

        $postID = $_POST['post_id'];

        // Open connection
        $connection = new Connection();
        $con = $connection->openConnection();

        // Get the current user's ID and profile from the session
        $currentUserID = $_SESSION['USER']->id;
        $currentUserUsertype = $_SESSION['USER']->usertype;

        // Prepare a query to check if the user is the owner of the post or an admin
        $checkStmt = $con->prepare("
            SELECT post_client_id 
            FROM jobposts 
            WHERE post_id = :post_id
        ");
        $checkStmt->bindParam(':post_id', $postID, PDO::PARAM_INT);
        $checkStmt->execute();
        $postOwner = $checkStmt->fetchColumn();

        // Check if the current user is the owner or an admin
        if ($postOwner == $currentUserID || $currentUserUsertype === 'Admin') {
            // If they are, delete the post
            $deleteStmt = $con->prepare("DELETE FROM jobposts WHERE post_id = :post_id");
            $deleteStmt->bindParam(':post_id', $postID, PDO::PARAM_INT);
            $deleteStmt->execute();

            // Also delete associated images and comments if necessary
            $deleteImagesStmt = $con->prepare("DELETE FROM jobposts_images WHERE post_id = :post_id");
            $deleteImagesStmt->bindParam(':post_id', $postID, PDO::PARAM_INT);
            $deleteImagesStmt->execute();

            $deleteCommentsStmt = $con->prepare("DELETE FROM comments WHERE post_id = :post_id");
            $deleteCommentsStmt->bindParam(':post_id', $postID, PDO::PARAM_INT);
            $deleteCommentsStmt->execute();

            // Return a success response
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'You are not authorized to delete this post.']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error deleting post: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
