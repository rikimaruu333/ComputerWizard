<?php
include 'userconnection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['freelancer_id']) && isset($_FILES['file'])) {
        $freelancer_id = $_POST['freelancer_id'];

        // Handle the uploaded file
        $file = $_FILES['file'];
        $uploadDir = '../album/'; // Directory to save uploaded images
        $fileName = basename($file['name']);
        $targetFilePath = $uploadDir . $fileName;

        // Check if the upload directory exists; if not, create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            $album_img = $targetFilePath; // Set the album_img to the path of the uploaded file

            try {
                $newconnection = new Connection();
                $pdo = $newconnection->getConnection();
                
                $stmt = $pdo->prepare("INSERT INTO album (freelancer_id, album_img) VALUES (:freelancer_id, :album_img)");
                $stmt->execute([
                    ':freelancer_id' => $freelancer_id,
                    ':album_img' => $album_img
                ]);

                // Return JSON response
                echo json_encode(['status' => 'success', 'message' => 'Image added to album successfully!']);
            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => 'Error inserting into database: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error uploading the file.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing required data.']);
    }
}

?>
