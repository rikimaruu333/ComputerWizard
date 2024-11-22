<?php
session_start();
require_once 'userconnection.php'; // Include your database connection class

// Create a new instance of the Connection class
$connection = new Connection();
$pdo = $connection->getConnection(); // Get the PDO instance

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['USER']) && isset($_SESSION['USER']->id)) {
        $userId = $_SESSION['USER']->id; 
        $defaultImage = '../images/user.jpg'; 

        // Perform the deletion
        $stmt = $pdo->prepare("UPDATE users SET profile = ? WHERE id = ?");
        if ($stmt->execute([$defaultImage, $userId])) {
            echo json_encode(['status' => 'success', 'message' => 'Profile picture deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error deleting profile picture']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
