<?php
include 'userconnection.php';
session_start();

header('Content-Type: application/json'); // Ensure the response is JSON

// Check if freelancer_id is provided
if (!isset($_GET['freelancer_id'])) {
    echo json_encode(['error' => 'Freelancer ID is required']);
    exit();
}

$freelancerId = $_GET['freelancer_id']; // Get freelancer_id from GET request

try {
    $conn = new Connection();
    $db = $conn->openConnection();

    $stmt = $db->prepare("SELECT * FROM schedules WHERE freelancer_id = :freelancer_id");
    $stmt->execute(['freelancer_id' => $freelancerId]); // Use the freelancer_id from GET request

    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return schedules as JSON
    echo json_encode($schedules);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
