<?php
session_start();
include 'userconnection.php'; // Include your connection class

// Create a new instance of the Connection class
$connection = new Connection();
$con = $connection->getConnection(); // Get the PDO connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['post_id'];
    $freelancerId = $_POST['freelancer_id'];

    if (!empty($postId) && !empty($freelancerId)) {
        try {
            // Update query to set the new tagged freelancer for the post
            $stmt = $con->prepare("UPDATE jobposts SET post_tagged_user_id = :freelancer_id WHERE post_id = :post_id");
            $stmt->bindParam(':freelancer_id', $freelancerId, PDO::PARAM_INT);
            $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Fetch freelancer details for response
                $stmt = $con->prepare("SELECT firstname, lastname, profile FROM users WHERE id = :freelancer_id");
                $stmt->bindParam(':freelancer_id', $freelancerId, PDO::PARAM_INT);
                $stmt->execute();
                $freelancer = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($freelancer) {
                    echo json_encode(['success' => true, 'freelancer' => $freelancer]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Freelancer not found.']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to update the database.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid input data.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>
