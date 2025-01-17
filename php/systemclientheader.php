<?php
$userId = $_SESSION['USER']->id;
?>

<link rel="stylesheet" href="../css/system-header.css">
        <div class="headcontainer" id="head" data-user-id="<?php echo htmlspecialchars($userId); ?>">
            <div class="logo">
                <img src="../images/gighub.png" alt="">
            </div>
            <ul>
                <li id="bookingRequestListBtn">Booking Requests</li>
                <li id="endedTransactionListBtn">Transaction History</li>
            </ul>
            <div class="header-buttons">
                <div class="notification-bell-container">
                    <i class="bx bxs-bell" id="notificationBell"></i>
                    <span id="notificationCount" class="notification-count"></span> 
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h4>Notifications</h4>
                        </div>
                        <div class="notification-list" id="notificationList">
                            
                        </div>
                    </div>
                </div>
                <i class="bx bxs-cog" id="openUserSettings"></i>
                <a href="" id="logout-btn"><i class="bx bxs-log-out"></i></a>
            </div>
        </div>

        <?php include "system-user-settings.php";?>
        
        <script type="text/javascript"
            src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
        </script>
        <script src="../js/system-auto-restrict-user.js"></script>
        
