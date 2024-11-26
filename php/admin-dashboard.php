<?php
    require "formfunctions.php";
    usercheck_login();
    
    if($_SESSION['USER']->usertype !== 'Admin' && $_SESSION['USER']->usertype !== 'Freelancer') header("Location: client-dashboard.php");
    if($_SESSION['USER']->usertype !== 'Admin' && $_SESSION['USER']->usertype !== 'Client') header("Location: freelancer-dashboard.php");
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
    <link rel="stylesheet" href="../css/admin-dashboard.css">  
</head>
<body>
<?php include "systemadminheader.php";?>
<?php include "systemadminsidebar.php";?>
<?php include "system-report-details.php";?>
<?php include "system-transaction-details.php";?>

        <div class="admin-dashboard">
            <div class="chart-container" id="chartContainer">
                
                <?php
                $currentMonth = date('n'); 
                $currentYear = date('Y');  
                ?>

                <div class="select-container">
                    <select id="selectMonth" class="custom-select">
                        <option value="1" <?= ($currentMonth == 1) ? 'selected' : ''; ?>>January</option>
                        <option value="2" <?= ($currentMonth == 2) ? 'selected' : ''; ?>>February</option>
                        <option value="3" <?= ($currentMonth == 3) ? 'selected' : ''; ?>>March</option>
                        <option value="4" <?= ($currentMonth == 4) ? 'selected' : ''; ?>>April</option>
                        <option value="5" <?= ($currentMonth == 5) ? 'selected' : ''; ?>>May</option>
                        <option value="6" <?= ($currentMonth == 6) ? 'selected' : ''; ?>>June</option>
                        <option value="7" <?= ($currentMonth == 7) ? 'selected' : ''; ?>>July</option>
                        <option value="8" <?= ($currentMonth == 8) ? 'selected' : ''; ?>>August</option>
                        <option value="9" <?= ($currentMonth == 9) ? 'selected' : ''; ?>>September</option>
                        <option value="10" <?= ($currentMonth == 10) ? 'selected' : ''; ?>>October</option>
                        <option value="11" <?= ($currentMonth == 11) ? 'selected' : ''; ?>>November</option>
                        <option value="12" <?= ($currentMonth == 12) ? 'selected' : ''; ?>>December</option>
                    </select>
                    
                    <select id="selectYear" class="custom-select">
                        <option value="2020" <?= ($currentYear == 2020) ? 'selected' : ''; ?>>2020</option>
                        <option value="2021" <?= ($currentYear == 2021) ? 'selected' : ''; ?>>2021</option>
                        <option value="2022" <?= ($currentYear == 2022) ? 'selected' : ''; ?>>2022</option>
                        <option value="2023" <?= ($currentYear == 2023) ? 'selected' : ''; ?>>2023</option>
                        <option value="2024" <?= ($currentYear == 2024) ? 'selected' : ''; ?>>2024</option>
                    </select>
                    <i class="bx bxs-printer" id="print-button" title="Print PDF"></i>
                </div>


                <div class="analysis-container">
                    <canvas id="myChart"></canvas>
                </div>

            </div>
            
            <div class="transactions-container">
                <div class="transactions-container-toggle">
                    <div class="toggle-ongoing"><p>ONGOING TRANSACTIONS</p></div>
                    <div class="toggle-ended"><p>ENDED TRANSACTIONS</p></div>
                </div>
            </div>

            

            <div class="ongoing-transaction-container">
                <div class="transaction-details-container">
                    <div class="transaction-details">
                        <div class="transaction-details-info" id="ongoingTransactionContainer">

                            <!-- <div class="transaction-details-user-info">
                                <div class="transaction-profile">
                                    <img src="../images/userpic1.jpg" alt="">
                                    <div class="details">
                                        <h3>Ricky Monsales</h3>
                                        <p>Freelancer</p>
                                    </div>
                                </div>
                                <div class="transaction-line-img-container">
                                    <img class="line-img" src="../images/pulse.png" alt="">
                                </div>
                                <div class="transaction-profile">
                                    <img src="../images/userpic1.jpg" alt="">
                                    <div class="details">
                                        <h3>Ricky Monsales</h3>
                                        <p>Freelancer</p>
                                    </div>
                                </div>
                                <div class="transaction-buttons">
                                    <div class="info-request-button-container">
                                        <i class="bx bx-info-circle info-request-button" title="View Transaction Details"></i>
                                    </div>
                                </div>
                            </div> -->
                            

                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="ended-transaction-container" style="display: none;">
                <div class="transaction-details-container">
                    <div class="transaction-details">
                        <div class="transaction-details-info" id="endedTransactionContainer">

                            <!-- <div class="transaction-details-user-info">
                                <div class="transaction-profile">
                                    <img src="../images/userpic1.jpg" alt="">
                                    <div class="details">
                                        <h3>Ricky Monsales</h3>
                                        <p>Freelancer</p>
                                    </div>
                                </div>
                                <div class="transaction-line-img-container">
                                    <img class="line-img" src="../images/pulse.png" alt="">
                                </div>
                                <div class="transaction-profile">
                                    <img src="../images/userpic1.jpg" alt="">
                                    <div class="details">
                                        <h3>Ricky Monsales</h3>
                                        <p>Freelancer</p>
                                    </div>
                                </div>
                                <div class="transaction-buttons">
                                    <div class="info-request-button-container">
                                        <i class="bx bx-info-circle info-request-button" title="View Transaction Details"></i>
                                    </div>
                                </div>
                            </div> -->
                            

                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="freelancer-registration-container">
            <div class="freelancer-registration-text">
                <h4>Freelancer registration requests</h4>
                <p>These freelancers need your approval in order to fully register their account. Kindly check their registration info. Have a nice day!</p>
             
            </div>
            <div class="freelancer-registration-request-list">

            
            </div>
        </div>



        <div id="freelancerRegistrationModal" class="freelancer-registration-modal">
            <div class="freelancer-registration-modal-content">
                <div class="freelancer-registration-modal-header">
                    <h3>Freelancer registration details</h3>
                    <span class="close-freelancer-registration-modal" id="closeFreelancerRegistrationModal">&times;</span>
                </div>
                <div class="freelancer-registration-details-container">
                    <div class="freelancer-registration-details">
                        <div class="freelancer-request-info">
                            <p>First Name: <span id="registrationModalFirstName"></span></p>
                            <p>Last Name: <span id="registrationModalLastName"></span></p>
                            <p>Age: <span id="registrationModalAge"></span></p>
                            <p>Address: <span id="registrationModalAddress"></span></p>
                            <p>Phone Number: <span id="registrationModalPhone"></span></p>
                            <p>Email: <span id="registrationModalEmail"></span></p>
                            <p>Gender: <span id="registrationModalGender"></span></p>
                        </div>
                        <div class="freelancer-request-img-buttons">
                            <div class="freelancer-request-img">
                                <img src="" alt="Profile Image" id="registrationModalProfileImage">
                            </div>
                            <div class="freelancer-request-buttons">
                                <div class="button-container"  id="freelancerRegistrationRejectBtn" >
                                    <i class="bx bxs-user-x reject-button"></i>
                                    <span>Reject</span>
                                </div>
                                <div class="button-container" id="frelancerRegistrationValidationBtn">
                                    <i class="bx bxs-user-check approve-button"></i>
                                    <span>Approve</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="freelancer-request-valid-id">
                        <div class="valid-id-details">
                            <h3>Presented Valid ID</h3>
                            <p>ID type: <span id="registrationModalValidIDType"></span></p>
                            <p>Date submitted: <span id="registrationModalRegistrationDate"></span></p>
                        </div>
                        <img src="" alt="Valid ID" id="registrationModalValidID" style="width: 100%;">
                    </div>
                    <!-- Modal Structure -->
                    <div id="validateIdModal" class="validate-id-modal">
                        <div class="validate-id-modal-content">
                            <div class="validate-id-modal-content-header">
                                <h3>Validate ID</h3>
                                <span class="close-btn">&times;</span>
                            </div>
                            <form id="validateIdForm">
                                <input type="text" id="valid_id_type" name="valid_id_type" placeholder="No valid ID submitted" disabled>
                                <input type="text" id="fullname" name="fullname" placeholder="Enter the ID's full name here..." required>
                                <button type="submit" id="freelancerRegistrationApproveBtn">Finalize Approval</button>
                            </form>
                        </div>
                    </div>


                </div>
            </div>
        </div>


    <script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
    </script>
    <script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>

    <script src="../js/admin-dashboard.js"></script>
    <script src="../js/admin-header.js"></script>
    <script src="../js/system-sidebar.js"></script>
    <script src="../js/system-notifications.js"></script>

</body>
</html>