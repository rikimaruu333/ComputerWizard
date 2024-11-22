<?php
session_start();
include 'userconnection.php';

header('Content-Type: application/json');

$conn = new Connection();
$pdo = $conn->openConnection();

if (isset($_POST['booking_id'])) {
    $bookingId = $_POST['booking_id'];
    $freelancerId = $_SESSION['USER']->id;

    try {
        // Begin transaction to ensure all queries execute successfully
        $pdo->beginTransaction();

        // Approve the selected booking request
        $approveQuery = "UPDATE booking SET booking_status = 'Approved', start_date = NOW() WHERE booking_id = :bookingId AND freelancer_id = :freelancerId";
        $approveStmt = $pdo->prepare($approveQuery);
        $approveStmt->bindParam(':bookingId', $bookingId);
        $approveStmt->bindParam(':freelancerId', $freelancerId);

        if ($approveStmt->execute()) {
            // Fetch the client ID of the approved booking to notify
            $clientQuery = "SELECT client_id FROM booking WHERE booking_id = :bookingId";
            $clientStmt = $pdo->prepare($clientQuery);
            $clientStmt->bindParam(':bookingId', $bookingId);
            $clientStmt->execute();
            $approvedClientId = $clientStmt->fetchColumn();

            // Update other pending bookings for the freelancer to 'Rejected'
            $rejectQuery = "UPDATE booking SET booking_status = 'Rejected' WHERE freelancer_id = :freelancerId AND booking_status = 'Pending' AND booking_id != :bookingId";
            $rejectStmt = $pdo->prepare($rejectQuery);
            $rejectStmt->bindParam(':freelancerId', $freelancerId);
            $rejectStmt->bindParam(':bookingId', $bookingId);
            $rejectStmt->execute();

            // Notify the approved client
            $notificationData = [
                'userId' => $approvedClientId,
                'action' => 'Booking Approved',
                'message' => 'Your booking request has been accepted by the freelancer.'
            ];
            sendNotification($notificationData, 'emit-booking-approval-notification');

            // Notify all rejected clients
            $rejectedClientsQuery = "SELECT client_id FROM booking WHERE freelancer_id = :freelancerId AND booking_status = 'Rejected' AND booking_id != :bookingId";
            $rejectedClientsStmt = $pdo->prepare($rejectedClientsQuery);
            $rejectedClientsStmt->bindParam(':freelancerId', $freelancerId);
            $rejectedClientsStmt->bindParam(':bookingId', $bookingId);
            $rejectedClientsStmt->execute();

            while ($rejectedClientId = $rejectedClientsStmt->fetchColumn()) {
                $rejectNotificationData = [
                    'userId' => $rejectedClientId,
                    'action' => 'Booking Rejected',
                    'message' => 'Your booking request has been rejected as the freelancer has accepted another request.'
                ];
                sendNotification($rejectNotificationData, 'emit-booking-rejection-notification');
            }

            // Commit transaction
            $pdo->commit();

            echo json_encode(['success' => true, 'message' => 'Booking approved and other requests rejected.']);
        } else {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'error' => 'Failed to approve booking.']);
        }

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

} else {
    echo json_encode(['success' => false, 'error' => 'Booking ID not provided.']);
}

// Function to send notifications via CURL
function sendNotification($data, $endpoint) {
    $ch = curl_init('http://localhost:8080/' . $endpoint);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    if ($response === false) {
        error_log('Notification request failed: ' . curl_error($ch));
    }
    curl_close($ch);
}
?>
