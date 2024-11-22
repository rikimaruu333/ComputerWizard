<?php
include 'userconnection.php';
session_start();

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Database connection
$conn = new Connection();
$db = $conn->openConnection();

// Read the incoming data
$data = json_decode(file_get_contents('php://input'), true);

$eventId = $data['id'] ?? '';
$title = $data['title'] ?? '';
$description = $data['description'] ?? '';
$startDate = $data['startDate'] ?? ''; // Starting date (YYYY-MM-DD)
$startTime = $data['startTime'] ?? ''; // Starting time (HH:mm)
$endDate = $data['endDate'] ?? '';     // Ending date (YYYY-MM-DD)
$endTime = $data['endTime'] ?? '';     // Ending time (HH:mm)

// Validate the data
if ($eventId && $title && $startDate && $startTime && $endDate && $endTime) {
    // Prepare the SQL statement
    $stmt = $db->prepare("UPDATE events SET title = :title, description = :description, start_date = :startDate, start_time = :startTime, end_date = :endDate, end_time = :endTime WHERE event_id = :eventId");

    // Bind parameters
    $stmt->bindParam(':eventId', $eventId);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':startDate', $startDate); // Use start date separately
    $stmt->bindParam(':startTime', $startTime); // Use start time separately
    $stmt->bindParam(':endDate', $endDate);     // Use end date separately
    $stmt->bindParam(':endTime', $endTime);     // Use end time separately

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
}
