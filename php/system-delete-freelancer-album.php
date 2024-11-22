<?php
include 'userconnection.php';

session_start();

$freelancer_id = isset($_POST['freelancer_id']) ? $_POST['freelancer_id'] : $_SESSION['USER']->id;

// Get the album ID from the request
if (isset($_POST['album_id']) && isset($freelancer_id)) {
    $album_id = $_POST['album_id'];

    $newconnection = new Connection();
    $pdo = $newconnection->getConnection();

    try {
        // Prepare the deletion statement
        $stmt = $pdo->prepare("DELETE FROM album WHERE album_id = :album_id AND freelancer_id = :freelancer_id");
        $stmt->execute([':album_id' => $album_id, ':freelancer_id' => $freelancer_id]);

        // Check if any row was deleted
        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Image deleted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Image not found or already deleted.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting image: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Album ID and Freelancer ID are required.']);
}
?>
