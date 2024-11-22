<?php
include 'userconnection.php';
session_start();

header('Content-Type: application/json'); // Ensure the response is JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $day = $_POST['day'];
    $timeIn = $_POST['time_in'];
    $timeOut = $_POST['time_out'];
    $freelancerId = $_SESSION['USER']->id;

    if (!empty($day) && !empty($timeIn) && !empty($timeOut)) {
        $connection = new Connection();
        $con = $connection->openConnection();

        try {
            // Check if the same day already exists for the freelancer
            $stmt = $con->prepare("SELECT * FROM schedules WHERE freelancer_id = :freelancer_id AND day = :day");
            $stmt->bindParam(':freelancer_id', $freelancerId);
            $stmt->bindParam(':day', $day);
            $stmt->execute();
            $existingSchedule = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingSchedule) {
                echo json_encode(['error' => 'A schedule for this day already exists.']);
            } else {
                // Proceed to insert the new schedule
                $stmt = $con->prepare("INSERT INTO schedules (freelancer_id, day, time_in, time_out) VALUES (:freelancer_id, :day, :time_in, :time_out)");
                $stmt->bindParam(':freelancer_id', $freelancerId);
                $stmt->bindParam(':day', $day);
                $stmt->bindParam(':time_in', $timeIn);
                $stmt->bindParam(':time_out', $timeOut);
                $stmt->execute();

                // Get the new schedule ID
                $newScheduleId = $con->lastInsertId();

                // Return JSON success response
                echo json_encode([
                    'success' => 'Schedule added successfully!',
                    'schedule_id' => $newScheduleId,
                    'day' => $day,
                    'time_in' => $timeIn,
                    'time_out' => $timeOut
                ]);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }

        $connection->closeConnection();
    } else {
        echo json_encode(['error' => 'Please fill in all fields.']);
    }
}
?>
