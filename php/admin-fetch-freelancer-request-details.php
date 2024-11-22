<?php
session_start();
include 'userconnection.php';

$connection = new Connection();
$con = $connection->openConnection();

if (isset($_GET['freelancer_id'])) {
    $freelancer_id = $_GET['freelancer_id'];

    // Query to fetch freelancer details by ID
    $query = "SELECT * FROM users WHERE id = :freelancer_id";
    $statement = $con->prepare($query);
    $statement->bindParam(':freelancer_id', $freelancer_id);
    $statement->execute();
    $freelancer = $statement->fetch(PDO::FETCH_ASSOC);

    if ($freelancer) {
        // Return the freelancer data in JSON format
        echo json_encode($freelancer);
    } else {
        echo json_encode(['error' => 'Freelancer not found']);
    }
} else {
    echo json_encode(['error' => 'No freelancer ID provided']);
}
?>
