<?php
include 'userconnection.php';
session_start();

header('Content-Type: application/json'); // Ensure the response is JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $serviceId = $_POST['service_id'];
    $serviceCategoryName = $_POST['service_category'];
    $serviceName = $_POST['service'];
    $serviceRate = $_POST['service_rate'];

    $freelancerId = $_SESSION['USER']->id;

    if (!empty($serviceName) && !empty($serviceRate)) {
        $connection = new Connection();
        $con = $connection->openConnection();

        try {
            $stmt = $con->prepare("UPDATE servicelisting SET service = :service, service_category = :service_category, service_rate = :service_rate WHERE service_id = :service_id AND freelancer_id = :freelancer_id");
            $stmt->execute([
                'service_id' => $serviceId,
                'service_category' => $serviceCategoryName,
                'service' => $serviceName,
                'service_rate' => $serviceRate,
                'freelancer_id' => $freelancerId
            ]);

            // Return JSON success response
            echo json_encode([
                'success' => 'Service updated successfully!',
                'service_id' => $serviceId,
                'service_category' => $serviceCategoryName,
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
