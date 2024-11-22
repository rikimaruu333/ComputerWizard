<?php
include 'userconnection.php';

session_start();

if (!isset($_SESSION['USER'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

$conn = new Connection();
$db = $conn->openConnection();

$booking_id = $_GET['booking_id'] ?? null;

if (!$booking_id) {
    echo json_encode(['success' => false, 'error' => 'Invalid booking ID']);
    exit;
}

try {
    $query = "SELECT b.booking_id, b.client_id, b.freelancer_id, b.job_type, b.payment_type, b.booking_status, 
                     b.client_ET_request, b.freelancer_ET_request, b.start_date, b.end_date, b.booking_date,
                     CONCAT(c.firstname, ' ', c.lastname) AS client_name, c.profile AS client_profile,
                     CONCAT(f.firstname, ' ', f.lastname) AS freelancer_name, f.profile AS freelancer_profile
              FROM booking AS b
              JOIN users AS c ON b.client_id = c.id
              JOIN users AS f ON b.freelancer_id = f.id
              WHERE b.booking_id = :booking_id";
              
    $stmt = $db->prepare($query);
    $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
    $stmt->execute();

    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($booking) {
        echo json_encode(['success' => true, 'booking' => $booking]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Booking not found']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
