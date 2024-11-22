<?php
include 'userconnection.php';
session_start();

// Ensure the response is JSON
header('Content-Type: application/json');

// Get service_id from GET request
$serviceId = intval($_GET['service_id']); 

try {
    $conn = new Connection();
    $db = $conn->openConnection();

    // Prepare and execute the statement to fetch services for the given service_id
    $stmt = $db->prepare("SELECT service_id, freelancer_id, service, service_rate FROM servicelisting WHERE service_id = :service_id");
    $stmt->execute(['service_id' => $serviceId]); 

    $serviceDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($serviceDetails) {
        // Fetch freelancer profile information
        $freelancerId = $serviceDetails['freelancer_id'];
        $freelancerStmt = $db->prepare("SELECT firstname, lastname, usertype, profile FROM users WHERE id = :freelancer_id");
        $freelancerStmt->execute(['freelancer_id' => $freelancerId]);
        $freelancerInfo = $freelancerStmt->fetch(PDO::FETCH_ASSOC);

        // Combine the service and freelancer information
        $response = [
            'service' => $serviceDetails,
            'freelancer' => $freelancerInfo
        ];

        echo json_encode($response); // Return both service and freelancer details as JSON
    } else {
        echo json_encode(['message' => 'No services found for this freelancer']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
