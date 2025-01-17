document.addEventListener("DOMContentLoaded", function () {
// Initialize EmailJS with the first public key
window.addEventListener('load', () => {
    emailjs.init("XnzWkndgRn2h6N10o"); // First public key
});


function emitAutoRestrictionStatus() {
    const url = 'system-auto-restrict-users.php'; // Update to your PHP script URL

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        data.forEach(user => {
            if (user.isRestricted) {
                toastr.info(`Your account has been unrestricted.`);
                
                // Send email notification
                sendAutoRestrictionEmail(user.email, user.unrestrictDate, user.restrictReason);
            }
        });
    })
    .catch((error) => {
        console.error('Error emitting restriction status:', error);
    });
}

// Function to send restriction email
function sendAutoRestrictionEmail(userEmail, unrestrictDate, restrictReason) {

    // Construct the message using template literals
    var message = `We regret to inform you that your account has been temporarily restricted until ${unrestrictDate} due to violations of our community guidelines. This action was taken to maintain a safe and respectful environment for all users. We encourage you to review our guidelines to understand the reasons for this restriction. If you believe this decision was made in error or if you have any questions, please feel free to contact our support team located at the footer of the landing page. We appreciate your understanding and cooperation in this matter.
            
    Summary: Your account has been restricted until ${unrestrictDate} due to ${restrictReason}.
            
    ${$('#adminNotes').val()}`;

    var templateParams = {
        from_name: "GigHub Team",
        reply_to: userEmail,
        message: message
    };


    console.log("Sending restriction email with parameters:", templateParams); // Log parameters for debugging

    emailjs.send('service_7edsjxk', 'template_zm3dmjk', templateParams) // Use the first EmailJS service
        .then(function(response) {
            toastr.info('Restriction notification sent to your email: ' + userEmail);
        }, function(error) {
            console.error('Error sending restriction email:', error);
        });
}

// Call to emit unrestriction status (you may call this after processing users)
emitAutoRestrictionStatus(); // Call this function without email input


});