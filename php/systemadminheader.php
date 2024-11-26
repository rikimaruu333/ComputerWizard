<?php
$userId = $_SESSION['USER']->id;
?>

<link rel="stylesheet" href="../css/system-header.css">
        <div class="headcontainer" id="head" data-user-id="<?php echo htmlspecialchars($userId); ?>">
            <div class="logo">
                <img src="../images/gighub.png" alt="">
            </div>
            <ul>
                <li id="totalRegisteredClients"></li>
                <li id="totalRegisteredFreelancers"></li>
                <li id="totalRestrictedUsers"></li>
            </ul>
            <div class="header-buttons">
                <div class="notification-bell-container">
                    <i class="bx bxs-notification" id="openReportsListModalBtn"></i>
                    <span id="reportNotificationCount" class="notification-count"></span> 
                    <div class="notification-dropdown" id="reportNotificationDropdown">
                        <div class="notification-header">
                            <h4>Reports</h4>
                        </div>
                        <div class="notification-list" id="reportNotificationList">
                            
                        </div>
                    </div>
                </div>
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
                <a href="" id="logout-btn"><i class="bx bxs-log-out"></i></a>
            </div>
        </div>

        
