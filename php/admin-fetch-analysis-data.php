<?php
session_start();
include 'userconnection.php';

$connection = new Connection();
$con = $connection->openConnection();

// Function to fetch registered users by type and status on each day of the month
function fetchRegisteredUsersByDay($userType, $status, $selectedMonth, $selectedYear) {
    global $con;
    $daysInMonth = date('t', strtotime("$selectedYear-$selectedMonth-01")); // Get total days in the selected month
    $result = [];

    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = "$selectedYear-$selectedMonth-".str_pad($day, 2, '0', STR_PAD_LEFT); // Format date as YYYY-MM-DD
        $query = "SELECT COUNT(*) AS total
                  FROM users
                  WHERE usertype = :userType 
                  AND status = :status
                  AND DATE(date) = :date";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':userType', $userType);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $result[] = $row['total'] ?? 0;
    }

    return $result;
}

// Get selected month and year from GET parameters
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('m');
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Fetch data for clients and freelancers (only with status 1)
$totalRegisteredClients = fetchRegisteredUsersByDay('Client', 1, $selectedMonth, $selectedYear);
$totalRegisteredFreelancers = fetchRegisteredUsersByDay('Freelancer', 1, $selectedMonth, $selectedYear);

// Fetch pending freelancer registration requests (status 0)
$pendingFreelancerRequests = fetchRegisteredUsersByDay('Freelancer', 0, $selectedMonth, $selectedYear);

// Prepare response as JSON
$response = [
    'totalRegisteredClients' => $totalRegisteredClients,
    'totalRegisteredFreelancers' => $totalRegisteredFreelancers,
    'freelancerRequests' => $pendingFreelancerRequests
];

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
