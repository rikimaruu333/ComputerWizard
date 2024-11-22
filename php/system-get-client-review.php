<?php
include 'userconnection.php'; // Ensure this file is included for the Connection class
session_start();

header('Content-Type: application/json');

// Create a new connection instance and open the database connection
$conn = new Connection();
$db = $conn->openConnection(); // Use openConnection() to get the PDO object

// Check if booking_id is provided
if (isset($_GET['booking_id'])) {
    $bookingId = $_GET['booking_id'];

    try {
        // Concatenate firstname and lastname as client_name
        $stmt = $db->prepare("
            SELECT r.rating, r.review, r.review_date, r.client_id, 
                   CONCAT(c.firstname, ' ', c.lastname) AS client_name, 
                   c.usertype, c.profile 
            FROM reviews r
            JOIN users c ON r.client_id = c.id
            WHERE r.booking_id = :booking_id
        ");
        $stmt->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);
        $stmt->execute();

        // Check if a review was found
        if ($stmt->rowCount() > 0) {
            $review = $stmt->fetch(PDO::FETCH_ASSOC);

            // Truncate the review to a maximum of 100 characters
            $truncatedReview = strlen($review['review']) > 100 ? substr($review['review'], 0, 110) . '...' : $review['review'];

            // Return success response with review details
            echo json_encode([
                'success' => true,
                'review' => [
                    'rating' => $review['rating'],
                    'review' => $truncatedReview,
                    'review_date' => $review['review_date'],
                    'client_name' => $review['client_name'],
                    'profile' => $review['profile'],
                    'client_id' => $review['client_id'],
                    'loggedInUserId' => $_SESSION['USER']->id // Ensure the logged-in user's ID is passed here
                ]
            ]);
        } else {
            // No review found for the given booking ID
            echo json_encode([
                'success' => false,
                'error' => 'No review found for this booking.'
            ]);
        }
    } catch (PDOException $e) {
        // Handle database error
        echo json_encode([
            'success' => false,
            'error' => 'Database error: ' . $e->getMessage()
        ]);
    } finally {
        // Close the connection after the query
        $conn->closeConnection();
    }
} else {
    // Missing booking ID
    echo json_encode([
        'success' => false,
        'error' => 'Booking ID not provided.'
    ]);
}
?>
