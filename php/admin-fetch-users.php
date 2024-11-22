<?php
session_start();
include 'userconnection.php';

// Open connection
$connection = new Connection();
$con = $connection->openConnection();

// Query to fetch only 'Client' and 'Freelancer' users with status 1 or 3 and their restriction status
$query = "
    SELECT u.id, u.firstname, u.lastname, u.usertype, u.profile, u.status,
           CASE 
               WHEN u.status = 3 THEN 'restricted'  
               WHEN u.status = 1 THEN 'unrestricted'
           END AS restriction_status
    FROM users u
    WHERE u.usertype IN ('Client', 'Freelancer')  
    AND u.status IN (1, 3)  
    ORDER BY u.firstname ASC";  // Sort by first name

$stmt = $con->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON response
echo json_encode($users);
?>
