<?php
session_start();
include 'userconnection.php';
date_default_timezone_set('Asia/Manila'); // Set timezone

$connection = new Connection();
$con = $connection->openConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $freelancerId = $_POST['freelancer_id'];
    $status = $_POST['status'];

    if ($freelancerId && ($status == 1 || $status == 2)) {
        try {
            // Use PHP to get the current date and time
            $currentDate = date('Y-m-d H:i:s');

            // Update the status and date in the database
            $query = "UPDATE users SET status = :status, date = :date WHERE id = :freelancer_id";
            $stmt = $con->prepare($query);
            $stmt->execute([
                ':status' => $status,
                ':date' => $currentDate,
                ':freelancer_id' => $freelancerId,
            ]);

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Invalid request parameters.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
