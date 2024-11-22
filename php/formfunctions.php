<?php

session_start();


function usersignup($data) {
    $errors = array();
    
    $arr['email'] = $data['email'];

    $query = "SELECT * FROM users WHERE email = :email AND status = 2 LIMIT 1";
    $rejectedUser = userdata($query, $arr);

    if ($rejectedUser) {
        // Update the rejected user's details if found
        $query = "UPDATE users 
                  SET usertype = :usertype, 
                      firstname = :firstname, 
                      lastname = :lastname, 
                      password = :password, 
                      date = :date, 
                      address = :address,
                      phone = :phone,
                      age = :age,
                      gender = :gender,
                      profile = :profile,
                      status = :status 
                  WHERE email = :email AND status = 2";

        $arr['usertype'] = $data['usertype'];
        $arr['firstname'] = $data['firstname'];
        $arr['lastname'] = $data['lastname'];
        $arr['password'] = hash('sha256', $data['password']);
        $arr['date'] = date("Y-m-d");
        $arr['address'] = $data['address'];
        $arr['phone'] = $data['phone'];
        $arr['age'] = $data['age'];
        $arr['gender'] = $data['gender'];
        $arr['profile'] = $data['profile'];
        $arr['status'] = $data['status']; 

        if (count($errors) == 0) {
            userdata($query, $arr);
            // redirectUser($data['usertype']);
            return [];
        }
    } else { 
        if (count($errors) == 0) {
            $unique_id = rand(time(), 100000000);
            $arr['unique_id'] = $unique_id;
            $arr['usertype'] = $data['usertype'];
            $arr['firstname'] = $data['firstname'];
            $arr['lastname'] = $data['lastname'];
            $arr['email'] = $data['email'];
            $arr['password'] = hash('sha256', $data['password']);
            $arr['date'] = date("Y-m-d");
            $arr['address'] = $data['address'];
            $arr['phone'] = $data['phone'];
            $arr['age'] = $data['age'];
            $arr['gender'] = $data['gender'];
            $arr['profile'] = $data['profile'];
            $arr['status'] = $data['status'];

            // Check if usertype is Freelancer and if file is provided
            if ($data['usertype'] == 'Freelancer') {
                if (isset($_FILES['file']) && $_FILES['file']['size'] > 0) {
                    $file = $_FILES['file'];
                    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                    $maxFileSize = 10 * 1024 * 1024;

                    // Validate file type and size
                    if (!in_array($file['type'], $allowedTypes)) {
                        $errors[] = 'Invalid file type. Only JPG, PNG, and PDF files are allowed.';
                    }
                    if ($file['size'] > $maxFileSize) {
                        $errors[] = 'File size exceeds the maximum limit of 10MB.';
                    }

                    // If no errors, proceed with file upload
                    if (empty($errors)) {
                        $targetPath = '../valid_id/' . basename($file['name']);
                        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                            $arr['valid_id'] = $targetPath;
                        } else {
                            $errors[] = 'File upload failed. Please try again.';
                        }
                    }
                } else {
                    // If no valid ID uploaded for freelancers, throw an error
                    $errors[] = 'Freelancers must upload a valid ID.';
                }
            } else if ($data['usertype'] == 'Client') {
                // For Clients, valid_id can be left empty
                $arr['valid_id'] = '';
            }

            // Proceed with registration if there are no errors
            if (empty($errors)) {
                $query = "INSERT INTO users (unique_id, usertype, firstname, lastname, email, password, date, address, phone, age, gender, profile, status, valid_id) 
                          VALUES (:unique_id, :usertype, :firstname, :lastname, :email, :password, :date, :address, :phone, :age, :gender, :profile, :status, :valid_id)";
                
                userdata($query, $arr);
                return [];
            }
        }
    }
    
    return $errors;
}

function approveRegistration($unique_id) {
    $query = "UPDATE users SET status = 1 WHERE unique_id = :unique_id";
    $vars = [':unique_id' => $unique_id];
    userdata($query, $vars);
}

function getUserDetails($unique_id) {
    $query = "SELECT * FROM users WHERE unique_id = :unique_id LIMIT 10";
    $vars = [':unique_id' => $unique_id];

    return userdata($query, $vars)[0]; 
}

function userprofilesetup($data){
    $errors = array();

    if (!preg_match('/^09\d{2}\d{3}\d{4}$/', $data['phone'])) {
        $errors[] = "Please enter a valid mobile number in 09XXXXXXXXX format.";
    }

    if(count($errors) == 0){
        // Upload the profile image
        $uploadedFilePath = null;
        if (isset($_FILES["profile"])) {
            $target_dir = "../profile/"; // Directory where uploaded files will be stored
            $target_file = $target_dir . basename($_FILES["profile"]["name"]);

            // Check if file is an actual image
            $check = getimagesize($_FILES["profile"]["tmp_name"]);
            if ($check !== false) {
                // Upload the file to the specified directory
                if (move_uploaded_file($_FILES["profile"]["tmp_name"], $target_file)) {
                    $uploadedFilePath = $target_file;
                } else {
                    $errors[] = "Sorry, there was an error uploading your file.";
                }
            } else {
                $errors[] = "File is not an image.";
            }
        }
        
        // Update the existing record in the table
        $query = "UPDATE users 
                  SET profile = :profile, phone = :phone, address = :address, birthdate = :birthdate, gender = :gender
                  WHERE email = :email" ;

        $arr['profile'] = $uploadedFilePath; 
        $arr['phone'] = $data['phone'];
        $arr['address'] = $data['address'];
        $arr['birthdate'] = $data['birthdate'];
        $arr['gender'] = $data['gender'];
        $arr['email'] = $data['email'];

        
        userdata($query, $arr);
    }

    return $errors;
}

