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

$freelancer_id = $_SESSION['USER']->id;
$title = $data['title'] ?? '';
$description = $data['description'] ?? '';
$startDate = $data['startDate'] ?? ''; // Starting date (YYYY-MM-DD)
$startTime = $data['startTime'] ?? ''; // Starting time (HH:mm)
$endDate = $data['endDate'] ?? '';     // Ending date (YYYY-MM-DD)
$endTime = $data['endTime'] ?? '';     // Ending time (HH:mm)

// Validate the data
if ($title && $startDate && $startTime && $endDate && $endTime) {
    // Combine date and time for start and end
    $startDateTime = $startDate . ' ' . $startTime; // Combine for database
    $endDateTime = $endDate . ' ' . $endTime;       // Combine for database

    // Prepare the SQL statement
    $stmt = $db->prepare("INSERT INTO events (freelancer_id, title, description, start_time, end_time, start_date, end_date) VALUES (:freelancer_id, :title, :description, :startTime, :endTime, :startDate, :endDate)");

    // Bind parameters
    $stmt->bindParam(':freelancer_id', $freelancer_id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':startTime', $startDateTime); // Use combined start date and time
    $stmt->bindParam(':endTime', $endDateTime);     // Use combined end date and time
    $stmt->bindParam(':startDate', $startDate);     // Store start date
    $stmt->bindParam(':endDate', $endDate);         // Store end date

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
}
