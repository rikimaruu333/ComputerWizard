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

// Get the logged-in user's ID
$userId = $_SESSION['USER']->id;

try {
    // Prepare SQL query to fetch approved or completed bookings where the user is either a client or freelancer
    $query = "
        SELECT 
            b.booking_id, b.client_id, b.freelancer_id, b.job_type, b.payment_type, 
            b.booking_date, b.booking_status, b.client_ET_request, b.freelancer_ET_request,
            CONCAT(c.firstname, ' ', c.lastname) AS client_name, c.profile AS client_profile,
            CONCAT(f.firstname, ' ', f.lastname) AS freelancer_name, f.profile AS freelancer_profile
        FROM 
            booking AS b
        JOIN 
            users AS c ON b.client_id = c.id
        JOIN 
            users AS f ON b.freelancer_id = f.id
        WHERE 
            (b.client_id = :user_id OR b.freelancer_id = :user_id)
            AND b.booking_status = 'Completed'
    ";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Include session user ID and user type in the response for frontend usage if needed
    echo json_encode(['success' => true, 'bookings' => $bookings, 'user_id' => $userId, 'user_usertype' => $_SESSION['USER']->usertype]);

} catch (PDOException $e) {
    // Handle SQL errors
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
