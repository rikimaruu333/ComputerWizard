<?php
session_start();
include 'userconnection.php';

$conn = new Connection();
$pdo = $conn->openConnection();

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Get the POST data for unrestriction
$userId = $_POST['userId'];

// Fetch the user's email
$query = "SELECT email FROM users WHERE id = :userId"; 
$stmt = $pdo->prepare($query);
$stmt->bindParam(':userId', $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $userEmail = $user['email'];

    // Fetch the restriction record for the user
    $restrictionQuery = "SELECT date_unrestricted FROM restrictions WHERE restricted_user_id = :userId AND date_unrestricted IS NULL";
    $restrictionStmt = $pdo->prepare($restrictionQuery);
    $restrictionStmt->bindParam(':userId', $userId);
    $restrictionStmt->execute();
    $restriction = $restrictionStmt->fetch(PDO::FETCH_ASSOC);

    if ($restriction) {
        // Start a transaction for consistency
        $pdo->beginTransaction();

        try {
            // Update the restriction record with the current date as date_unrestricted
            $updateQuery = "UPDATE restrictions 
                            SET date_unrestricted = NOW() 
                            WHERE restricted_user_id = :userId AND date_unrestricted IS NULL";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':userId', $userId);
            $updateStmt->execute();

            // Update user status to unrestricted
            $statusUpdateQuery = "UPDATE users SET status = 1 WHERE id = :userId";
            $statusUpdateStmt = $pdo->prepare($statusUpdateQuery);
            $statusUpdateStmt->bindParam(':userId', $userId);
            $statusUpdateStmt->execute();

            // Mark relevant reports as Settled
            $reportsUpdateQuery = "UPDATE reports 
                                   SET report_status = 'Settled' 
                                   WHERE reported_user_id = :userId AND report_status = 'Processed'";
            $reportsUpdateStmt = $pdo->prepare($reportsUpdateQuery);
            $reportsUpdateStmt->bindParam(':userId', $userId);
            $reportsUpdateStmt->execute();

            // Commit the transaction
            $pdo->commit();

            // Fetch the newly updated date_unrestricted
            $newRestrictionQuery = "SELECT date_unrestricted FROM restrictions WHERE restricted_user_id = :userId";
            $newRestrictionStmt = $pdo->prepare($newRestrictionQuery);
            $newRestrictionStmt->bindParam(':userId', $userId);
            $newRestrictionStmt->execute();
            $newRestriction = $newRestrictionStmt->fetch(PDO::FETCH_ASSOC);

            // Prepare the response to send back
            echo json_encode([
                'status' => 'success',
                'message' => 'User has been successfully unrestricted.',
                'email' => $userEmail, // Send the user's email back in the response
                'dateUnrestricted' => $newRestriction['date_unrestricted'] // Send the updated date_unrestricted
            ]);
        } catch (Exception $e) {
            // Rollback the transaction on error
            $pdo->rollBack();

            // Log the error
            error_log("Error unrestricting user with ID $userId: " . $e->getMessage());

            // Return error response
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Failed to unrestrict the user.']);
        }
    } else {
        // Handle the case where no active restriction is found
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'No active restriction found for this user.']);
    }
} else {
    // Handle the case where no user is found
    http_response_code(404); // Not Found
    echo json_encode(['error' => 'User not found.']);
}
?>
