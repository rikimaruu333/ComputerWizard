<?php
session_start();
include 'userconnection.php';

header('Content-Type: application/json');

$conn = new Connection();
$pdo = $conn->openConnection();

if (isset($_POST['booking_id'])) {
    $bookingId = $_POST['booking_id'];
    $freelancerId = $_SESSION['USER']->id;
    
    // Update booking status instead of deleting the booking
    $query = "UPDATE booking SET booking_status = 'Rejected' WHERE booking_id = :bookingId AND freelancer_id = :freelancerId";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':bookingId', $bookingId);
    $stmt->bindParam(':freelancerId', $freelancerId);

    if ($stmt->execute()) {
        // Fetch the freelancer ID to notify
        $stmt = $pdo->prepare("SELECT client_id FROM booking WHERE booking_id = :bookingId AND booking_status = 'Rejected'");
        $stmt->bindParam(':bookingId', $bookingId);
        $stmt->execute();
        $clientId = $stmt->fetchColumn();

        // Notification payload
        $notificationData = [
            'userId' => $clientId,
            'action' => 'Booking Rejected',
            'message' => 'A booking request has been rejected by the freelancer.'
        ];

        // Send the notification via CURL
        $ch = curl_init('http://localhost:8080/emit-booking-rejection-notification');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notificationData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        curl_close($ch);

        // Error handling for CURL
        if ($response === false) {
            echo json_encode(['success' => false, 'error' => 'Notification request failed: ' . curl_error($ch)]);
        } else {
            echo json_encode(['success' => true, 'notification_data' => $notificationData, 'server_response' => $response]);        
        }

        curl_close($ch);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update booking status: ' . implode(", ", $stmt->errorInfo())]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Booking ID not provided.']);
}
?>
