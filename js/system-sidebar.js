document.addEventListener("DOMContentLoaded", function() {
    const menuItems = document.querySelectorAll('.user-sidebar ul li a');
    
    // Get only the path of the current page (not the full URL)
    const currentPage = window.location.pathname.split('/').pop(); 

    menuItems.forEach(function(item) {
        // Get the href attribute and extract only the last part (file name)
        const itemPage = item.getAttribute('href').split('/').pop();

        // Compare the current page file name with the menu item file name
        if (itemPage === currentPage) {
            item.parentElement.classList.add('active');
        }
    });
});

document.getElementById('logout-btn').addEventListener('click', function (e) {
    e.preventDefault(); // Prevent the default action of the link

    Swal.fire({
        title: 'Are you sure?',
        text: 'You will be logged out!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, log me out',
        cancelButtonText: 'Cancel',
        customClass: {
            popup: 'small-swal-popup',
            icon: 'small-swal-icon',
            confirmButton: 'custom-confirm-button',
            cancelButton: 'custom-cancel-button'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to the logout script
            window.location.href = '../php/logout.php';
        }
    });
});

function fetchUserData() {
    $.ajax({
        url: 'system-get-user-data.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                PopulateSidebarUserImage(response.data);
            } else {
                alert(response.message); 
            }
        },
        error: function () {
            alert('Error fetching freelancer data');
        }
    });
}
fetchUserData();
function PopulateSidebarUserImage(data) {
    document.querySelector("#sidebarProfileImg").src = data.profile ? data.profile : '../images/user.jpg';
}
