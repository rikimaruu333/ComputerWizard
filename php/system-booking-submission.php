<?php
include 'userconnection.php';
session_start();
date_default_timezone_set('Asia/Manila');

if (!isset($_SESSION['USER'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

$conn = new Connection();
$db = $conn->openConnection();

$client_id = $_SESSION['USER']->id;
$freelancer_id = $_POST['freelancer_id'];
$service_id = $_POST['service_id'];
$job_type = $_POST['job_type'];
$payment_type = $_POST['payment_type'];
$booking_status = 'Pending';

// Function to emit notifications
function emitNotification($notifications) {
    $url = 'http://localhost:8080/emit-booking-notification';
    $data = json_encode(['notifications' => $notifications]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

try {
    $query = "INSERT INTO booking (client_id, freelancer_id, service_id, job_type, payment_type, booking_status, booking_date)
              VALUES (:client_id, :freelancer_id, :service_id, :job_type, :payment_type, :booking_status, NOW())";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
    $stmt->bindParam(':freelancer_id', $freelancer_id, PDO::PARAM_INT);
    $stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);
    $stmt->bindParam(':job_type', $job_type, PDO::PARAM_STR);
    $stmt->bindParam(':payment_type', $payment_type, PDO::PARAM_STR);
    $stmt->bindParam(':booking_status', $booking_status, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $notifications = [
            [
                'userId' => $freelancer_id,
                'action' => 'Booking Request',
                'message' => 'You received a new booking request. Check your booking request list.',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'userId' => $client_id,
                'action' => 'Booking Request',
                'message' => 'Your booking request has been sent to the freelancer.',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        $response = emitNotification($notifications);

        if ($response === false) {
            echo json_encode(['success' => false, 'error' => 'Failed to send notification.']);
            exit;
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to create booking.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}

