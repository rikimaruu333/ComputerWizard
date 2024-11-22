<?php
session_start();
include("../php/userconnection.php"); // Include your database connection
header('Content-Type: application/json');

// Define the decryption function
function str_openssl_dec($encryptedStr, $iv) {
    $key = '1234567890vishal%$%^%$$#$#'; // Same key used for encryption
    $cipher = "AES-128-CTR"; // Cipher method
    $options = 0;
    $decryptedStr = openssl_decrypt($encryptedStr, $cipher, $key, $options, $iv);
    return $decryptedStr;
}

// Function to format date and time to a readable format
function formatDateandTimeToWords($datetime) {
    date_default_timezone_set('Asia/Manila'); // Set timezone

    $timestamp = strtotime($datetime);
    
    if ($timestamp === false) {
        return 'Invalid date';
    }

    $currentTimestamp = time();
    
    // Calculate the time difference
    $timeDifference = $currentTimestamp - $timestamp;

    // Format the output based on time difference
    if ($timeDifference < 60) {
        return 'Just now';
    } elseif ($timeDifference < 3600) {
        return floor($timeDifference / 60) . ' minutes ago';
    } elseif ($timeDifference < 86400) {
        return floor($timeDifference / 3600) . ' hours ago';
    } else {
        return date('M d, Y h:i A', $timestamp); // Format as date if more than a day ago
    }
}

$response = [];

if (isset($_POST['incoming_id'])) {
    $userId = $_SESSION['USER']->unique_id;
    $incomingId = $_POST['incoming_id'];

    try {
        $newconnection = new Connection();

        // Fetch chat messages
        $stmtMessages = $newconnection->openConnection()->prepare("
            SELECT messages.*, users.profile 
            FROM messages 
            LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id 
            WHERE (outgoing_msg_id = :userid AND incoming_msg_id = :incomingId) 
            OR (outgoing_msg_id = :incomingId AND incoming_msg_id = :userid) 
            ORDER BY msg_id ASC
        ");
        $stmtMessages->bindParam(':userid', $userId, PDO::PARAM_INT);
        $stmtMessages->bindParam(':incomingId', $incomingId, PDO::PARAM_INT);
        $stmtMessages->execute();

        $messages = $stmtMessages->fetchAll(PDO::FETCH_OBJ);

        // Prepare messages array
        if ($messages) {
            foreach ($messages as $message) {
                $iv = hex2bin($message->iv); // Convert IV from hex to binary
                $messageText = str_openssl_dec($message->msg, $iv); // Decrypt message
                $formattedDate = formatDateandTimeToWords($message->msg_datetime); // Format the datetime

                // Add message data to the response array
                $response[] = [
                    'msg' => htmlspecialchars($messageText),
                    'profile' => htmlspecialchars($message->profile),
                    'datetime' => $formattedDate,
                    'outgoing' => ($message->outgoing_msg_id == $userId)
                ];
            }
        } else {
            $response['error'] = 'Say Hi, to start a conversation with this user.';
        }
    } catch (PDOException $e) {
        $response['error'] = "There's a problem in the query: " . $e->getMessage();
    }
}

// Output the JSON response
echo json_encode($response);
exit;
