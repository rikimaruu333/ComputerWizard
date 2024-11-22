<?php
include 'userconnection.php';
session_start();

header('Content-Type: application/json'); // Ensure the response is JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $serviceName = $_POST['serviceName'];
    $serviceRate = $_POST['serviceRate'];
    $freelancerId = $_SESSION['USER']->id;

    if (!empty($serviceName) && !empty($serviceRate)) {
        $connection = new Connection();
        $con = $connection->openConnection();

        try {
            $stmt = $con->prepare("INSERT INTO servicelisting (freelancer_id, service, service_rate) VALUES (:freelancer_id, :service, :service_rate)");
            $stmt->bindParam(':freelancer_id', $freelancerId);
            $stmt->bindParam(':service', $serviceName);
            $stmt->bindParam(':service_rate', $serviceRate);
            $stmt->execute();

            // Get the new service ID
            $newServiceId = $con->lastInsertId();

            // Return JSON success response
            echo json_encode([
                'success' => 'Service added successfully!',
                'service_id' => $newServiceId,
                'service' => $serviceName,
                'service_rate' => $serviceRate
            ]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }

        $connection->closeConnection();
    } else {
        echo json_encode(['error' => 'Please fill in all fields.']);
    }
}
