<?php
    require "formfunctions.php";
    usercheck_login();
    
    if($_SESSION['USER']->usertype !== 'Freelancer' && $_SESSION['USER']->usertype !== 'Admin') header("Location: client-dashboard.php");
    if($_SESSION['USER']->usertype !== 'Freelancer' && $_SESSION['USER']->usertype !== 'Client') header("Location: admin-dashboard.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../toastr.min.css">
    <link rel="stylesheet" href="../css/globalstyle.css">
</head>
<body>
<?php include "systemfreelancerheader.php";?>
<?php include "systemfreelancersidebar.php";?>
<?php include "system-messaging.php";?>
<?php include "system-booking-request.php";?>
<?php include "system-transaction-details.php";?>

        
    <script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="../js/system-messaging.js"></script>
    <script src="../js/freelancer-service.js"></script>
    <script src="../js/freelancer-schedule.js"></script>
    <script src="../js/system-notifications.js"></script>
    <script src="../js/system-user-settings.js"></script>
    <script src="../js/system-sidebar.js"></script>
    <script src="../js/system-booking-request.js"></script>
    <script src="../js/system-check-restriction.js"></script>
</body>
</html>