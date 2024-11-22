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

// Get user ID and user type (client or freelancer) from session
$user_id = $_SESSION['USER']->id;
$user_usertype = $_SESSION['USER']->usertype;

try {
    // Prepare SQL query to fetch bookings where the user is either client or freelancer
    $query = "SELECT b.booking_id, b.client_id, b.freelancer_id, b.job_type, b.payment_type, b.booking_date, b.client_ET_request, b.freelancer_ET_request,
                     CONCAT(c.firstname, ' ', c.lastname) AS client_name, c.profile AS client_profile,
                     CONCAT(f.firstname, ' ', f.lastname) AS freelancer_name, f.profile AS freelancer_profile
              FROM booking AS b
              JOIN users AS c ON b.client_id = c.id
              JOIN users AS f ON b.freelancer_id = f.id
              WHERE b.booking_status = 'Approved'
                AND ((b.client_id = :user_id AND c.usertype = :user_usertype) 
                     OR (b.freelancer_id = :user_id AND f.usertype = :user_usertype))";
              
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_usertype', $user_usertype, PDO::PARAM_STR);
    $stmt->execute();

    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Include session user ID in the response
    echo json_encode(['success' => true, 'bookings' => $bookings, 'user_id' => $user_id, 'user_usertype' => $user_usertype]);

} catch (PDOException $e) {
    // Handle SQL errors
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
