<?php
session_start();
include 'userconnection.php';

$conn = new Connection();
$pdo = $conn->openConnection();

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Fetch all users who are currently restricted and check their unrestrict date
$query = "SELECT u.id, u.email, r.unrestrict_date, r.report_status 
          FROM users u 
          JOIN restrictions r ON u.id = r.restricted_user_id 
          WHERE u.status = 3 AND r.report_status = 'Processed' AND r.date_unrestricted IS NULL";
$stmt = $pdo->prepare($query);
$stmt->execute();
$restrictedUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Current date and time
$currentDateTime = date('Y-m-d H:i:s');

$response = []; // Array to collect responses

foreach ($restrictedUsers as $user) {
    // Compare the current datetime with the unrestrict_date
    if ($user['unrestrict_date'] && strtotime($currentDateTime) >= strtotime($user['unrestrict_date'])) {
        // Start a transaction to ensure consistency
        $pdo->beginTransaction();

        try {
            // Update the restriction record to set date_unrestricted
            $updateQuery = "UPDATE restrictions 
                            SET date_unrestricted = NOW() 
                            WHERE restricted_user_id = :userId AND date_unrestricted IS NULL";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':userId', $user['id']);
            $updateStmt->execute();

            // Update user status to unrestricted (status = 1)
            $statusUpdateQuery = "UPDATE users SET status = 1 WHERE id = :userId";
            $statusUpdateStmt = $pdo->prepare($statusUpdateQuery);
            $statusUpdateStmt->bindParam(':userId', $user['id']);
            $statusUpdateStmt->execute();

            // Mark relevant reports as Settled
            $reportsUpdateQuery = "UPDATE reports 
                                   SET report_status = 'Settled' 
                                   WHERE reported_user_id = :userId AND report_status = 'Processed'";
            $reportsUpdateStmt = $pdo->prepare($reportsUpdateQuery);
            $reportsUpdateStmt->bindParam(':userId', $user['id']);
            $reportsUpdateStmt->execute();

            // Commit the transaction
            $pdo->commit();

            // Prepare response data
            $response[] = [
                'email' => $user['email'],
                'isUnrestricted' => true
            ];

            // Optional: Log the unrestriction for debugging
            error_log("User with ID {$user['id']} and email {$user['email']} has been unrestricted and marked as Settled.");
        } catch (Exception $e) {
            // Rollback the transaction on error
            $pdo->rollBack();

            // Log the error
            error_log("Failed to auto-unrestrict user with ID {$user['id']}: " . $e->getMessage());

            // Add an error to the response
            $response[] = [
                'email' => $user['email'],
                'isUnrestricted' => false,
                'error' => 'Failed to auto-unrestrict user.'
            ];
        }
    }
}

// Return the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
