<?php
include 'userconnection.php';
session_start();

header('Content-Type: application/json'); // Ensure the response is JSON

try {
    $conn = new Connection();
    $db = $conn->openConnection();

    $stmt = $db->prepare("SELECT * FROM servicelisting WHERE freelancer_id = :freelancer_id");
    $stmt->execute(['freelancer_id' => $_SESSION['USER']->id]);

    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return services as JSON
    echo json_encode($services);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
