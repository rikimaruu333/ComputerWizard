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

    // Check if the freelancer has an approved ongoing booking
    $ongoingBookingStmt = $db->prepare("SELECT COUNT(*) AS booking_count FROM booking WHERE freelancer_id = :freelancer_id AND booking_status = 'Approved'");
    $ongoingBookingStmt->execute(['freelancer_id' => $freelancerId]);
    $ongoingBooking = $ongoingBookingStmt->fetch(PDO::FETCH_ASSOC);
    $hasOngoingBooking = $ongoingBooking['booking_count'] > 0;

    // Fetch all services for the given freelancer
    $stmt = $db->prepare("SELECT * FROM servicelisting WHERE freelancer_id = :freelancer_id");
    $stmt->execute(['freelancer_id' => $freelancerId]);
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
