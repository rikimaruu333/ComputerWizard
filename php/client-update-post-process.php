<?php
session_start();
include 'userconnection.php';

$post_id = $_POST['post_id'] ?? null;
$post_description = $_POST['post_description'] ?? '';
$post_client_id = $_SESSION['USER']->id; // Ensure the user is authorized to update this post

try {
    // Open connection
    $connection = new Connection();
    $con = $connection->openConnection();

    // Start a transaction
    $con->beginTransaction();

    // Update the post description
    $stmt = $con->prepare("UPDATE jobposts SET post_description = :post_description WHERE post_id = :post_id AND post_client_id = :post_client_id");
    $stmt->bindParam(':post_description', $post_description);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':post_client_id', $post_client_id);
    $stmt->execute();

    // Handle image removal if any images were marked for removal
    if (isset($_POST['removeImages']) && is_array($_POST['removeImages'])) {
        foreach ($_POST['removeImages'] as $image) {
            // Delete the image file from the server
            if (file_exists($image)) {
                unlink($image); // Remove the file
            }

            // Remove the image from the database
            $deleteStmt = $con->prepare("DELETE FROM jobposts_images WHERE image_path = :image AND post_id = :post_id");
            $deleteStmt->bindParam(':image', $image);
            $deleteStmt->bindParam(':post_id', $post_id);
            $deleteStmt->execute();
        }
    }

    // Handle multiple file uploads if new images are uploaded
    $uploadDirectory = '../images/';
    
    // Check if 'pictures' key exists in $_FILES and it is an array
    if (isset($_FILES['pictures']) && is_array($_FILES['pictures']['name'])) {
        $fileCount = count($_FILES['pictures']['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES['pictures']['error'][$i] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['pictures']['tmp_name'][$i];
                $fileName = $_FILES['pictures']['name'][$i];
                $picture = $uploadDirectory . uniqid('post_') . '_' . $fileName;

                // Move the uploaded file to the desired location
                if (move_uploaded_file($fileTmpPath, $picture)) {
                    // Insert new image path into the jobposts_images table
                    $imageStmt = $con->prepare("INSERT INTO jobposts_images (post_id, image_path) VALUES (:post_id, :image_path)");
                    $imageStmt->bindParam(':post_id', $post_id);
                    $imageStmt->bindParam(':image_path', $picture);
                    $imageStmt->execute();
                } else {
                    // Rollback transaction if there's an error
                    $con->rollBack();
                    echo json_encode(['status' => 'error', 'message' => 'Error uploading picture. Please try again.']);
                    exit();
                }
            }
        }
    }

    // Commit the transaction
    $con->commit();
    echo json_encode(['status' => 'success', 'message' => 'Post updated successfully']);

} catch (Exception $e) {
    // Rollback transaction in case of error
    if ($con->inTransaction()) {
        $con->rollBack();
    }
    echo json_encode(['status' => 'error', 'message' => 'Error updating post: ' . $e->getMessage()]);
}
?>
