<?php
session_start();
include 'userconnection.php';

if (isset($_SESSION['USER']->id)) {
    $userId = $_SESSION['USER']->id;

    // Create a new database connection
    $conn = new Connection();
    $db = $conn->openConnection();

    try {
        // Query to get the client's basic info
        $stmt = $db->prepare("SELECT usertype, firstname, lastname, email, address, gender, profile FROM users WHERE id = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        // Query to count recommendations
        $recommendationStmt = $db->prepare("SELECT COUNT(*) AS recommendation_count FROM reviews WHERE freelancer_id = :freelancer_id AND recommendation = 'Recommended'");
        $recommendationStmt->bindParam(':freelancer_id', $userId);
        $recommendationStmt->execute();

        $recommendationData = $recommendationStmt->fetch(PDO::FETCH_ASSOC);
        $recommendationCount = $recommendationData['recommendation_count'];

        // Query to count completed transactions for the client
        $transactionStmt = $db->prepare("SELECT COUNT(*) AS transaction_count FROM booking WHERE client_id = :client_id AND booking_status = 'Completed'");
        $transactionStmt->bindParam(':client_id', $userId);
        $transactionStmt->execute();

        $transactionData = $transactionStmt->fetch(PDO::FETCH_ASSOC);
        $transactionCount = $transactionData['transaction_count'];

        if ($client) {
            // Include recommendation and transaction counts in the response
            $client['recommendation_count'] = $recommendationCount;
            $client['transaction_count'] = $transactionCount;
            echo json_encode(['status' => 'success', 'data' => $client]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Client not found']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No client logged in']);
}
?>
