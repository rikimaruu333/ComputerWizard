<?php
header('Content-Type: application/json');

require 'userconnection.php'; 

$newconnection = new Connection();
$con = $newconnection->openConnection(); // Open connection

$data = json_decode(file_get_contents("php://input"));
$email = $data->email;

$query = "SELECT COUNT(*) as count FROM users WHERE email = ?";
$stmt = $con->prepare($query);
$stmt->bindParam(1, $email); // Make sure to bind correctly
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC); // Use fetch for PDO

$response = [
    'exists' => $row['count'] > 0
];

echo json_encode($response);
