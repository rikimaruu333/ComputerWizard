<?php
include 'userconnection.php';

session_start();
$freelancer_id = $_SESSION['USER']->id; // Assuming session holds the freelancer id

$newconnection = new Connection();
$pdo = $newconnection->getConnection();

try {
    // Fetch both album images and their IDs
    $stmt = $pdo->prepare("SELECT album_id, album_img FROM album WHERE freelancer_id = :freelancer_id");
    $stmt->execute([':freelancer_id' => $freelancer_id]);
    $albumImages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare an array to hold the image data
    $imageData = [];
    foreach ($albumImages as $image) {
        $imageData[] = [
            'album_id' => $image['album_id'],
            'album_img' => $image['album_img'],
        ];
    }

    echo json_encode(['status' => 'success', 'data' => $imageData]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error fetching images: ' . $e->getMessage()]);
}
?>
