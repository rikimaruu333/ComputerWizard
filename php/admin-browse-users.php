<?php
    require "formfunctions.php";
    usercheck_login();
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
    <link rel="stylesheet" href="../css/admin-browse-users.css">  
</head>
<body>
<?php include "systemadminheader.php";?>
<?php include "systemadminsidebar.php";?>
<?php include "system-report-details.php";?>    
<?php include "system-restrict-user.php";?>

<div class="admin-browse-users">
        <div class="search-bar-container">
            <div class="search">
                <span class="text">Search a user here...</span>
                <input type="text" placeholder="Enter name to search...">
                <button><i class="fas fa-search"></i></button>
            </div>
        </div>
        <div class="user-list-container">
        </div>
    </div>
        
    <div class="user-list-filter-container">
        <div class="filter-user-list-header">
            <h1>FILTER</h1>
        </div>
        
        <div class="filter-selection">
            <select name="user-filter-restriction" id="filterRestriction">
                <option value="">All Users</option>
                <option value="restricted">Restricted</option>
                <option value="unrestricted">Unrestricted</option>
            </select>

            <select name="user-filter-usertype" id="filterUsertype">
                <option value="">All Usertype</option>
                <option value="Client">Client</option>
                <option value="Freelancer">Freelancer</option>
            </select>

            <select name="user-filter-address" id="filterAddress">
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

            <select name="user-filter-gender" id="filterGender">
                <option value="">All Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
    </div>

    <script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
    </script>
    <script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
    <script>
        const socket = io('http://localhost:8080');

        // Initialize EmailJS with your public key
        window.addEventListener('load', () => {
            emailjs.init("XnzWkndgRn2h6N10o"); // Your EmailJS user ID
        });

        socket.on('unrestriction', (data) => {
            console.log('Received unrestriction update:', data);
            if (!data.isUnrestricted) {
                const userEmail = data.email;
                const dateUnrestricted = new Date().toLocaleString(); // Replace with actual date from server if needed

                // Call the function to send the email
                sendUnrestrictionNotification(userEmail, dateUnrestricted);

                alert('A user has been unrestricted!');
            }
        });

        function sendUnrestrictionNotification(userEmail, dateUnrestricted) {
            if (!userEmail || userEmail.trim() === "") {
                console.error('Error: Recipient email is empty.');
                return; // Exit the function if the email is empty
            }

            const message = `
                We are pleased to inform you that the restriction on your account has been lifted, and you now have full access to your account as of ${dateUnrestricted}. We appreciate your understanding during the restriction period and hope that you continue to contribute positively to our community.
                
                Please review our community guidelines to ensure a safe and respectful environment for all users. Should you have any further questions or need assistance, feel free to reach out to our support team, available through the footer of the landing page.
                
                Summary: Your account has been unrestricted as of ${dateUnrestricted}.
            `;

            const templateParams = {
                from_name: "GigHub Team",
                reply_to: userEmail,
                message: message,
            };

            emailjs.send("service_7edsjxk", "template_zm3dmjk", templateParams)
                .then((response) => {
                    console.log('Unrestriction notification sent to email:', userEmail);
                }, (error) => {
                    console.error('Error sending unrestriction email:', error);
                });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="../js/admin-header.js"></script>
    <script src="../js/admin-browse-users.js"></script>
    <script src="../js/system-sidebar.js"></script>
    <script src="../js/system-restrict-user.js"></script>
</body>
</html>