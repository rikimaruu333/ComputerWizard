<?php
session_start();
include 'userconnection.php';

$post_client_id = $_SESSION['USER']->id;
$post_description = $_POST['post_description'] ?? '';

try {
    // Open connection
    $connection = new Connection();
    $con = $connection->openConnection();

    // Start a transaction
    $con->beginTransaction();

    // Prepare statement to insert the post
    $stmt = $con->prepare("INSERT INTO jobposts (post_client_id, post_description, post_date) VALUES (:post_client_id, :post_description, NOW())");
    $stmt->bindParam(':post_client_id', $post_client_id);
    $stmt->bindParam(':post_description', $post_description);
    $stmt->execute();

    // Get the last inserted post ID
    $post_id = $con->lastInsertId();

    // Directory where the files will be stored
    $uploadDirectory = '../images/';

    // Handle multiple file uploads
    $fileCount = count($_FILES['pictures']['name']);
    for ($i = 0; $i < $fileCount; $i++) {
        if ($_FILES['pictures']['error'][$i] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['pictures']['tmp_name'][$i];
            $fileName = $_FILES['pictures']['name'][$i];
            $picture = $uploadDirectory . uniqid('post_') . '_' . $fileName;

            // Move the uploaded file to the desired location
            if (move_uploaded_file($fileTmpPath, $picture)) {
                // Prepare statement to insert image path into post_images table
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

    // Commit the transaction
    $con->commit();
    echo json_encode(['status' => 'success', 'message' => 'Post created successfully']);

} catch (Exception $e) {
    // Rollback transaction in case of error
    if ($con->inTransaction()) {
        $con->rollBack();
    }
    echo json_encode(['status' => 'error', 'message' => 'Error creating post: ' . $e->getMessage()]);
}
?>
