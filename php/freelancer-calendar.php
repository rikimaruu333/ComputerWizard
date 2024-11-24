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
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shepherd.js@13.0.0/dist/css/shepherd.css"/>

    <link rel="stylesheet" href="../toastr.min.css">
    <link rel="stylesheet" href="../css/globalstyle.css">
    <link rel="stylesheet" href="../css/freelancer-calendar.css">
</head>
<body>
<?php include "systemfreelancerheader.php";?>
<?php include "systemfreelancersidebar.php";?>
<?php include "system-booking-request.php";?>
<?php include "system-transaction-details.php";?>
        
    <div class="freelancer-calendar">
        <div class="calendar" id="calendar"></div>
    </div>
    <div class="freelancer-calendar-list">
        <i class="bx bxs-help-circle calendar-tour-button" id="startTourButton" title="Click to start a tour on the calendar."></i>
        <div class="messaging-default-userinfo" id="default-userinfo">
            <div class="default-userinfo-details">
                <img src="../images/gighublogo.png" alt="" id="default-profile-pic">
                <h4 class="user-name" id="default-user-name">GIGHUB</h4>
            </div>
            <div class="default-userinfo-data">
                <p id="default-user-message">
                    Managing your schedule is crucial for effective collaboration, and <span>GigHub's</span> FullCalendar feature is designed to make that <u>intuitive</u>, <u>flexible</u>, and <u>efficient</u>. <br> <br> Whether you're a freelancer planning your tasks or a business coordinating events, our calendar system <span>empowers you to stay organized and informed</span>, ensuring that every important date is just a click away.
                </p>
            </div>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div class="event-detail-modal" id="eventModal">
        <div class="event-detail-modal-content">
            <div class="event-detail-modal-header">
                <h3>Event</h3>
                <span class="event-detail-close-btn" id="closeEventModal">&times;</span>
            </div>
            <div class="event-details">
                <input type="hidden" id="event_id" value="">
                <p><strong>Title:</strong> <span id="calendarModalEventTitle"></span></p>
                <p><strong>Description:</strong> <span id="calendarModalEventDescription"></span></p>
                <p><strong>Start:</strong> <span id="calendarModalEventStart"></span></p>
                <p><strong>End:</strong> <span id="calendarModalEventEnd"></span></p>
                <button id="deleteEventButton">Delete Event</button>
            </div>
        </div>
    </div>


    <!-- Add Event Modal -->
    <div id="addEventModal" class="add-calendar-modal">
        <div class="add-calendar-modal-content">
            <div class="add-calendar-modal-header">
                <h3>Add Event</h3>
                <span class="add-calendar-close-btn" id="closeAddEventModal">&times;</span>
            </div>
            <div class="add-calendar-modal-form">
                <label for="eventTitle">Title:</label>
                <input type="text" id="eventTitle" required>

                <label for="eventDescription">Description:</label>
                <textarea id="eventDescription"></textarea>
                <div class="add-calendar-modal-form-dates">
                    <div class="start-time">
                        <label for="eventStartTime">Start Time:</label>
                        <input type="time" id="eventStartTime" value="00:00" required>
                    </div>
                    <div class="end-time">
                        <label for="eventEndTime">End Time:</label>
                        <input type="time" id="eventEndTime" value="23:59" required>
                    </div>
                </div>
                <div class="add-calendar-modal-form-dates">
                    <div class="start-date">
                        <input type="date" id="eventStartDate" required>
                    </div>
                    <div class="end-date">
                        <input type="date" id="eventEndDate">
                    </div>
                </div>

                <button id="saveEventButton">Save Event</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.js"></script>
    <script type="module" src="../js/freelancer-calendar-guide.js"></script>
    <script src="../js/freelancer-calendar.js"></script>
    <script src="../js/freelancer-service.js"></script>
    <script src="../js/freelancer-schedule.js"></script>
    <script src="../js/system-notifications.js"></script>
    <script src="../js/system-user-settings.js"></script>
    <script src="../js/system-sidebar.js"></script>
    <script src="../js/system-booking-request.js"></script>
    <script src="../js/system-check-restriction.js"></script>

</body>
</html>
