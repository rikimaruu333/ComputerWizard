<?php
include 'userconnection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the ID from the request
    $data = json_decode(file_get_contents("php://input"));
    $eventId = $data->id;

    // Connect to the database (assuming you have a Connection class)
    $conn = new Connection();
    $pdo = $conn->openConnection();

    try {
        // Prepare and execute the delete statement
        $stmt = $pdo->prepare("DELETE FROM events WHERE event_id = :id");
        $stmt->bindParam(':id', $eventId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Event not found or already deleted.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
