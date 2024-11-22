<?php
session_start();
include 'userconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['userId'];

    $conn = new Connection();
    $pdo = $conn->openConnection();

    // Fetch the unrestrict_date for the user
    $stmt = $pdo->prepare("SELECT unrestrict_date FROM restrictions WHERE restricted_user_id = :userId AND date_unrestricted IS NULL");
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    
    // Fetch the restriction record
    $restriction = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the unrestrict date as JSON
    if ($restriction) {
        echo json_encode([$restriction]); // Return as an array to match the JS code
    } else {
        echo json_encode([]); // Return an empty array if no records found
    }
}
?>
