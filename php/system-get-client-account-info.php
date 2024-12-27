<?php
session_start();
include 'userconnection.php';

try {
    // Open connection
    $connection = new Connection();
    $con = $connection->openConnection();

    $currentUserId = $_SESSION['USER']->id;
    $currentUserProfile = $_SESSION['USER']->profile;
    $currentUserUsertype = $_SESSION['USER']->usertype;
    // Get the selected client's ID from the AJAX request
    $selectedClientID = isset($_GET['client_id']) ? $_GET['client_id'] : null;

    if (!$selectedClientID) {
        echo json_encode(['error' => 'Client ID not provided.']);
        exit;
    }

    // Get the current user's profile from the session
    $currentUserProfile = $_SESSION['USER']->profile;

    // Prepare statement to fetch the client details
    $clientQuery = $con->prepare("SELECT * FROM users WHERE id = :client_id");
    $clientQuery->bindParam(':client_id', $selectedClientID, PDO::PARAM_INT);
    $clientQuery->execute();

    // Fetch client data
    $clientData = $clientQuery->fetch(PDO::FETCH_ASSOC);

    // Prepare statement to count the total job posts for the selected client
    $countStmt = $con->prepare("SELECT COUNT(*) AS total_jobposts FROM jobposts WHERE post_client_id = :client_id");
    $countStmt->bindParam(':client_id', $selectedClientID, PDO::PARAM_INT);
    $countStmt->execute();
    $total_jobposts = $countStmt->fetchColumn(); // Fetch the total job posts count

    // Count completed transactions
    $completedTransactionStmt = $con->prepare("
        SELECT COUNT(*) AS completed_transactions 
        FROM booking 
        WHERE client_id = :client_id AND booking_status = 'Completed'
    ");
    $completedTransactionStmt->bindParam(':client_id', $selectedClientID, PDO::PARAM_INT);
    $completedTransactionStmt->execute();
    $completedTransactions = $completedTransactionStmt->fetchColumn();

    // Prepare statement to fetch the posts for the selected client along with user details
    $postsQuery = $con->prepare("
        SELECT jp.post_id AS post_id, jp.post_client_id AS post_client_id, jp.post_description AS caption, jp.post_job_category AS job_category, jp.post_job AS job, jp.post_date AS post_created, u.id AS id, u.usertype, u.firstname, u.lastname, u.profile
        FROM jobposts jp
        JOIN users u ON jp.post_client_id = u.id
        WHERE jp.post_client_id = :client_id
        ORDER BY jp.post_date DESC
    ");
    $postsQuery->bindParam(':client_id', $selectedClientID, PDO::PARAM_INT); // Bind selected client ID
    $postsQuery->execute();

    $posts = $postsQuery->fetchAll(PDO::FETCH_OBJ); // Fetch posts data as objects

    // Prepare response
    $response = [
        'client' => $clientData,
        'total_jobposts' => $total_jobposts,
        'completed_transactions' => $completedTransactions,
        'posts' => []
    ];

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
        $response['posts'][] = [
            'post_id' => $post->post_id,
            'caption' => $post->caption,
            'job_category' => $post->job_category,
            'job' => $post->job,
            'post_created' => $post->post_created,
            'firstname' => $post->firstname,
            'lastname' => $post->lastname,
            'usertype' => $post->usertype,
            'profile' => $post->profile,
            'owner_id' => $post->post_client_id,
            'images' => $images,
            'total_comments' => $total_comments,
            'currentUserId' => $currentUserId,
            'currentUserProfile' => $currentUserProfile,
            'currentUserUsertype' => $currentUserUsertype
        ];
    }

    echo json_encode($response); // Return the client data and posts as JSON
} catch (Exception $e) {
    echo json_encode(['error' => 'Error fetching data: ' . $e->getMessage()]);
}
