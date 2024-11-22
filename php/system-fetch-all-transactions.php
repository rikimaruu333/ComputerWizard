<?php
include 'userconnection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['USER'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

$conn = new Connection();
$db = $conn->openConnection();

try {
    // Prepare SQL query to fetch all approved bookings
    $query = "SELECT b.booking_id, b.client_id, b.freelancer_id, b.job_type, b.payment_type, b.booking_date, b.booking_status, 
                     b.client_ET_request, b.freelancer_ET_request,
                     CONCAT(c.firstname, ' ', c.lastname) AS client_name, c.profile AS client_profile,
                     CONCAT(f.firstname, ' ', f.lastname) AS freelancer_name, f.profile AS freelancer_profile
              FROM booking AS b
              JOIN users AS c ON b.client_id = c.id
              JOIN users AS f ON b.freelancer_id = f.id
              WHERE b.booking_status = 'Approved' OR b.booking_status = 'Completed'";
              
    $stmt = $db->prepare($query);
    $stmt->execute();

    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Include session user ID in the response for frontend usage if needed
    echo json_encode(['success' => true, 'bookings' => $bookings, 'user_id' => $_SESSION['USER']->id, 'user_usertype' => $_SESSION['USER']->usertype]);

} catch (PDOException $e) {
    // Handle SQL errors
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
