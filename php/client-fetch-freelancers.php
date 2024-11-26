<?php
session_start();
include 'userconnection.php';

// Open connection
$connection = new Connection();
$con = $connection->openConnection();

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');
// Get the current day and time
$currentDay = date('l'); // e.g., 'Monday'
$currentTime = date('H:i'); // e.g., '14:30' (24-hour format)

// Query to fetch all freelancers and check their availability in the schedules table
$query = "
    SELECT u.id, u.firstname, u.lastname, u.usertype, u.profile, 
           CASE WHEN EXISTS (
                SELECT 1 
                FROM schedules s 
                WHERE s.freelancer_id = u.id 
                AND s.day = :currentDay 
                AND s.time_in <= :currentTime 
                AND s.time_out >= :currentTime
            ) THEN 1 ELSE 0 END AS is_available,
           CASE WHEN EXISTS (
                SELECT 1 
                FROM booking b 
                WHERE b.freelancer_id = u.id 
                AND b.booking_status = 'Approved'
            ) THEN 1 ELSE 0 END AS has_booking
    FROM users u
    WHERE u.usertype = 'Freelancer' 
    AND u.status = 1";

$stmt = $con->prepare($query);
$stmt->bindParam(':currentDay', $currentDay, PDO::PARAM_STR);
$stmt->bindParam(':currentTime', $currentTime, PDO::PARAM_STR);
$stmt->execute();
$freelancers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON response
echo json_encode($freelancers);
?>
