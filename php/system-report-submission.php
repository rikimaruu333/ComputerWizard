<?php
session_start(); // Assuming session is used for user IDs
include 'userconnection.php'; // Include the database connection file

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Function to send notifications to the WebSocket server
function emitNotification($report) {
    $url = 'http://localhost:8080/emit-notification'; // Adjust this URL if your server is on a different address
    $data = json_encode($report); // Convert report data to JSON

    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // Execute the request
    $response = curl_exec($ch);
    curl_close($ch);

    return $response; // Optionally handle the response if needed
}

// Handle POST request for report submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // If the request is for marking reports as viewed
    if (isset($_POST['markAsViewed']) && $_POST['markAsViewed'] == '1') {
        try {
            $connection = new Connection(); // Assuming you have a Connection class for PDO
            $db = $connection->openConnection();

            // Update all unviewed reports to viewed (report_notification = 1)
            $stmt = $db->prepare("UPDATE reports SET report_notification = 1 WHERE report_notification = 0");
            if ($stmt->execute()) {
                // Emit notification for marked reports
                emitNotification(['action' => 'reportsViewed', 'message' => 'Reports have been marked as viewed.']);
                echo json_encode(['success' => true]); // Successfully marked as viewed
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to mark reports as viewed.']);
            }

            $connection->closeConnection();
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]); // Handle database errors
        }
        exit(); // Stop further execution after marking reports as viewed
    }

    // Report submission handling
    // Capture user IDs and content
    $reporterUserId = $_SESSION['USER']->id; // Assuming the session contains user info
    $reportedUserId = $_POST['reported_user_id'];
    $reportContent = $_POST['report_content'];
    $reportReason = $_POST['report_reason']; // Get report reason from the POST request
    $reportDate = date('Y-m-d H:i:s'); // Get the current date and time
    $reportNotification = 0; // Unviewed notification initially

    try {
        $connection = new Connection(); // Assuming you have a Connection class for PDO
        $db = $connection->openConnection();

        // Prepare and execute the insert statement with report_reason
        $stmt = $db->prepare("INSERT INTO reports (reporter_user_id, reported_user_id, report_content, report_reason, report_date, report_notification) 
                              VALUES (:reporter_user_id, :reported_user_id, :report_content, :report_reason, :report_date, :report_notification)");
        $stmt->bindParam(':reporter_user_id', $reporterUserId);
        $stmt->bindParam(':reported_user_id', $reportedUserId);
        $stmt->bindParam(':report_content', $reportContent);
        $stmt->bindParam(':report_reason', $reportReason); // Bind report reason
        $stmt->bindParam(':report_date', $reportDate);
        $stmt->bindParam(':report_notification', $reportNotification);

        // Execute the prepared statement and check for success
        if ($stmt->execute()) {
            // Calculate the new count of unviewed reports
            $unviewedStmt = $db->prepare("SELECT COUNT(*) as unviewedCount FROM reports WHERE report_notification = 0");
            $unviewedStmt->execute();
            $unviewedCount = $unviewedStmt->fetch(PDO::FETCH_ASSOC)['unviewedCount'];

            // Emit notification for new report
            emitNotification([
                'action' => 'newReport',
                'report' => [
                    'report_content' => $reportContent,
                    'report_reason' => $reportReason, // Add report reason here
                    'report_date' => $reportDate,
                    'reporter_user_id' => $reporterUserId,
                    'reporter_profile_image_url' => '', // Optionally include the profile image URL
                    'reported_user_id' => $reportedUserId,
                    'reported_profile_image_url' => '', // Optionally include the reported user's profile image URL
                ],
                'unviewedCount' => $unviewedCount // Include the unviewed count in the notification
            ]);

            echo json_encode(['success' => true]); // Report submitted successfully
        } else {
            echo json_encode(['success' => false, 'error' => 'Error occurred while submitting the report.']); // General error message
        }

        $connection->closeConnection();
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]); // Handle database errors
    }
    exit(); // Stop further execution after report submission
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Open the database connection
        $connection = new Connection();
        $db = $connection->openConnection();

        // Query to fetch all reports and join with the users table to get profile data
        $stmt = $db->prepare("
            SELECT 
                r.report_id, 
                r.report_content, 
                r.report_reason, 
                r.report_date, 
                r.reporter_user_id, 
                r.reported_user_id, 
                r.report_notification, 
                ru1.id AS reporter_id, 
                ru1.firstname AS reporter_firstname, 
                ru1.lastname AS reporter_lastname, 
                ru1.usertype AS reporter_usertype, 
                ru1.profile AS reporter_profile_image_url, 
                ru2.id AS reported_id, 
                ru2.firstname AS reported_firstname, 
                ru2.lastname AS reported_lastname, 
                ru2.usertype AS reported_usertype, 
                ru2.profile AS reported_profile_image_url 
            FROM reports r
            LEFT JOIN users ru1 ON r.reporter_user_id = ru1.id 
            LEFT JOIN users ru2 ON r.reported_user_id = ru2.id 
            ORDER BY r.report_id DESC
        ");
        $stmt->execute();
        $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Truncate the report_reason to 60 characters for each report
        foreach ($reports as &$report) {
            // Keep full reason for modal, and truncate only for notifications
            $report['truncated_reason'] = strlen($report['report_reason']) > 82 ? substr($report['report_reason'], 0, 82) . '...' : $report['report_reason'];
        }
        
        // Query to fetch the count of unviewed reports
        $unviewedStmt = $db->prepare("SELECT COUNT(*) as unviewedCount FROM reports WHERE report_notification = 0");
        $unviewedStmt->execute();
        $unviewedCount = $unviewedStmt->fetch(PDO::FETCH_ASSOC)['unviewedCount'];

        // Return the reports and unviewed count as a JSON response
        echo json_encode(['success' => true, 'reports' => $reports, 'unviewedCount' => $unviewedCount]);

        // Close the connection
        $connection->closeConnection();
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

?>
