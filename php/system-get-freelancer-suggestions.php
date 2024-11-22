<?php
session_start();
include 'userconnection.php';

$client_id = $_SESSION['USER']->id;

if (!$client_id) {
    echo json_encode(['status' => 'error', 'message' => 'Client ID is required.']);
    exit();
}

try {
    $connection = new Connection();
    $con = $connection->openConnection();

    $query = isset($_GET['query']) ? '%' . $_GET['query'] . '%' : '%';

    $stmt = $con->prepare("
        SELECT CONCAT(u.firstname, ' ', u.lastname) AS name, u.id, u.profile
        FROM booking b
        JOIN users u ON b.freelancer_id = u.id
        WHERE b.client_id = :client_id
          AND (b.booking_status = 'Approved' OR b.booking_status = 'Completed')
          AND (u.firstname LIKE :query OR u.lastname LIKE :query)
    ");
    $stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
    $stmt->bindParam(':query', $query, PDO::PARAM_STR);
    $stmt->execute();

    $freelancers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'freelancers' => $freelancers]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error fetching freelancers: ' . $e->getMessage()]);
}
?>
