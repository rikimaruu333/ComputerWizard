<?php
session_start();
include 'userconnection.php';

// Open connection
$connection = new Connection();
$con = $connection->openConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['post_id'];

    // Debugging: Log the received POST data
    error_log("Received post_id: $postId");

    // Query to fetch the tagged user ID from the posts table
    $stmt = $con->prepare('SELECT post_tagged_user_id FROM jobposts WHERE post_id = :post_id');
    $stmt->execute(['post_id' => $postId]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post) {
        $taggedUserId = $post['post_tagged_user_id'];

        // Prepare statement to fetch freelancers who have an approved or completed booking with the client
        $freelancerStmt = $con->prepare("
        SELECT u.id AS freelancer_id, u.firstname, u.lastname, u.profile
        FROM users u
        JOIN booking b ON u.id = b.freelancer_id
        WHERE b.client_id = :user_id
        AND (b.booking_status = 'Approved' OR b.booking_status = 'Completed')
        ");
        $freelancerStmt->bindParam(':user_id', $_SESSION['USER']->id, PDO::PARAM_INT); // Use session user_id for client
        $freelancerStmt->execute();

        // Fetch freelancers
        $freelancers = $freelancerStmt->fetchAll(PDO::FETCH_OBJ); // Fetch as an object

        // Fetch tagged user details if available
        if (!empty($taggedUserId)) {
            $userStmt = $con->prepare('SELECT id, profile, firstname, lastname FROM users WHERE id = :user_id');
            $userStmt->execute(['user_id' => $taggedUserId]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
        }

        // Prepare the response
        $response = [];
        
        if ($user) {
            $response['taggedUser'] = $user;  // Include the tagged freelancer info
        }

        $response['freelancers'] = $freelancers;  // Include the freelancers associated with the post

        // Return the response
        echo json_encode(['success' => true, 'data' => $response]);
    } else {
        error_log("No post found with ID: $postId");
        echo json_encode(['success' => false, 'message' => 'No post found with the given ID.']);
    }
} else {
    error_log("Invalid request method.");
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
