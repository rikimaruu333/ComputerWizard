<?php
session_start();
include 'userconnection.php'; // Include your connection class

// Create a new instance of the Connection class
$newConnection = new Connection();
$pdo = $newConnection->getConnection(); // Get the PDO connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['post_id'];
    $freelancerId = $_POST['freelancer_id'];

    if (!empty($postId) && !empty($freelancerId)) {
        $stmt = $pdo->prepare("UPDATE jobposts SET post_tagged_user_id = :freelancer_id WHERE post_id = :post_id");
        $stmt->bindParam(':freelancer_id', $freelancerId, PDO::PARAM_INT);
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update the database.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid input data.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>
