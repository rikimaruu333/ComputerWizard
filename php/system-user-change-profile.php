<?php
session_start();
include 'userconnection.php'; 

$newConnection = new Connection();
$pdo = $newConnection->getConnection(); // Get the PDO connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profilePicture'])) {
    $userId = $_SESSION['USER']->id; 
    $file = $_FILES['profilePicture'];
    $targetDir = "../profile/"; 
    $targetFile = $targetDir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validate the file type and size
    if (in_array($imageFileType, ['jpg', 'png', 'jpeg']) && $file['size'] < 20000000) {
        // Move the uploaded file to the target directory
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            // Prepare and execute the SQL statement to update the profile picture
            $stmt = $pdo->prepare("UPDATE users SET profile = ? WHERE id = ?");
            $stmt->execute([$targetFile, $userId]);

            // Return success response with new image path
            echo json_encode([
                'status' => 'success', 
                'message' => 'Profile picture updated successfully', 
                'data' => $targetFile
            ]);
        } else {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Error moving uploaded file'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Invalid file type or size too large'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Invalid request'
    ]);
}
