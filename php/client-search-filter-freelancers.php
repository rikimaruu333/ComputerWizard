<?php
session_start();
include 'userconnection.php';

// Open connection
$connection = new Connection();
$con = $connection->openConnection();

// Retrieve search and filter values
$searchTerm = isset($_POST['searchTerm']) ? trim($_POST['searchTerm']) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';
$gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');
// Get the current day and time
$currentDay = date('l'); // e.g., 'Monday'
$currentTime = date('H:i'); // e.g., '14:30' (24-hour format)

// Prepare the base query with filtering conditions
$query = "
    SELECT u.id, u.firstname, u.lastname, u.usertype, u.profile, 
           CASE WHEN EXISTS (
                SELECT 1 
                FROM schedules s 
                WHERE s.freelancer_id = u.id 
                AND s.day = :currentDay 
                AND s.time_in <= :currentTime 
                AND s.time_out >= :currentTime
            ) THEN 1 ELSE 0 END AS is_available
    FROM users u
    WHERE u.usertype = 'Freelancer' 
    AND u.status = 1";

// Add conditions for filters if they are selected
if (!empty($address)) {
    $query .= " AND address = :address";
}
if (!empty($gender)) {
    $query .= " AND gender = :gender";
}

// Add search condition if a search term is provided
if (!empty($searchTerm)) {
    $query .= " AND (firstname LIKE :searchTerm OR lastname LIKE :searchTerm)";
}

$query .= " ORDER BY u.firstname ASC"; // Order alphabetically

$stmt = $con->prepare($query);

// Bind parameters based on the selected filters and search term
$stmt->bindParam(':currentDay', $currentDay, PDO::PARAM_STR);
$stmt->bindParam(':currentTime', $currentTime, PDO::PARAM_STR);
if (!empty($address)) {
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
}
if (!empty($gender)) {
    $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
}
if (!empty($searchTerm)) {
    $stmt->bindValue(':searchTerm', "%$searchTerm%", PDO::PARAM_STR);
}

$stmt->execute();
$freelancers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON response
echo json_encode($freelancers);

// Close connection
$con = null;
?>
