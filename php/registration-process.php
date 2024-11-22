<?php
include 'formfunctions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'usersignup') {
        // Gather the data for user signup
        $data = [
            'usertype' => $_POST['usertype'],
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'address' => $_POST['address'],
            'phone' => $_POST['phone'],
            'age' => $_POST['age'],
            'gender' => $_POST['gender'],
            'profile' => $_POST['profile'],
            'status' => $_POST['status'], // Ensure this is set correctly
            'email' => $_POST['email'],
            'password' => $_POST['password']
        ];

        // Call the usersignup function
        $errors = usersignup($data);

        if (empty($errors)) {
            echo json_encode(['success' => true, 'redirect' => 'formfunctions.php']);
        } else {
            echo json_encode(['errors' => $errors]);
        }
        exit; // Stop further execution
    }
}
?>
