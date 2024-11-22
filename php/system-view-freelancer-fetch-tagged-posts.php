<?php
session_start();
include 'userconnection.php';

try {
    // Open connection
    $connection = new Connection();
    $con = $connection->openConnection();

    // Get the current user's ID and profile from the session
    $currentUserID = $_SESSION['USER']->id;
    $currentUserProfile = $_SESSION['USER']->profile;

    // Validate and fetch the freelancer_id from the GET request
    if (!isset($_GET['freelancer_id']) || empty($_GET['freelancer_id'])) {
        echo json_encode(['error' => 'Freelancer ID not provided.']);
        exit;
    }

    $freelancerId = intval($_GET['freelancer_id']); // Convert to integer for security

    // Prepare statement to fetch posts tagged to the sent freelancer_id
    $stmt = $con->prepare("
        SELECT jp.post_id AS post_id, jp.post_client_id, jp.post_tagged_user_id, jp.post_description AS caption, jp.post_date AS post_created, 
               u.id AS id, u.usertype, u.firstname, u.lastname, u.profile
        FROM jobposts jp
        JOIN users u ON jp.post_client_id = u.id
        WHERE jp.post_tagged_user_id = :freelancer_id
        ORDER BY jp.post_date DESC
    ");
    $stmt->bindParam(':freelancer_id', $freelancerId, PDO::PARAM_INT); // Bind the sent freelancer ID
    $stmt->execute();

    $posts = $stmt->fetchAll(PDO::FETCH_OBJ); // Fetch data as objects

    // Prepare response
    $response = [];
    foreach ($posts as $post) {
        // Fetch associated images for each post
        $imageStmt = $con->prepare("SELECT image_path FROM jobposts_images WHERE post_id = :post_id");
        $imageStmt->bindParam(':post_id', $post->post_id, PDO::PARAM_INT);
        $imageStmt->execute();
        $images = $imageStmt->fetchAll(PDO::FETCH_COLUMN, 0); // Fetch only the image paths

        // Fetch total comments count for the post
        $commentStmt = $con->prepare("SELECT COUNT(*) AS total_comments FROM comments WHERE post_id = :post_id");
        $commentStmt->bindParam(':post_id', $post->post_id, PDO::PARAM_INT);
        $commentStmt->execute();
        $total_comments = $commentStmt->fetchColumn();

        // Add post details, images, and other info to the response
        $response[] = [
            'post_id' => $post->post_id,
            'caption' => $post->caption,
            'post_created' => $post->post_created,
            'firstname' => $post->firstname,
            'lastname' => $post->lastname,
            'usertype' => $post->usertype,
            'profile' => $post->profile,
            'owner_id' => $post->post_client_id,
            'images' => $images,
            'total_comments' => $total_comments,
            'currentUserID' => $currentUserID,
            'currentUserProfile' => $currentUserProfile
        ];
    }

    echo json_encode($response); // Return the array of posts as JSON
} catch (Exception $e) {
    echo json_encode(['error' => 'Error fetching posts: ' . $e->getMessage()]);
}
