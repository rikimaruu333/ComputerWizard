document.addEventListener("DOMContentLoaded", function () {
    // Initialize Socket.IO client
    const socket = io('http://localhost:8080');

    // Fetch total counts from PHP script
    $.ajax({
        url: 'admin-fetch-total-registered-users.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            $('#totalRegisteredClients').html(`TOTAL CLIENTS REGISTERED: <strong>${response.totalClients}</strong>`);
            $('#totalRegisteredFreelancers').html(`TOTAL FREELANCERS REGISTERED: <strong>${response.totalFreelancers}</strong>`);
            $('#totalRestrictedUsers').html(`TOTAL USERS RESTRICTED: <strong class="text-danger">${response.totalRestrictedUsers}</strong>`);
        },
        error: function (xhr, status, error) {
            console.error('Error fetching and populating counts:', error);
        }
    });

    // Elements for notification system
    const notificationBell = document.getElementById('openReportsListModalBtn');
    const notificationDropdown = document.getElementById('reportNotificationDropdown');
    const notificationList = document.getElementById('reportNotificationList');
    const notificationCount = document.getElementById('reportNotificationCount');

    // Modal elements
    const reportDetailsModal = document.getElementById('reportDetailsModal');
    const modalReportContent = document.getElementById('modalReportDetailsContent');
    const modalReportReason = document.getElementById('modalReportDetailsReason');
    const modalReportDate = document.getElementById('modalReportDetailsDate');
    const closeModalBtn = document.getElementById('closeReportDetailsModalBtn');

    const reporterProfileImage = document.querySelector('.report-details-user-profiles ul li:nth-child(1) img');
    const violatorProfileImage = document.querySelector('.report-details-user-profiles ul li:nth-child(2) img');

    // Fetch notifications on page load
    fetchReports(); // Fetch reports to display on load

    // Listen for notifications
    socket.on('notification', (data) => {
        if (data.action === 'newReport') {
            // If a new report is received, display it
            displayNewReport(data.report);
            incrementNotificationCount();
        } else if (data.action === 'reportsViewed') {
            // If reports are marked as viewed, clear the notification count
            clearNotificationCount();
        }
    });

    // Function to emit unrestriction status using Fetch API
    function emitUnrestrictionStatus() {
        const url = 'system-auto-unrestrict-users.php'; // Update to your PHP script URL

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            data.forEach(user => {
                if (user.isUnrestricted) {
                    toastr.info(`User with email ${user.email} has been unrestricted.`);
                    
                    // Send email notification
                    sendUnrestrictionEmail(user.email);
                }
            });
        })
        .catch((error) => {
            console.error('Error emitting unrestriction status:', error);
        });
    }

    // Function to send unrestriction email
    function sendUnrestrictionEmail(userEmail) {
        const dateUnrestricted = new Date().toLocaleString(); // Get current date and time for the email

        // Construct the message using template literals
        var message = `
            We are pleased to inform you that the restriction on your account has been lifted, and you now have full access to your account as of ${dateUnrestricted}. We appreciate your understanding during the restriction period and hope that you continue to contribute positively to our community.
            
            Please review our community guidelines to ensure a safe and respectful environment for all users. Should you have any further questions or need assistance, feel free to reach out to our support team, available through the footer of the landing page.
            
            Summary: Your account has been unrestricted as of ${dateUnrestricted}.
        `;

        var templateParams = {
            from_name: "GigHub Team",
            reply_to: userEmail,
            message: message
        };

        console.log("Sending unrestriction email with parameters:", templateParams); // Log parameters for debugging

        emailjs.send('service_7edsjxk', 'template_zm3dmjk', templateParams) // Use the first EmailJS service
            .then(function(response) {
                toastr.info('Unrestriction notification sent to email: ' + userEmail);
            }, function(error) {
                console.error('Error sending unrestriction email:', error);
            });
    }

    // Call to emit unrestriction status (you may call this after processing users)
    emitUnrestrictionStatus(); // Call this function without email input




    // Toggle notification dropdown visibility
    notificationBell.addEventListener('click', function () {
        if (notificationDropdown.style.display === 'block') {
            notificationDropdown.style.display = 'none'; // Hide the dropdown
            notificationBell.classList.remove('active');
        } else {
            notificationDropdown.style.display = 'block'; // Show the dropdown
            notificationBell.classList.add('active');
            markReportsAsViewed(); // Mark the reports as viewed when dropdown is opened
        }
    });

    // Function to fetch reports from the server
    function fetchReports() {
        $.ajax({
            url: 'system-report-submission.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Clear existing reports in the dropdown
                    notificationList.innerHTML = '';

                    // Display reports and update the notification count
                    displayReports(response.reports);
                    updateNotificationCount(response.unviewedCount); // Use the unviewed count from the response
                } else {
                    console.error('Failed to fetch reports:', response.error);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching reports:', error);
            }
        });
    }

    // Function to mark reports as viewed
    function markReportsAsViewed() {
        $.ajax({
            url: 'system-report-submission.php',
            type: 'POST',
            data: { markAsViewed: '1' },
            dataType: 'json',
            success: function (response) {
                if (!response.success) {
                    console.error('Failed to mark reports as viewed:', response.error);
                } else {
                    clearNotificationCount(); // Clear the notification count on success
                }
            },
            error: function (xhr, status, error) {
                console.error('Error marking reports as viewed:', error);
            }
        });
    }

    // Function to display reports in the dropdown
    function displayReports(reports) {
        reports.forEach(report => {
            const reportItem = document.createElement('div');
            reportItem.classList.add('notification-item');
            if (report.report_notification === 0) {
                reportItem.classList.add('unread');
            }
            reportItem.innerHTML = `
                <p><strong>Report:</strong> ${report.report_content}</p>
                <p><strong>Reason:</strong> ${report.truncated_reason}</p>
                <p><strong>Date:</strong> ${new Date(report.report_date).toLocaleString()}</p>
            `;

            // Attach click event to open modal with report details
            reportItem.addEventListener('click', function () {
                openReportModal(report);
            });

            notificationList.appendChild(reportItem);
        });
    }

    // Function to display a new report
    function displayNewReport(report) {
        const reportItem = document.createElement('div');
        reportItem.classList.add('notification-item', 'unread'); // Immediately add 'unread' class
        reportItem.innerHTML = `
            <p><strong>New Report:</strong> ${report.report_content}</p>
            <p><strong>Reason:</strong> ${report.report_reason ? report.report_reason : 'No reason provided'}</p> 
            <p><strong>Date:</strong> ${new Date(report.report_date).toLocaleString()}</p>
        `;

        // Attach click event to open modal with report details
        reportItem.addEventListener('click', function () {
            openReportModal(report); // Ensure this has all details including report_reason
        });

        notificationList.prepend(reportItem); // Prepend to show the newest report at the top
    }

    // Function to open the modal and populate it with report details
    function openReportModal(report) {
        modalReportContent.textContent = report.report_content;
        modalReportReason.textContent = report.report_reason ? report.report_reason : 'No reason provided'; // Handle undefined

        modalReportDate.textContent = new Date(report.report_date).toLocaleString();
        
        // Populate profile images with titles
        reporterProfileImage.src = report.reporter_profile_image_url ? report.reporter_profile_image_url : '../images/user.jpg';
        reporterProfileImage.title = `${report.reporter_firstname} ${report.reporter_lastname} (${report.reporter_usertype}). Click to view profile.`;

        violatorProfileImage.src = report.reported_profile_image_url ? report.reported_profile_image_url : '../images/user.jpg';
        violatorProfileImage.title = `${report.reported_firstname} ${report.reported_lastname} (${report.reported_usertype}). Click to view profile.`;

        // Clear any previous event listeners to avoid duplicate bindings
        reporterProfileImage.onclick = null;
        violatorProfileImage.onclick = null;

        // Attach click event listeners to the profile images for redirection
        reporterProfileImage.addEventListener('click', function() {
            if (report.reporter_usertype === 'Client') {
                window.location.href = `system-view-client-profile.php?client_id=${report.reporter_user_id}`;
            } else if (report.reporter_usertype === 'Freelancer') {
                window.location.href = `system-view-freelancer-profile.php?freelancer_id=${report.reporter_user_id}`;
            }
        });

        violatorProfileImage.addEventListener('click', function() {
            if (report.reported_usertype === 'Client') {
                window.location.href = `system-view-client-profile.php?client_id=${report.reported_user_id}`;
            } else if (report.reported_usertype === 'Freelancer') {
                window.location.href = `system-view-freelancer-profile.php?freelancer_id=${report.reported_user_id}`;
            }
        });

        // Show the modal
        reportDetailsModal.style.display = 'block';
    }

    // Function to close the modal
    closeModalBtn.addEventListener('click', function () {
        reportDetailsModal.style.display = 'none';
        notificationBell.classList.remove('active');
    });

    // Function to increment the notification count
    function incrementNotificationCount() {
        let currentCount = parseInt(notificationCount.textContent) || 0;
        currentCount += 1; // Increment by 1 for the new report
        updateNotificationCount(currentCount); // Update the displayed count
    }
    // Function to update the notification count
    function updateNotificationCount(count) {
        notificationCount.textContent = count !== undefined ? count : '0';
        notificationCount.style.display = count > 0 ? 'block' : 'none'; // Hide if count is 0
    }

    // Function to clear the notification count
    function clearNotificationCount() {
        updateNotificationCount(0);
    }
});
