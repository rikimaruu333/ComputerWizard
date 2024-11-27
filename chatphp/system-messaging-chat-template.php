<?php
session_start();
include_once "../php/userconnection.php"; // Database connection

$id = isset($_GET['id']) ? $_GET['id'] : null;  // Ensure ID is provided
$usertype = isset($_GET['usertype']) ? $_GET['usertype'] : null;  // Ensure userType is provided

if (!$id || !$usertype) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters provided.']);
    exit;
}

try {
    // Fetch user details
    $stmt = $newconnection->openConnection()->prepare("SELECT * FROM users WHERE unique_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if ($user) {
        // Default counts
        $transactionCount = 0;
        $jobPostsCount = 0;
        $recommendationCount = 0;

        if ($usertype === 'Client') {
            // Count total job posts
            $jobPostsStmt = $newconnection->openConnection()->prepare("SELECT COUNT(*) AS total_jobposts FROM jobposts WHERE post_client_id = :user_id");
            $jobPostsStmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
            $jobPostsStmt->execute();
            $jobPostsCount = $jobPostsStmt->fetchColumn();

            // Count completed transactions
            $transactionStmt = $newconnection->openConnection()->prepare("SELECT COUNT(*) AS transaction_count FROM booking WHERE client_id = :client_id AND booking_status = 'Completed'");
            $transactionStmt->bindParam(':client_id', $user->id, PDO::PARAM_INT);
        } elseif ($usertype === 'Freelancer') {
            // Count recommendations and average ratings
            $recommendationStmt = $newconnection->openConnection()->prepare("
                SELECT 
                    AVG(r.rating) AS avg_rating,
                    COUNT(CASE WHEN r.recommendation = 'Recommended' THEN 1 END) AS recommendation_count
                FROM reviews r 
                WHERE r.freelancer_id = :freelancer_id
            ");
            $recommendationStmt->bindParam(':freelancer_id', $user->id, PDO::PARAM_INT);
            $recommendationStmt->execute();
            $recommendationData = $recommendationStmt->fetch(PDO::FETCH_ASSOC);
            $recommendationCount = $recommendationData['recommendation_count'];

            // Count completed transactions
            $transactionStmt = $newconnection->openConnection()->prepare("SELECT COUNT(*) AS transaction_count FROM booking WHERE freelancer_id = :freelancer_id AND booking_status = 'Completed'");
            $transactionStmt->bindParam(':freelancer_id', $user->id, PDO::PARAM_INT);
        }

        // Execute transaction statement if applicable
        if (isset($transactionStmt)) {
            $transactionStmt->execute();
            $transactionData = $transactionStmt->fetch(PDO::FETCH_ASSOC);
            $transactionCount = $transactionData['transaction_count'];
        }

        // Prepare response data
        echo json_encode([
            'status' => 'success',
            'id' => htmlspecialchars($user->id),
            'firstname' => htmlspecialchars($user->firstname),
            'lastname' => htmlspecialchars($user->lastname),
            'fullname' => htmlspecialchars($user->firstname) . ' ' . htmlspecialchars($user->lastname),
            'email' => htmlspecialchars($user->email),
            'phone' => htmlspecialchars($user->phone),
            'profile' => htmlspecialchars($user->profile),
            'usertype' => $usertype,
            'incoming_id' => $id,  // Incoming ID used for chat
            'transaction_count' => $transactionCount,
            'job_posts_count' => $jobPostsCount,
            'recommendation_count' => $recommendationCount,
            
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
