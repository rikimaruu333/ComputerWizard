<?php
    require "formfunctions.php";
    usercheck_login();
    
    if($_SESSION['USER']->usertype !== 'Client' && $_SESSION['USER']->usertype !== 'Admin') header("Location: freelancer-dashboard.php");
    if($_SESSION['USER']->usertype !== 'Client' && $_SESSION['USER']->usertype !== 'Freelancer') header("Location: admin-dashboard.php");
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
    <link rel="stylesheet" href="../css/client-browse-freelancers.css">  
</head>
<body>
<?php include "systemclientheader.php";?>
<?php include "systemclientsidebar.php";?>
<?php include "system-get-freelancer-service-and-schedule-list.php";?>
<?php include "system-booking-request.php";?>
<?php include "system-transaction-details.php";?>

    <div class="client-browse-freelancers">
        <div class="search-bar-container">
            <div class="search" id="searchBar">
                <span class="text">Search a freelancer here...</span>
                <input type="text" placeholder="Enter name to search...">
                <button><i class="fas fa-search"></i></button>
            </div>
        </div>
        <div class="freelancer-list-container">
        </div>
    </div>
        
    <div class="freelancer-list-filter-container">
        <div class="filter-freelancer-list-header">
            <h1>FILTER</h1>
        </div>
        
        <div class="filter-selection">
            <select name="post-filter-recommendation" id="filterRecommendation">
                <option value="">Recommendation</option>
                <option value="Highest">Highest</option>
                <option value="Lowest">Lowest</option>
            </select>

            <select name="post-filter-address" id="filterAddress">
                <option value="">All Address</option>
                <option value="Anonang Norte, Bogo City, Cebu">Anonang Norte, Bogo City, Cebu</option>
                <option value="Anonang Sur, Bogo City, Cebu">Anonang Sur, Bogo City, Cebu</option>
                <option value="Banban, Bogo City, Cebu">Banban, Bogo City, Cebu</option>
                <option value="Binabag, Bogo City, Cebu">Binabag, Bogo City, Cebu</option>
                <option value="Bungtod, Bogo City, Cebu">Bungtod, Bogo City, Cebu</option>
                <option value="Carbon, Bogo City, Cebu">Carbon, Bogo City, Cebu</option>
                <option value="Cayang, Bogo City, Cebu">Cayang, Bogo City, Cebu</option>
                <option value="Cogon, Bogo City, Cebu">Cogon, Bogo City, Cebu</option>
                <option value="Dakit, Bogo City, Cebu">Dakit, Bogo City, Cebu</option>
                <option value="Don Pedro, Bogo City, Cebu">Don Pedro, Bogo City, Cebu</option>
                <option value="Gairan, Bogo City, Cebu">Gairan, Bogo City, Cebu</option>
                <option value="Guadalupe, Bogo City, Cebu">Guadalupe, Bogo City, Cebu</option>
                <option value="La Paz, Bogo City, Cebu">La Paz, Bogo City, Cebu</option>
                <option value="LPC, Bogo City, Cebu">LPC, Bogo City, Cebu</option>
                <option value="Libertad, Bogo City, Cebu">Libertad, Bogo City, Cebu</option>
                <option value="Lourdes, Bogo City, Cebu">Lourdes, Bogo City, Cebu</option>
                <option value="Malingin, Bogo City, Cebu">Malingin, Bogo City, Cebu</option>
                <option value="Marangog, Bogo City, Cebu">Marangog, Bogo City, Cebu</option>
                <option value="Nailon, Bogo City, Cebu">Nailon, Bogo City, Cebu</option>
                <option value="Odlot, Bogo City, Cebu">Odlot, Bogo City, Cebu</option>
                <option value="Pandan, Bogo City, Cebu">Pandan, Bogo City, Cebu</option>
                <option value="Polambato, Bogo City, Cebu">Polambato, Bogo City, Cebu</option>
                <option value="Sambag, Bogo City, Cebu">Sambag, Bogo City, Cebu</option>
                <option value="San Vicente, Bogo City, Cebu">San Vicente, Bogo City, Cebu</option>
                <option value="Siocon, Bogo City, Cebu">Siocon, Bogo City, Cebu</option>
                <option value="Sto. Nino, Bogo City, Cebu">Sto. Nino, Bogo City, Cebu</option>
                <option value="Sto. Rosario, Bogo City, Cebu">Sto. Rosario, Bogo City, Cebu</option>
                <option value="Sudlonon, Bogo City, Cebu">Sudlonon, Bogo City, Cebu</option>
                <option value="Taytayan, Bogo City, Cebu">Taytayan, Bogo City, Cebu</option>
            </select>

            <select name="post-filter-gender" id="filterGender">
                <option value="">All Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
    </div>



    
    

        
    <script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="../js/client-browse-freelancers.js"></script>
    <script src="../js/system-notifications.js"></script>
    <script src="../js/system-user-settings.js"></script>
    <script src="../js/system-sidebar.js"></script>
    <script src="../js/system-booking-request.js"></script>
    <script src="../js/system-check-restriction.js"></script>
</body>
</html>