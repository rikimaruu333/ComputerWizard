<?php
include 'userconnection.php';
session_start();

$conn = new Connection();
$db = $conn->openConnection();

$response = ['success' => false];

if (isset($_GET['booking_id']) && isset($_SESSION['USER']->id)) {
    $bookingId = $_GET['booking_id'];
    $userId = $_SESSION['USER']->id;
    $userType = $_SESSION['USER']->usertype;

    try {
        // Prepare SQL to fetch booking details with service information from servicelisting
        $query = "
            SELECT 
                b.booking_id,
                sl.service AS service,
                sl.service_rate,
                b.job_type,
                b.payment_type,
                b.booking_date,
                b.client_id,
                b.freelancer_id,
                b.booking_status,
                b.start_date,
                b.end_date,
                CONCAT(c.firstname, ' ', c.lastname) AS client_name,
                c.profile AS client_profile,
                CONCAT(f.firstname, ' ', f.lastname) AS freelancer_name,
                f.profile AS freelancer_profile
            FROM booking b
            JOIN users c ON b.client_id = c.id
            JOIN users f ON b.freelancer_id = f.id
            JOIN servicelisting sl ON b.service_id = sl.service_id
            WHERE b.booking_id = :booking_id
        ";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);
        $stmt->execute();

        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($booking) {
            $response['success'] = true;
            $response['booking'] = $booking;
            $response['is_sender'] = ($userId == $booking['client_id']);
            $response['is_receiver'] = ($userId == $booking['freelancer_id']);
            $response['user_type'] = $response['is_sender'] ? 'Client' : 'Freelancer';
            $response['user_usertype'] = $userType;
        } else {
            $response['error'] = "Booking details not found.";
        }
    } catch (PDOException $e) {
        $response['error'] = "Database error: " . $e->getMessage();
    }
} else {
    $response['error'] = "Invalid request.";
}

header('Content-Type: application/json');
echo json_encode($response);
