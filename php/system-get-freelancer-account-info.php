<?php
include 'userconnection.php';
session_start();

// Check if freelancer_id is set
if (isset($_GET['freelancer_id'])) {
    $freelancerId = $_GET['freelancer_id'];

    // Create a new database connection
    $connection = new Connection();
    $pdo = $connection->getConnection();

    try {
        // Prepare the SQL queries
        $freelancerQuery = $pdo->prepare("SELECT * FROM users WHERE id = :freelancer_id");
        $albumQuery = $pdo->prepare("SELECT album_id, album_img FROM album WHERE freelancer_id = :freelancer_id");
        $recommendationQuery = $pdo->prepare("SELECT COUNT(*) AS recommendation_count FROM reviews WHERE freelancer_id = :freelancer_id AND recommendation = 'Recommended'");

        // Execute the queries
        $freelancerQuery->execute(['freelancer_id' => $freelancerId]);
        $albumQuery->execute(['freelancer_id' => $freelancerId]);
        $recommendationQuery->execute(['freelancer_id' => $freelancerId]);

        // Fetch results
        $freelancerData = $freelancerQuery->fetch(PDO::FETCH_ASSOC);
        $albumImages = $albumQuery->fetchAll(PDO::FETCH_ASSOC);
        $recommendationData = $recommendationQuery->fetch(PDO::FETCH_ASSOC);
        $recommendationCount = $recommendationData['recommendation_count'];

        // Prepare response
        if ($freelancerData) {
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'freelancer' => $freelancerData,
                    'albumImages' => $albumImages,
                    'recommendationCount' => $recommendationCount
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Freelancer not found'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Freelancer ID not provided'
    ]);
}
?>
