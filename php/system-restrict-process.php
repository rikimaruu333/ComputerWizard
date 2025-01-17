<?php
session_start();
include 'userconnection.php';

$conn = new Connection();
$pdo = $conn->openConnection();

// Get the POST data
$userId = $_POST['userId'];
$restrictReason = $_POST['restrictReason'];
$unrestrictDate = $_POST['unrestrictDate'];
$adminNotes = $_POST['adminNotes'];

// Fetch the user's email before restricting them
$query = "SELECT email FROM users WHERE id = :userId"; 
$stmt = $pdo->prepare($query);
$stmt->bindParam(':userId', $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Prepare to insert the restriction details into the database
    $insertQuery = "INSERT INTO restrictions (restricted_user_id, restrict_reason, unrestrict_date, restrict_note, restriction_date) 
                    VALUES (:restrictedUserId, :restrictReason, :unrestrictDate, :adminNotes, NOW())";
    $insertStmt = $pdo->prepare($insertQuery);

    // Bind parameters for the restriction
    $insertStmt->bindParam(':restrictedUserId', $userId);
    $insertStmt->bindParam(':restrictReason', $restrictReason);
    $insertStmt->bindParam(':unrestrictDate', $unrestrictDate);
    $insertStmt->bindParam(':adminNotes', $adminNotes);

    // Execute the insertion and update user's status
    if ($insertStmt->execute()) {
        // Update the user's status to restricted (status = 3)
        $updateQuery = "UPDATE users SET status = 3 WHERE id = :userId";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':userId', $userId);
        
        if ($updateStmt->execute()) {
            // Update all report_status to 'Processed' for the restricted user
            $updateReportsQuery = "UPDATE reports 
                                   SET report_status = 'Processed' 
                                   WHERE reported_user_id = :userId AND report_status = 'Pending'";
            $updateReportsStmt = $pdo->prepare($updateReportsQuery);
            $updateReportsStmt->bindParam(':userId', $userId);

            if ($updateReportsStmt->execute()) {
                // Emit restriction event using a Socket.IO client
                $socketUrl = 'http://localhost:8080'; // Adjust the URL if needed
                $socketMessage = json_encode(['userId' => $userId, 'isRestricted' => true]);

                // Send the restriction event to the server
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $socketUrl . '/emit-restriction');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $socketMessage);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($socketMessage)
                ]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_exec($ch);
                curl_close($ch);

                // Return the user's email and success response
                echo json_encode([
                    'email' => $user['email'],
                    'status' => 'success'
                ]);
            } else {
                // Handle errors during reports update
                http_response_code(500); // Internal Server Error
                echo json_encode(['error' => 'Failed to update report statuses.']);
            }
        } else {
            // Handle errors during status update
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Failed to update user status.']);
        }
    } else {
        // Handle any errors during insertion
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Failed to restrict user.']);
    }
} else {
    // Handle the case where the user is not found
    http_response_code(404); // Not Found
    echo json_encode(['error' => 'User not found.']);
}
?>
