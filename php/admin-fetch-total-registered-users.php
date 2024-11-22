<?php
session_start();
include 'userconnection.php';

// Open connection
$connection = new Connection();
$con = $connection->openConnection();

// Function to execute a query and fetch a single row result
function fetchCount($conn, $query) {
    $stmt = $conn->query($query);
    return $stmt->fetchColumn();
}

$queryClients = "SELECT COUNT(*) FROM users WHERE usertype = 'Client' AND status = 1";
$queryFreelancers = "SELECT COUNT(*) FROM users WHERE usertype = 'Freelancer' AND status = 1";
$queryRestrictedUsers = "SELECT COUNT(*) FROM users WHERE (usertype = 'Client' OR usertype = 'Freelancer') AND status = 3";

// Fetch counts
$totalClients = fetchCount($con, $queryClients);
$totalFreelancers = fetchCount($con, $queryFreelancers);
$totalRestrictedUsers = fetchCount($con, $queryRestrictedUsers);

// Prepare response as JSON
$response = [
    'totalClients' => $totalClients,
    'totalFreelancers' => $totalFreelancers,
    'totalRestrictedUsers' => $totalRestrictedUsers
];

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
