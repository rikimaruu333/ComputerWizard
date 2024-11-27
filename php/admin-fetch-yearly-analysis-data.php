<?php
include 'userconnection.php';
$connection = new Connection();
$con = $connection->openConnection();

$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
$monthlyData = [];

for ($month = 1; $month <= 12; $month++) {
    $startDate = "$selectedYear-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
    $endDate = date('Y-m-t', strtotime($startDate));
    $query = "SELECT COUNT(*) AS total FROM users WHERE DATE(date) BETWEEN :startDate AND :endDate";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':startDate', $startDate);
    $stmt->bindParam(':endDate', $endDate);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $monthlyData[date('F', strtotime($startDate))] = $row['total'] ?? 0;
}

header('Content-Type: application/json');
echo json_encode($monthlyData);
?>
