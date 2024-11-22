

// Initialize EmailJS with the first public key
window.addEventListener('load', () => {
    emailjs.init("XnzWkndgRn2h6N10o"); // First public key
});

$(document).ready(function () {
    // Handle showing the Restrict User Modal
    $(document).on('click', '.restrict-button', function () {
        var userId = $(this).closest('.user-details-container').find('.view-profile-btn').data('user-id'); // Get user ID from the profile button data attribute
        
        $('#restrictUserId').val(userId); // Set the user ID in the hidden input
        $('#restrictModal').css('display', 'block'); // Show the modal
    });

    // Handle closing the Restrict User Modal
    $('#closeRestrictModalBtn').on('click', function () {
        $('#restrictModal').css('display', 'none'); // Hide the modal
    });

    // Handle showing the Report List Modal
    $(document).on('click', '#viewReportListBtn', function () {
        var userId = $('#restrictUserId').val();

        // Fetch user reports using AJAX
        $.ajax({
            url: 'system-fetch-user-reports.php',  // URL to fetch reports
            type: 'POST',
            data: { userId: userId },
            dataType: 'json',
            success: function (response) {
                populateReportTable(response);  // Populate the table with fetched reports
                $('#reportListModal').css('display', 'block'); // Show the modal
            },
            error: function (xhr, status, error) {
                console.error('Error fetching reports:', error);
                $('#reportTableBody').html('<tr><td colspan="2">No reports found.</td>');  // Show no reports found message if there's an error
            }
        });
    });

    // Handle closing the Report List Modal
    $('#closeReportListModalBtn').on('click', function () {
        $('#reportListModal').css('display', 'none'); // Hide the modal
    });

    // Optional: Close modal when clicking outside the modal content
    $(window).on('click', function (event) {
        if (event.target.id === 'reportListModal') {
            $('#reportListModal').css('display', 'none');
        }
    });

    // Function to populate the report table with actual reports
    function populateReportTable(reports) {
        var reportTableBody = $('#reportTableBody');
        reportTableBody.empty();  // Clear the current content

        if (Array.isArray(reports) && reports.length > 0) {
            reports.forEach(function (report) {
                var reportRow = `
                    <tr>
                        <td>${report.report_reason}</td>
                        <td>${report.report_date}</td>
                    </tr>
                `;
                reportTableBody.append(reportRow);  // Append each report row to the table
            });
        } else {
            // If no reports are found, display a message
            reportTableBody.html('<tr><td colspan="2">No reports found.</td></tr>');
        }
    }

    $('#restrictUserForm').on('submit', function (e) {
        e.preventDefault(); // Prevent form submission

        var userId = $('#restrictUserId').val();
        var restrictReason = $('#restrictReason').val();
        var unrestrictDate = $('#unrestrictDate').val();
        var adminNotes = $('#adminNotes').val();

        // Make an AJAX request to restrict the user in the backend
        $.ajax({
            url: 'system-restrict-process.php', // Your backend script to restrict the user
            type: 'POST',
            dataType: 'json', // Make sure the response is treated as JSON
            data: {
                userId: userId,
                restrictReason: restrictReason,
                unrestrictDate: unrestrictDate,
                adminNotes: adminNotes
            },
            success: function (response) {
                var userEmail = response.email; // Get user email from response
                sendRestrictionNotification(userEmail, restrictReason, unrestrictDate);
                $('#restrictModal').css('display', 'none'); // Hide the modal
                toastr.info('User restricted.');
            },
            error: function (xhr, status, error) {
                console.error('Error restricting user:', error);
            }
        });
    });

    // Function to send restriction notification
    function sendRestrictionNotification(userEmail, restrictReason, unrestrictDate) {
        if (!userEmail || userEmail.trim() === "") {
            console.error('Error: Recipient email is empty.');
            return; // Exit the function if the email is empty
        }
    
        // Construct the message using template literals
        var message = `We regret to inform you that your account has been temporarily restricted until ${unrestrictDate} due to violations of our community guidelines. This action was taken to maintain a safe and respectful environment for all users. We encourage you to review our guidelines to understand the reasons for this restriction. If you believe this decision was made in error or if you have any questions, please feel free to contact our support team located at the footer of the landing page. We appreciate your understanding and cooperation in this matter.
                
        Summary: Your account has been restricted until ${unrestrictDate} due to ${restrictReason}.
                
        ${$('#adminNotes').val()}`;

        var templateParams = {
            from_name: "GigHub Team",
            reply_to: userEmail,
            message: message
        };

    
        console.log("Sending email with parameters:", templateParams); // Log parameters for debugging
    
        emailjs.send('service_7edsjxk', 'template_zm3dmjk', templateParams) // Use the first EmailJS service
            .then(function (response) {
                toastr.info('Restriction notification sent to email: ' + userEmail);
                setTimeout(function () {
                    window.location.reload();
                }, 2000);
            }, function (error) {
                console.error('Error sending email:', error);
            });
    }
    

    // Function to populate the unrestrict modal
    function populateUnrestrictModal(userId) {
        $.ajax({
            url: 'system-fetch-restriction-info.php',  // Backend script to fetch unrestrict info
            type: 'POST',
            dataType: 'json',
            data: { userId: userId },
            success: function (response) {
                if (response && response.length > 0) {
                    // Assuming the first entry in the array contains the needed information
                    $('#unrestrictionDate').text(response[0].unrestrict_date); // Set the unrestriction date
                    // Display modal
                    $('#unrestrictModal').css('display', 'block'); // Show the modal
                } else {
                    console.error('No unrestrict data found for this user.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching unrestrict info:', error);
            }
        });
    }
    

    // Handle showing the Unrestrict User Modal
    $(document).on('click', '.unrestrict-button', function () {
        var userId = $(this).closest('.user-details-container').find('.view-profile-btn').data('user-id'); // Get user ID

        $('#unrestrictUserId').val(userId); // Set the user ID in hidden input
        populateUnrestrictModal(userId); // Call the function to populate and show the unrestrict modal
    });

    // Handle closing the Unrestrict User Modal
    $('#closeUnrestrictModalBtn').on('click', function () {
        $('#unrestrictModal').css('display', 'none'); // Hide the modal
    });

    $('#unrestrictUserBtn').on('click', function() {
        var userId = $('#unrestrictUserId').val();
        
        $.ajax({
            url: 'system-unrestrict-process.php', // Your backend script to process unrestriction
            type: 'POST',
            dataType: 'json',
            data: { 
                userId: userId
            },
            success: function(response) {
                var userEmail = response.email; // Assuming the response contains the user's email
                var dateUnrestricted = response.dateUnrestricted; // Get the unrestrict date from the response
                
                // Send the unrestriction notification
                sendUnrestrictionNotification(userEmail, dateUnrestricted);
    
                $('#unrestrictModal').css('display', 'none'); // Hide the modal
                toastr.info('User unrestricted successfully.');
            },
            error: function(xhr, status, error) {
                console.error('Error unrestricting user:', error);
            }
        });
    });
    
    // Function to send unrestriction notification
    function sendUnrestrictionNotification(userEmail, dateUnrestricted) {
        if (!userEmail || userEmail.trim() === "") {
            console.error('Error: Recipient email is empty.');
            return; // Exit the function if the email is empty
        }
    
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

});
