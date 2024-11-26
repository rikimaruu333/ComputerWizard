<?php
session_start();
include 'userconnection.php';

// Open connection
$connection = new Connection();
$con = $connection->openConnection();

// Retrieve search and filter values
$searchTerm = isset($_POST['searchTerm']) ? trim($_POST['searchTerm']) : '';
$recommendation = isset($_POST['recommendation']) ? trim($_POST['recommendation']) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';
$gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');
$currentDay = date('l'); // e.g., 'Monday'
$currentTime = date('H:i'); // e.g., '14:30'

// Base query
$query = "
    SELECT u.id, u.firstname, u.lastname, u.usertype, u.profile,
           AVG(r.rating) AS rating,
           COUNT(CASE WHEN r.recommendation = 'Recommended' THEN 1 END) AS recommendation_count,
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
    LEFT JOIN reviews r ON u.id = r.freelancer_id
    WHERE u.usertype = 'Freelancer' AND u.status = 1";

// Add filters
if (!empty($address)) {
    $query .= " AND u.address = :address";
}
if (!empty($gender)) {
    $query .= " AND u.gender = :gender";
}
if (!empty($searchTerm)) {
    $query .= " AND (u.firstname LIKE :searchTerm OR u.lastname LIKE :searchTerm)";
}

// Grouping and sorting
$query .= " GROUP BY u.id";

// Apply sorting based on recommendation filter
if ($recommendation === 'Highest') {
    $query .= " ORDER BY recommendation_count DESC, u.firstname ASC";
} elseif ($recommendation === 'Lowest') {
    $query .= " ORDER BY recommendation_count ASC, u.firstname ASC";
} else {
    $query .= " ORDER BY u.firstname ASC"; // Default sorting
}

// Prepare and execute query
$stmt = $con->prepare($query);

// Bind parameters
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
