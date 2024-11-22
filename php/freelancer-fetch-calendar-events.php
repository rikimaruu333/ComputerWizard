<?php
include 'userconnection.php';
session_start();

header('Content-Type: application/json'); // Set the content type to JSON

try {
    $conn = new Connection();
    $db = $conn->openConnection(); // Open connection using the Connection class

    // Get the freelancer ID from the session
    $freelancer_id = $_SESSION['USER']->id;

    // Prepare SQL query to fetch events for the current freelancer
    $stmt = $db->prepare("SELECT event_id AS id, title, description, 
        CONCAT(start_date, ' ', start_time) AS start, 
        CONCAT(end_date, ' ', end_time) AS end 
        FROM events WHERE freelancer_id = :freelancer_id");
    $stmt->bindParam(':freelancer_id', $freelancer_id, PDO::PARAM_INT); // Bind the freelancer ID parameter
    $stmt->execute();

    // Fetch events from the database
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return events as JSON for FullCalendar
    echo json_encode($events);

} catch (PDOException $e) {
    // Handle connection errors
    echo json_encode([
        'success' => false,
        'message' => 'Connection failed: ' . $e->getMessage()
    ]);
}
?>
