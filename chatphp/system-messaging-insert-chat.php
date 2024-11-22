<?php
session_start();
include_once "../php/userconnection.php";

function str_openssl_enc($str, $iv)
{
    $key = '1234567890vishal%$%^%$$#$#'; // Your encryption key
    $cipher = "AES-128-CTR"; // Cipher method
    $options = 0; // Options for encryption
    $str = openssl_encrypt($str, $cipher, $key, $options, $iv); // Encrypt the message
    return $str; // Return encrypted message
}

// Check if incoming_id and message are set
if (isset($_POST['incoming_id']) && isset($_POST['message'])) {
    $iv = openssl_random_pseudo_bytes(16); // Generate random initialization vector
    $outgoing_id = $_SESSION['USER']->unique_id; // Get outgoing user ID from session
    $incoming_id = $_POST['incoming_id']; // Get incoming user ID from POST data
    $message = $_POST['message']; // Get message from POST data

    $message = str_openssl_enc($message, $iv); // Encrypt the message
    $iv = bin2hex($iv); // Convert IV to hexadecimal

    // Check if the message is not empty
    if (!empty($message)) {
        // Prepare the SQL statement to insert the message into the database
        $stmt = $newconnection->openConnection()->prepare("INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg, iv)
            VALUES (:incoming_id, :outgoing_id, :message, :iv)");
        // Bind parameters
        $stmt->bindParam(':incoming_id', $incoming_id, PDO::PARAM_INT);
        $stmt->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_INT);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->bindParam(':iv', $iv, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            // Return success response
            echo json_encode(['status' => 'success', 'message' => 'Message sent successfully.']);
        } else {
            // Return error response if insertion fails
            echo json_encode(['status' => 'error', 'message' => 'Message could not be sent.']);
        }
    } else {
        // Return error response if message is empty
        echo json_encode(['status' => 'error', 'message' => 'Message cannot be empty.']);
    }
} else {
    // Return error response if POST data is missing
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
