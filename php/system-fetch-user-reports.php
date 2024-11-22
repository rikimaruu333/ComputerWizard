<?php
session_start();
include 'userconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['userId'];

    $conn = new Connection();
    $pdo = $conn->openConnection();

    $stmt = $pdo->prepare("SELECT * FROM reports WHERE reported_user_id = :userId ORDER BY report_date DESC");
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the reports as JSON
    echo json_encode($reports);
}
?>
