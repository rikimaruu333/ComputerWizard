<?php
include 'userconnection.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $serviceId = $_POST['service_id'];
    $freelancerId = $_SESSION['USER']->id;

    if (!empty($serviceId)) {
        $connection = new Connection();
        $con = $connection->openConnection();

        try {
            $stmt = $con->prepare("DELETE FROM servicelisting WHERE service_id = :service_id AND freelancer_id = :freelancer_id");
            $stmt->bindParam(':service_id', $serviceId);
            $stmt->bindParam(':freelancer_id', $freelancerId);

            if ($stmt->execute()) {
                echo json_encode(['success' => 'Service deleted successfully!']);
            } else {
                echo json_encode(['error' => 'Failed to delete the service.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        } finally {
            $connection->closeConnection();
        }
    } else {
        echo json_encode(['error' => 'Invalid service ID.']);
    }
}
?>
