<?php
include 'userconnection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['USER'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

$conn = new Connection();
$db = $conn->openConnection();

try {
    // Prepare SQL query to fetch all approved bookings
    $query = "SELECT b.booking_id, b.client_id, b.freelancer_id, b.job_type, b.payment_type, b.booking_date, b.booking_status, 
                     b.client_ET_request, b.freelancer_ET_request,
                     CONCAT(c.firstname, ' ', c.lastname) AS client_name, c.profile AS client_profile,
                     CONCAT(f.firstname, ' ', f.lastname) AS freelancer_name, f.profile AS freelancer_profile
              FROM booking AS b
              JOIN users AS c ON b.client_id = c.id
              JOIN users AS f ON b.freelancer_id = f.id
              WHERE b.booking_status = 'Approved' OR b.booking_status = 'Completed'";

    $stmt = $db->prepare($query);
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch admin users
    $adminQuery = "SELECT id FROM users WHERE usertype = 'Admin'";
    $adminStmt = $db->prepare($adminQuery);
    $adminStmt->execute();
    $adminUsers = $adminStmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare notifications for admin only (notify only once)
    $notifications = [];
    foreach ($adminUsers as $admin) {
        foreach ($bookings as $booking) {
            // Check if the booking has already been notified (notification_sent = 1)
            $checkNotificationQuery = "SELECT notification_sent FROM booking WHERE booking_id = :booking_id";
            $checkStmt = $db->prepare($checkNotificationQuery);
            $checkStmt->bindParam(':booking_id', $booking['booking_id']);
            $checkStmt->execute();
            $notificationSent = $checkStmt->fetchColumn();

            if ($notificationSent != 1) {
                // Add a notification for the admin
                $notifications[] = [
                    'userId' => $admin['id'],
                    'action' => 'Ongoing Transaction',
                    'message' => 'A transaction is ongoing between client ' . $booking['client_name'] . 
                                 ' and freelancer ' . $booking['freelancer_name'] . '.',
                ];

                // Update the booking record to mark the notification as sent
                $updateQuery = "UPDATE booking SET notification_sent = 1 WHERE booking_id = :booking_id";
                $updateStmt = $db->prepare($updateQuery);
                $updateStmt->bindParam(':booking_id', $booking['booking_id']);
                $updateStmt->execute();
            }
        }
    }

    // Send notifications to Socket.IO server if there are new ones
    if (count($notifications) > 0) {
        $socketUrl = 'http://localhost:8080/emit-ongoing-transaction-notification';
        $socketData = json_encode(['notifications' => $notifications]);
        $options = [
            'http' => [
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => $socketData,
            ],
        ];
        $context = stream_context_create($options);
        file_get_contents($socketUrl, false, $context);
    }

    // Include session user details and bookings in the response
    echo json_encode([
        'success' => true,
        'bookings' => $bookings,
        'user_id' => $_SESSION['USER']->id,
        'user_usertype' => $_SESSION['USER']->usertype
    ]);

} catch (PDOException $e) {
    // Handle database errors
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
