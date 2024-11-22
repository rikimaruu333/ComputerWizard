<?php
include 'userconnection.php';
session_start();

header('Content-Type: application/json'); // Ensure the response is JSON

try {
    $conn = new Connection();
    $db = $conn->openConnection();

    // Prepare SQL statement to fetch schedule for the logged-in freelancer
    $stmt = $db->prepare("SELECT * FROM schedules WHERE freelancer_id = :freelancer_id");
    $stmt->execute(['freelancer_id' => $_SESSION['USER']->id]);

    // Fetch all schedules as an associative array
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Define the order for days of the week
    $dayOrder = [
        'Monday' => 1,
        'Tuesday' => 2,
        'Wednesday' => 3,
        'Thursday' => 4,
        'Friday' => 5,
        'Saturday' => 6,
        'Sunday' => 7
    ];

    // Sort schedules by day of the week
    usort($schedules, function ($a, $b) use ($dayOrder) {
        return $dayOrder[$a['day']] <=> $dayOrder[$b['day']];
    });

    // Return schedules as JSON
    echo json_encode($schedules);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
