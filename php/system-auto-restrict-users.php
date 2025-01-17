<?php
session_start();
include 'userconnection.php';

$conn = new Connection();
$pdo = $conn->openConnection();

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Current logged-in user ID
$reportedUserId = $_SESSION['USER']->id;

// Check the number of unique reporters for the reported user
$query = "SELECT COUNT(DISTINCT reporter_user_id) AS unique_reports 
          FROM reports 
          WHERE reported_user_id = :reportedUserId AND report_status = 'Pending'";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':reportedUserId', $reportedUserId);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$uniqueReports = $result['unique_reports'] ?? 0;

// Response array to track restriction status
$response = [];

// Check if the reported user has reached the limit of 10 unique reports
if ($uniqueReports >= 10) {
    // Start a transaction to ensure data consistency
    $pdo->beginTransaction();

    try {
        // Set the restriction date and unrestriction date
        $restrictionDate = date('Y-m-d H:i:s');
        $unrestrictDate = date('Y-m-d H:i:s', strtotime('+2 months', strtotime($restrictionDate)));

        // Insert restriction record into the `restrictions` table
        $insertQuery = "INSERT INTO restrictions (restricted_user_id, restrict_reason, unrestrict_date, restriction_date) 
                         VALUES (:restrictedUserId, :restrictReason, :unrestrictDate, :restrictionDate)";
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->bindParam(':restrictedUserId', $reportedUserId);
        $insertStmt->bindValue(':restrictReason', 'Reached report limit');
        $insertStmt->bindParam(':unrestrictDate', $unrestrictDate);
        $insertStmt->bindParam(':restrictionDate', $restrictionDate);
        $insertStmt->execute();

        // Update the user status to restricted (status = 3)
        $updateQuery = "UPDATE users SET status = 3 WHERE id = :userId";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':userId', $reportedUserId);
        $updateStmt->execute();

        // Update all report_status to 'Processed' for the restricted user
        $updateReportsQuery = "UPDATE reports 
                               SET report_status = 'Processed' 
                               WHERE reported_user_id = :userId AND report_status = 'Pending'";
        $updateReportsStmt = $pdo->prepare($updateReportsQuery);
        $updateReportsStmt->bindParam(':userId', $reportedUserId);
        $updateReportsStmt->execute();

        // Commit the transaction
        $pdo->commit();

        // Add to response array
        $response[] = [
            'email' => $_SESSION['USER']->email,
            'isRestricted' => true,
            'restrictReason' => 'Reached report limit',
            'unrestrictDate' => $unrestrictDate
        ];

    } catch (Exception $e) {
        // Roll back the transaction in case of an error
        $pdo->rollBack();
        
        // Log the error
        error_log("Error restricting user with ID $reportedUserId: " . $e->getMessage());

        // Add error to response array
        $response[] = [
            'status' => 'error',
            'message' => 'Failed to restrict the user.',
        ];
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
