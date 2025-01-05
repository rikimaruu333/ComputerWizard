<?php
session_start();
include 'userconnection.php';

try {
    // Open connection
    $connection = new Connection();
    $con = $connection->openConnection();

    // Get the filter values from the AJAX request
    $category = isset($_GET['category']) ? $_GET['category'] : '';
    $order = isset($_GET['order']) ? $_GET['order'] : '';
    $address = isset($_GET['address']) ? $_GET['address'] : '';
    $gender = isset($_GET['gender']) ? $_GET['gender'] : '';

    $currentUserID = $_SESSION['USER']->id;
    $currentUserProfile = $_SESSION['USER']->profile;
    $currentUserUsertype = $_SESSION['USER']->usertype;

    // Base query to fetch posts and user details
    $query = "
        SELECT jp.post_id AS post_id, jp.post_client_id AS post_client_id, jp.post_description AS caption, jp.post_job_category AS job_category, jp.post_job AS job, jp.post_date AS post_created, u.id AS id, u.usertype, u.firstname, u.lastname, u.profile
        FROM jobposts jp
        JOIN users u ON jp.post_client_id = u.id
        WHERE 1=1
    ";

    // Apply category filter if provided
    if (!empty($category)) {
        $query .= " AND jp.post_job_category = :category";
    }
    
    // Apply address filter if provided
    if (!empty($address)) {
        $query .= " AND u.address = :address";
    }

    // Apply gender filter if provided
    if (!empty($gender)) {
        $query .= " AND u.gender = :gender";
    }

    // Apply order filter
    if ($order == 'Oldest Post') {
        $query .= " ORDER BY jp.post_date ASC";
    } else {
        // Default to 'Latest Post'
        $query .= " ORDER BY jp.post_date DESC";
    }

    // Prepare the query
    $stmt = $con->prepare($query);

    // Bind parameters if applicable
    if (!empty($category)) {
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    }
    // Bind parameters if applicable
    if (!empty($address)) {
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    }
    if (!empty($gender)) {
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
    }

    // Execute the query
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

        // Check if there is a tagged freelancer for the post
        $taggedStmt = $con->prepare("SELECT COUNT(*) FROM jobposts WHERE post_id = :post_id AND post_tagged_user_id != '0'");
        $taggedStmt->bindParam(':post_id', $post->post_id, PDO::PARAM_INT);
        $taggedStmt->execute();
        $hasTaggedFreelancer = $taggedStmt->fetchColumn() > 0;



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
            'owner_id' => $post->post_client_id,
            'images' => $images,
            'total_comments' => $total_comments,
            'currentUserID' => $currentUserID,
            'currentUserProfile' => $currentUserProfile,
            'currentUserUsertype' => $currentUserUsertype,
            'hasTaggedFreelancer' => $hasTaggedFreelancer 
        ];
    }

    echo json_encode($response); // Return the array of posts as JSON
} catch (Exception $e) {
    echo json_encode(['error' => 'Error fetching posts: ' . $e->getMessage()]);
}
?>
