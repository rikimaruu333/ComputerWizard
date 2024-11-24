<?php
session_start();
include 'userconnection.php';
date_default_timezone_set('Asia/Manila'); // Set timezone

$connection = new Connection();
$con = $connection->openConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $freelancerId = $_POST['freelancer_id'] ?? null;
    $status = $_POST['status'] ?? null;
    $idType = $_POST['id_type'] ?? null;
    $idFullName = $_POST['id_full_name'] ?? null;
    $validationDate = date('Y-m-d H:i:s'); // Current date and time

    if (!$freelancerId || !$idType || !$idFullName || ($status != 1 && $status != 2)) {
        echo json_encode(['error' => 'Invalid request parameters.']);
        exit;
    }

    try {
        // Check if id_type or id_full_name already exists
        $checkQuery = "SELECT COUNT(*) as count FROM valid_id_validation WHERE id_type = :id_type AND id_full_name = :id_full_name";
        $checkStmt = $con->prepare($checkQuery);
        $checkStmt->execute([
            ':id_type' => $idType,
            ':id_full_name' => $idFullName,
        ]);

        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
        if ($result['count'] > 0) {
            echo json_encode(['error' => 'This type of valid ID with this full name already exists.']);
            exit;
        }

        // Proceed with the transaction if no duplicates
        $con->beginTransaction();

        // Update freelancer status
        $updateQuery = "UPDATE users SET status = :status, date = :date WHERE id = :freelancer_id";
        $updateStmt = $con->prepare($updateQuery);
        $updateStmt->execute([
            ':status' => $status,
            ':date' => $validationDate,
            ':freelancer_id' => $freelancerId,
        ]);

        // Insert validation details
        $insertQuery = "INSERT INTO valid_id_validation (id_type, id_full_name, validation_date) 
                        VALUES (:id_type, :id_full_name, :validation_date)";
        $insertStmt = $con->prepare($insertQuery);
        $insertStmt->execute([
            ':id_type' => $idType,
            ':id_full_name' => $idFullName,
            ':validation_date' => $validationDate,
        ]);

        $con->commit();

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $con->rollBack();
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
