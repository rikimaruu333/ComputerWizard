<?php
session_start();
include 'userconnection.php';


$user_id = $_SESSION['USER']->id;

try {
    $conn = new Connection();
    $pdo = $conn->openConnection();

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT notification_title, notification_content, notification_datetime 
                           FROM notifications WHERE user_id = :user_id ORDER BY notification_datetime DESC LIMIT 10");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // Fetch all notifications
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the notifications in JSON format
    echo json_encode($notifications);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error fetching notifications"]);
}
?>
