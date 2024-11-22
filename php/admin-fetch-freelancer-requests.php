<?php
session_start();
include 'userconnection.php';

$connection = new Connection();
$con = $connection->openConnection();

// Query to fetch freelancers with status 0 (pending registration)
$query = "SELECT * FROM users WHERE usertype = 'Freelancer' AND status = 0 ORDER BY id ASC";
$stmt = $con->prepare($query);
$stmt->execute();
$freelancers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Encode to JSON
$response = json_encode($freelancers);

// Send JSON response
header('Content-Type: application/json');
echo $response;
?>
