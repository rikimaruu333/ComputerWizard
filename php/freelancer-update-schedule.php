<?php
include 'userconnection.php';
session_start();

header('Content-Type: application/json'); // Ensure the response is JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $scheduleId = $_POST['schedule_id'];
    $day = $_POST['day'];
    $timeIn = $_POST['time_in'];
    $timeOut = $_POST['time_out'];

    $freelancerId = $_SESSION['USER']->id;

    if (!empty($day) && !empty($timeIn) && !empty($timeOut)) {
        $connection = new Connection();
        $con = $connection->openConnection();

        try {
            $stmt = $con->prepare("UPDATE schedules SET day = :day, time_in = :time_in, time_out = :time_out WHERE schedule_id = :schedule_id AND freelancer_id = :freelancer_id");
            $stmt->execute([
                'schedule_id' => $scheduleId,
                'day' => $day,
                'time_in' => $timeIn,
                'time_out' => $timeOut,
                'freelancer_id' => $freelancerId
            ]);

            // Return JSON success response
            echo json_encode([
                'success' => 'Schedule updated successfully!',
                'schedule_id' => $scheduleId,
                'day' => $day,
                'time_in' => $timeIn,
                'time_out' => $timeOut
            ]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }

        $connection->closeConnection();
    } else {
        echo json_encode(['error' => 'Please fill in all fields.']);
    }
}
?>
