<?php
session_start();
include_once "../php/userconnection.php";

$outgoing_id = $_SESSION['USER']->unique_id;
$searchTerm = $_POST['searchTerm'];

$sql = "SELECT * FROM users WHERE status = 1 AND NOT unique_id = :outgoing_id AND (firstname LIKE :searchTerm OR lastname LIKE :searchTerm)";
$stmt = $newconnection->openConnection()->prepare($sql);
$stmt->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_INT);
$stmt->bindValue(':searchTerm', "%$searchTerm%", PDO::PARAM_STR);
$stmt->execute();

$output = "";

if ($stmt->rowCount() > 0) {
    include_once "system-messaging-data.php";
} else {
    $output .= '<p class="noUserOnSearch">No user found related to your search term.</p>';
}

echo $output;
?>
