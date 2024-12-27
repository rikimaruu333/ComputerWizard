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

    // Prepare statement to count the total job posts for the current user
    $countStmt = $con->prepare("SELECT COUNT(*) AS total_jobposts FROM jobposts WHERE post_client_id = :user_id");
    $countStmt->bindParam(':user_id', $currentUserID, PDO::PARAM_INT);
    $countStmt->execute();
    $total_jobposts = $countStmt->fetchColumn(); // Fetch the total job posts count

    // Prepare statement to fetch only the posts for the current logged-in client along with user details
    $stmt = $con->prepare("
        SELECT jp.post_id AS post_id, jp.post_tagged_user_id, jp.post_description AS caption, jp.post_job_category AS job_category, jp.post_job AS job, jp.post_date AS post_created, u.id AS id, u.usertype, u.firstname, u.lastname, u.profile
        FROM jobposts jp
        JOIN users u ON jp.post_client_id = u.id
        WHERE jp.post_client_id = :user_id
        ORDER BY jp.post_date DESC
    ");
    $stmt->bindParam(':user_id', $currentUserID, PDO::PARAM_INT); // Bind current user ID
    $stmt->execute();
    
    $posts = $stmt->fetchAll(); // Fetch data as objects due to PDO::FETCH_OBJ

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


        // Check if a freelancer is tagged to this post
        $isTagged = $post->post_tagged_user_id !== 0;

        // Add post details, images, and other info to the response
        $response[] = [
            'post_id' => $post->post_id,
            'caption' => $post->caption,
            'job_category' => $post->job_category,
            'job' => $post->job,
            'post_created' => $post->post_created,
            'firstname' => $post->firstname,
            'lastname' => $post->lastname,
            'usertype' => $post->usertype,
            'profile' => $post->profile,
            'images' => $images,
            'total_comments' => $total_comments,
            'currentUserID' => $currentUserID,
            'currentUserProfile' => $currentUserProfile,
            'total_jobposts' => $total_jobposts, // Include total job posts count
            'freelancers' => $freelancers, // Include freelancers for this post
            'isTagged' => $isTagged // Add tagged status
        ];
    }

    echo json_encode($response); // Return the array of posts as JSON
} catch (Exception $e) {
    echo json_encode(['error' => 'Error fetching posts: ' . $e->getMessage()]);
}
