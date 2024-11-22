<?php
include 'userconnection.php';
session_start();

$conn = new Connection();
$db = $conn->openConnection();

// Get the event_id from the POST request
$eventId = $_POST['event_id'] ?? null;
$userId = $_SESSION['USER']->id; // Get logged in user ID for security

// Check if event_id is provided
if (!$eventId) {
    echo json_encode(['success' => false, 'error' => 'event_id missing']);
    exit;
}

// Update the notification for the specific event_id and user_id
$updateQuery = "UPDATE notifications SET is_read = 1 WHERE event_id = :event_id AND user_id = :user_id";
$updateStmt = $db->prepare($updateQuery);
$updateStmt->bindParam(':event_id', $eventId);
$updateStmt->bindParam(':user_id', $userId);
$updateStmt->execute();

echo json_encode(['success' => true]);
?>
