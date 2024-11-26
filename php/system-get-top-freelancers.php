<?php
session_start();
include 'userconnection.php';

try {
    $connection = new Connection();
    $con = $connection->openConnection();

    $userID = $_SESSION['USER']->id;

    $stmt = $con->prepare("
        SELECT u.id, u.firstname, u.lastname, u.usertype, u.profile,
               AVG(r.rating) AS rating,
               COUNT(CASE WHEN r.recommendation = 'Recommended' THEN 1 END) AS recommendation_count
        FROM users u
        JOIN reviews r ON u.id = r.freelancer_id
        WHERE u.usertype = 'Freelancer' AND u.status = 1
        GROUP BY u.id
        ORDER BY recommendation_count DESC
        LIMIT 5
    ");
    $stmt->execute();

    $freelancers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['freelancers' => $freelancers, 'userID' => $userID]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error fetching data: ' . $e->getMessage()]);
}
?>
