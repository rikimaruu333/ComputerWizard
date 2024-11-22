<?php
include 'userconnection.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $scheduleId = $_POST['schedule_id'];
    $freelancerId = $_SESSION['USER']->id;

    if (!empty($scheduleId)) {
        $connection = new Connection();
        $con = $connection->openConnection();

        try {
            $stmt = $con->prepare("DELETE FROM schedules WHERE schedule_id = :schedule_id AND freelancer_id = :freelancer_id");
            $stmt->bindParam(':schedule_id', $scheduleId);
            $stmt->bindParam(':freelancer_id', $freelancerId);

            if ($stmt->execute()) {
                echo json_encode(['success' => 'Schedule deleted successfully!']);
            } else {
                echo json_encode(['error' => 'Failed to delete the schedule.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        } finally {
            $connection->closeConnection();
        }
    } else {
        echo json_encode(['error' => 'Invalid schedule ID.']);
    }
}
?>
