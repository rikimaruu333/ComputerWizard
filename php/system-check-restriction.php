<?php
session_start();
include 'userconnection.php';

$conn = new Connection();
$pdo = $conn->openConnection();

// Get the logged-in user's ID (assuming it's stored in the session)
$userId = $_SESSION['USER']->id; // Adjust this according to your session variable

// Check if the user is restricted
$query = "SELECT status FROM users WHERE id = :userId";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':userId', $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Check if the user is restricted (status = 3)
    if ($user['status'] === '3') {
        // Emit restriction event to the Socket.IO server
        $ch = curl_init('http://localhost:8080/emit-restriction'); // Adjust URL as needed
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['userId' => $userId, 'isRestricted' => true]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        
        // Return response to the client
        echo json_encode(['isRestricted' => true]);
    } else {
        echo json_encode(['isRestricted' => false]);
    }
} else {
    // Handle the case where the user is not found
    http_response_code(404); // Not Found
    echo json_encode(['error' => 'User not found.']);
}
?>
