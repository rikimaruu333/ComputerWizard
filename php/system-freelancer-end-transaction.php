<?php
session_start(); // Assuming session is used for user IDs
include 'userconnection.php'; // Include the database connection file

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

$response = array('success' => false, 'message' => '');

// Initialize the database connection
$pdo = $newconnection->getConnection();

// Check if the required data is provided
if (isset($_POST['booking_id'])) {
    $bookingId = $_POST['booking_id'];

    try {
        // Start the transaction
        $pdo->beginTransaction();

        // Check if client_ET_request is already set to 'End'
        $checkQuery = "SELECT client_ET_request FROM booking WHERE booking_id = :booking_id";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->bindParam(':booking_id', $bookingId);
        $checkStmt->execute();
        
        $booking = $checkStmt->fetch(PDO::FETCH_ASSOC);

        // Update the booking status based on the client_ET_request
        if ($booking && $booking['client_ET_request'] === 'End') {
            // Set booking_status to 'Completed'
            $updateQuery = "UPDATE booking SET freelancer_ET_request = 'End', booking_status = 'Completed', end_date = NOW() WHERE booking_id = :booking_id";
        } else {
            // Just set freelancer_ET_request to 'End'
            $updateQuery = "UPDATE booking SET freelancer_ET_request = 'End' WHERE booking_id = :booking_id";
        }

        $stmt = $pdo->prepare($updateQuery);
        $stmt->bindParam(':booking_id', $bookingId);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update booking status.");
        }

        // Commit transaction
        $pdo->commit();

        $response['success'] = true;
        $response['message'] = 'Transaction ended successfully.';
    } catch (Exception $e) {
        // Roll back transaction in case of error
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $response['message'] = $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid data provided.';
}

// Close the database connection
$newconnection->closeConnection();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
