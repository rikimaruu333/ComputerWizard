<?php
session_start();
include_once "../php/userconnection.php";

$outgoing_id = $_SESSION['USER']->unique_id;
$sql = "SELECT users.*, MAX(messages.msg_datetime) AS latest_msg_datetime
        FROM users
        LEFT JOIN messages ON users.unique_id = messages.incoming_msg_id OR users.unique_id = messages.outgoing_msg_id
        WHERE users.status = 1 AND NOT users.unique_id = :outgoing_id
        AND (messages.incoming_msg_id = :outgoing_id OR messages.outgoing_msg_id = :outgoing_id)
        GROUP BY users.id
        ORDER BY CASE
                    WHEN MAX(messages.msg_datetime) IS NOT NULL THEN MAX(messages.msg_datetime)
                    ELSE '9999-12-31 23:59:59'  -- A future date to push users without messages to the end
                END DESC";

try {
    $stmt = $newconnection->openConnection()->prepare($sql);
    $stmt->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_INT);
    $stmt->execute();

    $output = "";

    if ($stmt->rowCount() == 0) {
        $output .= "<p class='noUserOnSearch'>No user with existing messages are available to chat</p>";
    } elseif ($stmt->rowCount() > 0) {
        include_once "system-messaging-data.php"; // Assuming data.php is the file where you process the results
    }

    echo $output;
} catch (PDOException $e) {
    echo "There's a problem in the query: " . $e->getMessage();
}
?>
