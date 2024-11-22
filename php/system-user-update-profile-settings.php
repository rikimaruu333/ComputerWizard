<?php
include 'userconnection.php';
session_start();

$newconnection = new Connection();
$pdo = $newconnection->getConnection(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $address = $_POST['address'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $id = $_POST['id'];

    try {
        // Prepare SQL update query
        $stmt = $pdo->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, address = :address, gender = :gender WHERE id = :id");

        // Execute the query with provided data
        $stmt->execute([
            ':firstname' => $firstName, 
            ':lastname' => $lastName,     
            ':address' => $address,
            ':gender' => $gender,
            ':id' => $id,
        ]);

        // Fetch the updated user details from the database
        $stmt = $pdo->prepare("SELECT firstname, lastname, address, gender FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $updatedUser = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the updated data as part of the response
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully!', 'data' => $updatedUser]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error updating profile: ' . $e->getMessage()]);
    }
}
?>
