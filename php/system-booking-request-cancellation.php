<?php
session_start();
include 'userconnection.php';

header('Content-Type: application/json');

$conn = new Connection();
$pdo = $conn->openConnection();

if (isset($_POST['booking_id'])) {
    $bookingId = $_POST['booking_id'];
    $clientId = $_SESSION['USER']->id;
    
    // Update booking status instead of deleting the booking
    $query = "UPDATE booking SET booking_status = 'Cancelled' WHERE booking_id = :bookingId AND client_id = :clientId";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':bookingId', $bookingId);
    $stmt->bindParam(':clientId', $clientId);

    if ($stmt->execute()) {
        // Fetch the freelancer ID to notify
        $stmt = $pdo->prepare("SELECT freelancer_id FROM booking WHERE booking_id = :bookingId AND booking_status = 'Cancelled'");
        $stmt->bindParam(':bookingId', $bookingId);
        $stmt->execute();
        $freelancerId = $stmt->fetchColumn();

        // Notification payload
        $notificationData = [
            'userId' => $freelancerId,
            'action' => 'Booking Cancelled',
            'message' => 'A booking request has been cancelled by the client.'
        ];

        // Send the notification via CURL
        $ch = curl_init('http://localhost:8080/emit-booking-cancellation-notification');
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