function usersettings($data) {
    $errors = array();

    if (!preg_match('/^[a-zA-Z ]+$/', $data['firstname'])) {
        $errors[] = "Please enter a valid First name.";
    }

    if (!preg_match('/^[a-zA-Z]+$/', $data['lastname'])) {
        $errors[] = "Please enter a valid Last name.";
    }

    if (!preg_match('/^[A-Za-z0-9\s.,]+$/', $data['address'])) {
        $errors[] = "Please enter a valid address.";
    }

    $_SESSION['USER']->email = $data['email'];
    $_SESSION['USER']->firstname = $data['firstname'];
    $_SESSION['USER']->lastname = $data['lastname'];
    $_SESSION['USER']->birthdate = $data['birthdate'];
    $_SESSION['USER']->gender = $data['gender'];
    $_SESSION['USER']->address = $data['address'];

    // Check if a file is uploaded
    if (isset($_FILES["profile"]) && $_FILES["profile"]["error"] != 4) {
        // Upload the profile image
        $target_dir = "../profile/"; // Assuming "profile" is one level up from "php"
        $target_file = $target_dir . basename($_FILES["profile"]["name"]);

        // Check if file is an actual image
        $check = getimagesize($_FILES["profile"]["tmp_name"]);
        if ($check !== false) {
            // Upload the file to the specified directory
            if (move_uploaded_file($_FILES["profile"]["tmp_name"], $target_file)) {
                $_SESSION['USER']->profile = $target_file; // Update the profile image path in the session
            } else {
                $errors[] = "Sorry, there was an error uploading your file.";
            }
        } else {
            $errors[] = "File is not an image.";
        }
    }   

    // Update the existing record in the database if needed
    if (count($errors) === 0) {
        $query = "UPDATE users SET profile = :profile, firstname = :firstname, lastname = :lastname, address = :address, birthdate = :birthdate, gender = :gender WHERE email = :email";

        $vars = array(
            ':profile' => $_SESSION['USER']->profile,
            ':firstname' => $_SESSION['USER']->firstname,
            ':lastname' => $_SESSION['USER']->lastname,
            ':address' => $_SESSION['USER']->address,
            ':birthdate' => $_SESSION['USER']->birthdate,
            ':gender' => $_SESSION['USER']->gender,
            ':email' => $_SESSION['USER']->email
        );

        // Call the userdata function to update the database
        userdata($query, $vars);
    }

    return $errors;
}


function userlogin($data) {
    $errors = array();

    if (count($errors) == 0) {
        $arr['email'] = $data['email'];
        $password = hash('sha256', $data['password']);

        $query = "SELECT * FROM users WHERE email = :email AND password = :password LIMIT 1";

        $arr['password'] = $password;
        $row = userdata($query, $arr);

        if (is_array($row) && $row[0]->status == 1) {
            $_SESSION['USER'] = $row[0];
            $_SESSION['LOGGED_IN'] = true;

            $redirect_url = '';
            if ($row[0]->usertype === 'Client') {
                $redirect_url = 'client-dashboard.php';
            } elseif ($row[0]->usertype === 'Freelancer') {
                $redirect_url = 'freelancer-dashboard.php';
            } elseif ($row[0]->usertype === 'Admin') {
                $redirect_url = 'admin-dashboard.php';
            } else {
                $redirect_url = 'landingpage.php';
            }

            return array('redirect' => $redirect_url);
        } elseif (is_array($row) && $row[0]->status == 0) {
            $errors[] = "Your registration is still pending, please wait for admin's approval.";
        } elseif (is_array($row) && $row[0]->status == 2) {
            $errors[] = "Your registration has been rejected.<br> Please sign up again with valid credentials.";
        } elseif (is_array($row) && $row[0]->status == 3) {
            $errors[] = "Your account has been restricted temporarily. Check your registered email for more details.";
        } else {
            $errors[] = "Wrong email or password";
        }
    }

    return array('errors' => $errors);
}


function usercheck_login($redirect = true){
    if(isset($_SESSION['USER']) && isset($_SESSION['LOGGED_IN'])){

        return true;

    }
    if($redirect){
        header("Location: landingpage.php");
        die;
    }else{
        return false;
    }
}

function userdata($query,$vars = array()){
    $string = "mysql:host=localhost;dbname=computerwizard";
    $con = new PDO($string,'root','');

    if(!$con){
        return false;
    }

    $stm = $con->prepare($query);
    $check = $stm->execute($vars);

    if($check){
        $data = $stm->fetchAll(PDO::FETCH_OBJ);
        if(count($data) > 0){
            return $data;
        }
    }
    return false;
}