<?php
session_start();
include 'userconnection.php';

// Open connection
$connection = new Connection();
$con = $connection->openConnection();

// Retrieve search and filter values
$searchTerm = isset($_POST['searchTerm']) ? trim($_POST['searchTerm']) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';
$gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
$restriction = isset($_POST['restriction']) ? trim($_POST['restriction']) : '';
$usertype = isset($_POST['usertype']) ? trim($_POST['usertype']) : '';

// Base query
$query = "
    SELECT u.id, u.firstname, u.lastname, u.usertype, u.profile, u.status,
           CASE 
               WHEN u.status = 3 THEN 'restricted'  
               WHEN u.status = 1 THEN 'unrestricted'
           END AS restriction_status
    FROM users u
    WHERE u.usertype IN ('Client', 'Freelancer')
    AND u.status IN (1, 3)";

// Add filters based on input
if (!empty($address)) {
    $query .= " AND u.address = :address";
}
if (!empty($gender)) {
    $query .= " AND u.gender = :gender";
}
if (!empty($restriction)) {
    // Use restriction values directly from the dropdown
    if ($restriction == 'restricted') {
        $query .= " AND u.status = 3";
    } elseif ($restriction == 'unrestricted') {
        $query .= " AND u.status = 1";
    }
}
if (!empty($usertype)) {
    $query .= " AND u.usertype = :usertype";
}
if (!empty($searchTerm)) {
    $query .= " AND (u.firstname LIKE :searchTerm OR u.lastname LIKE :searchTerm)";
}

$query .= " ORDER BY u.firstname ASC"; // Sort alphabetically

$stmt = $con->prepare($query);

// Bind parameters
if (!empty($address)) {
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
}
if (!empty($gender)) {
    $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
}
if (!empty($usertype)) {
    $stmt->bindParam(':usertype', $usertype, PDO::PARAM_STR);
}
if (!empty($searchTerm)) {
    $stmt->bindValue(':searchTerm', "%$searchTerm%", PDO::PARAM_STR);
}

$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON response
echo json_encode($users);

// Close connection
$con = null;
?>
