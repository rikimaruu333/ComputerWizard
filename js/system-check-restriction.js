$(document).ready(function() {
    const socket = io('http://localhost:8080'); // Adjust the URL if needed

// Listen for restriction events from the server
    socket.on('restriction', (data) => {
        if (data.isRestricted) { // Change response to data
            toastr.warning('Your account has been restricted. You will be logged out.');
            setTimeout(function() {
                window.location.href = 'landingpage.php'; // Redirect to the landing page
            }, 5000); // Give a 5-second delay before redirection
        }
    });


    // Optional: You can fetch the current restriction status on page load
    $.ajax({
        url: 'system-check-restriction.php', // URL of the new PHP file
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.isRestricted) {
                toastr.warning('Your account has been restricted. You will be logged out.');
                setTimeout(function() {
                    window.location.href = 'landingpage.php'; // Redirect to the landing page
                }, 5000); // Give a 5-second delay before redirection
            }
        },
        error: function(xhr) {
            console.error('Error checking restriction:', xhr.responseText);
        }
    });
});
