<?php
require "formfunctions.php";

$response = array();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $response = userlogin($_POST);

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
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
    <link rel="stylesheet" href="../css/globalstyle.css">
    <link rel="stylesheet" href="../css/overview.css">
</head>
<body>
    <?php include("signinupmodal.php"); ?>
    <div class="daddycontainer">
        <?php include("header.php"); ?>
    </div>
     
    <div class="container">

        <div class="slide">

            
            <div class="item" style="background-image: url(../images/freelancer6.png);">
                <div class="content">
                    <div class="name">Schedule Setting</div>
                    <div class="des">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ab, eum!</div>
                    <button>See More</button>
                </div>
            </div>
            <div class="item" style="background-image: url(../images/client1.png);">
                <div class="content">
                    <div class="name">Client Dashboard</div>
                    <div class="des">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ab, eum!</div>
                    <button>See More</button>
                </div>
            </div>
            <div class="item" style="background-image: url(../images/client2.png);">
                <div class="content">
                    <div class="name">Create Job Post</div>
                    <div class="des">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ab, eum!</div>
                    <button>See More</button>
                </div>
            </div>
            <div class="item" style="background-image: url(../images/client3.png);">
                <div class="content">
                    <div class="name">Browse Freelancers</div>
                    <div class="des">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ab, eum!</div>
                    <button>See More</button>
                </div>
            </div>
            <div class="item" style="background-image: url(../images/client4.png);">
                <div class="content">
                    <div class="name">Messaging</div>
                    <div class="des">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ab, eum!</div>
                    <button>See More</button>
                </div>
            </div>
            <div class="item" style="background-image: url(../images/freelancer1.png);">
                <div class="content">
                    <div class="name">Freelancer Dashboard</div>
                    <div class="des">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ab, eum!</div>
                    <button>See More</button>
                </div>
            </div>
            <div class="item" style="background-image: url(../images/freelancer2.png);">
                <div class="content">
                    <div class="name">Job Browsing</div>
                    <div class="des">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ab, eum!</div>
                    <button>See More</button>
                </div>
            </div>
            <div class="item" style="background-image: url(../images/freelancer4.png);">
                <div class="content">
                    <div class="name">Settings</div>
                    <div class="des">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ab, eum!</div>
                    <button>See More</button>
                </div>
            </div>
            <div class="item" style="background-image: url(../images/freelancer5.png);">
                <div class="content">
                    <div class="name">Service Listing</div>
                    <div class="des">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ab, eum!</div>
                    <button>See More</button>
                </div>
            </div>
            

        </div>

        <div class="button">
            <button class="prev"><i class="fa-solid fa-arrow-left"></i></button>
            <button class="next"><i class="fa-solid fa-arrow-right"></i></button>
        </div>

    </div>

    
    
    <script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
        
        (function(){
            emailjs.init({
                publicKey: "XnzWkndgRn2h6N10o",
            });
        })();
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/overview.js"></script>
    <script src="../js/signinupmodal.js"></script>
    <script src="../js/registrationmodal.js"></script>
</body>
</html>