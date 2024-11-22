<?php
include 'userconnection.php';
session_start();

header('Content-Type: application/json');

$conn = new Connection();
$db = $conn->openConnection();

// Check if the freelancer's ID is set in the session
if (isset($_SESSION['USER']->id)) {
    $freelancerId = $_SESSION['USER']->id;

    try {
        $stmt = $db->prepare("
            SELECT r.rating, r.review, r.review_date, 
                   CONCAT(c.firstname, ' ', c.lastname) AS client_name, 
                   c.usertype, c.profile 
            FROM reviews r
            JOIN users c ON r.client_id = c.id
            WHERE r.freelancer_id = :freelancer_id
        ");
        $stmt->bindParam(':freelancer_id', $freelancerId, PDO::PARAM_INT);
        $stmt->execute();

        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($reviews) {
            echo json_encode(['success' => true, 'reviews' => $reviews]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No reviews found for this freelancer.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    } finally {
        $conn->closeConnection();
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Freelancer ID not found in session.']);
}
?>
