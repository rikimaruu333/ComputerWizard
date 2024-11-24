<?php
session_start();
include_once "../php/userconnection.php"; // Your database connection file

$id = isset($_GET['id']) ? $_GET['id'] : null;  // Ensure ID is provided
$usertype = isset($_GET['usertype']) ? $_GET['usertype'] : null;  // Ensure userType is provided

if (!$id || !$usertype) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters provided.']);
    exit;
}

try {
    // Fetch user details from the users table using the unique_id
    $stmt = $newconnection->openConnection()->prepare("SELECT * FROM users WHERE unique_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_OBJ);

    if ($row) {
        // Default transaction count
        $transactionCount = 0;

        // Check the user type and fetch transaction count accordingly
        if ($usertype === 'Client') {
            $transactionStmt = $newconnection->openConnection()->prepare("SELECT COUNT(*) AS transaction_count FROM booking WHERE client_id = :client_id AND booking_status = 'Completed'");
            $transactionStmt->bindParam(':client_id', $row->id);
        } elseif ($usertype === 'Freelancer') {
            $transactionStmt = $newconnection->openConnection()->prepare("SELECT COUNT(*) AS transaction_count FROM booking WHERE freelancer_id = :freelancer_id AND booking_status = 'Completed'");
            $transactionStmt->bindParam(':freelancer_id', $row->id);
        }

        // Execute the statement if a valid user type is provided
        if (isset($transactionStmt)) {
            $transactionStmt->execute();
            $transactionData = $transactionStmt->fetch(PDO::FETCH_ASSOC);
            $transactionCount = $transactionData['transaction_count'];
        }

        // Send user details back to the front end along with the transaction count
        echo json_encode([
            'status' => 'success',
            'id' => htmlspecialchars($row->id),
            'firstname' => htmlspecialchars($row->firstname),
            'lastname' => htmlspecialchars($row->lastname),
            'profile' => htmlspecialchars($row->profile),  // User profile picture
            'fullname' => htmlspecialchars($row->firstname) . ' ' . htmlspecialchars($row->lastname),  // Full name
            'email' => htmlspecialchars($row->email),  // Email
            'phone' => htmlspecialchars($row->phone),  // Phone
            'usertype' => $usertype,  // User type ('freelancer', 'client')
            'incoming_id' => $id,  // Incoming ID used for chat
            'transaction_count' => $transactionCount  // Include transaction count
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

