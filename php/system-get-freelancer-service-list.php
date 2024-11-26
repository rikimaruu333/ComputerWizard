<?php
include 'userconnection.php';
session_start();

header('Content-Type: application/json');

if (!isset($_GET['freelancer_id'])) {
    echo json_encode(['error' => 'Freelancer ID is required']);
    exit();
}

$freelancerId = $_GET['freelancer_id'];
$clientId = $_SESSION['USER']->id;

try {
    $conn = new Connection();
    $db = $conn->openConnection();
    // Set timezone to Asia/Manila
    date_default_timezone_set('Asia/Manila');
    $currentDay = date('l'); // Current day
    $currentTime = date('H:i'); // Current time

    // Check if the freelancer has an approved ongoing booking
    $ongoingBookingStmt = $db->prepare("SELECT COUNT(*) AS booking_count FROM booking WHERE freelancer_id = :freelancer_id AND booking_status = 'Approved'");
    $ongoingBookingStmt->execute(['freelancer_id' => $freelancerId]);
    $ongoingBooking = $ongoingBookingStmt->fetch(PDO::FETCH_ASSOC);
    $hasOngoingBooking = $ongoingBooking['booking_count'] > 0;

    // Fetch all services for the given freelancer along with their availability
    $stmt = $db->prepare("
        SELECT s.*, 
               CASE WHEN EXISTS (
                    SELECT 1 
                    FROM schedules sch 
                    WHERE sch.freelancer_id = s.freelancer_id 
                    AND sch.day = :currentDay 
                    AND sch.time_in <= :currentTime 
                    AND sch.time_out >= :currentTime
                ) THEN 1 ELSE 0 END AS is_available
        FROM servicelisting s
        WHERE s.freelancer_id = :freelancer_id
    ");
    $stmt->execute([
        'freelancer_id' => $freelancerId,
        'currentDay' => $currentDay,
        'currentTime' => $currentTime
    ]);
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];

    foreach ($services as $service) {
        $serviceId = $service['service_id'];

        // Check if the current client has already booked this service
        $clientBookingStmt = $db->prepare("SELECT COUNT(*) AS client_booking_count FROM booking WHERE service_id = :service_id AND client_id = :client_id AND booking_status = 'Approved'");
        $clientBookingStmt->execute(['service_id' => $serviceId, 'client_id' => $clientId]);
        $clientBooking = $clientBookingStmt->fetch(PDO::FETCH_ASSOC);

        $service['hasOngoingBooking'] = $hasOngoingBooking;
        $service['alreadyBookedByClient'] = $clientBooking['client_booking_count'] > 0;

        $result[] = $service;
    }

    echo json_encode($result);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
