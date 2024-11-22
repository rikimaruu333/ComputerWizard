<?php
session_start(); // Assuming session is used for user IDs
include 'userconnection.php'; // Include the database connection file

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

$response = array('success' => false, 'message' => '');

// Initialize the database connection
$pdo = $newconnection->getConnection();

// Check if the required data is provided
if (isset($_POST['booking_id'], $_POST['rating'], $_POST['review'], $_POST['recommendation'])) {
    $bookingId = $_POST['booking_id'];
    $rating = $_POST['rating'];
    $reviewContent = $_POST['review'];
    $recommendation = $_POST['recommendation'];

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Retrieve client_id, freelancer_id, and freelancer_ET_request for the specified booking_id
        $bookingQuery = "SELECT client_id, freelancer_id, freelancer_ET_request FROM booking WHERE booking_id = :booking_id";
        $stmt = $pdo->prepare($bookingQuery);
        $stmt->bindParam(':booking_id', $bookingId);
        $stmt->execute();

        // Check if the booking exists
        if ($stmt->rowCount() === 0) {
            throw new Exception("Booking not found.");
        }

        // Fetch client_id, freelancer_id, and freelancer_ET_request
        $bookingData = $stmt->fetch(PDO::FETCH_ASSOC);
        $clientId = $bookingData['client_id'];
        $freelancerId = $bookingData['freelancer_id'];
        $freelancerETRequest = $bookingData['freelancer_ET_request'];

        // Insert review with recommendation into the reviews table
        $reviewQuery = "INSERT INTO reviews (booking_id, client_id, freelancer_id, rating, review, recommendation, review_date) 
                        VALUES (:booking_id, :client_id, :freelancer_id, :rating, :review, :recommendation, NOW())";
        $stmt = $pdo->prepare($reviewQuery);
        $stmt->bindParam(':booking_id', $bookingId);
        $stmt->bindParam(':client_id', $clientId);
        $stmt->bindParam(':freelancer_id', $freelancerId);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':review', $reviewContent);
        $stmt->bindParam(':recommendation', $recommendation);

        if (!$stmt->execute()) {
            throw new Exception("Failed to submit review.");
        }

        // Check if freelancer_ET_request is already 'End' to set booking status to 'Completed'
        if ($freelancerETRequest === 'End') {
            // Update booking status to 'Completed' and client_ET_request to 'End'
            $updateQuery = "UPDATE booking SET client_ET_request = 'End', booking_status = 'Completed', end_date = NOW() WHERE booking_id = :booking_id";
        } else {
            // Only update client_ET_request to 'End'
            $updateQuery = "UPDATE booking SET client_ET_request = 'End' WHERE booking_id = :booking_id";
        }

        $stmt = $pdo->prepare($updateQuery);
        $stmt->bindParam(':booking_id', $bookingId);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update booking status.");
        }

        // Commit transaction
        $pdo->commit();

        $response['success'] = true;
        $response['message'] = 'Transaction ended and review submitted successfully.';
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
