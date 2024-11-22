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
