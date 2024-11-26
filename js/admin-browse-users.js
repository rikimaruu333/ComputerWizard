$(document).ready(function() {
    // Fetch users (modified to reflect the new admin page context)
    $.ajax({
        url: 'admin-fetch-users.php',  // Backend script to fetch users
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (Array.isArray(response)) {
                populateUserList(response);  // Populate users if data is returned
                console.log(response)
            } else {
                console.error('Error: Invalid data format');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching users:', error);
        }
    });
});

function populateUserList(users) {
    var userListContainer = $('.user-list-container');  // Update the selector to reflect the new structure
    userListContainer.empty();  // Clear previous data

    users.forEach(function(user) {
        // Corrected icon selection for restricted vs. not restricted
        var restrictionIcon = user.status == 3 ? 
            '<i class="bx bxs-lock-open unrestrict-button" title="User is restricted. Click to unrestrict."></i>' : 
            '<i class="bx bxs-no-entry restrict-button" title="User is not restricted. Click to restrict."></i>';

        var userHTML = `
            <div class="user-details-container">
                <div class="user-details view-profile-btn" data-user-id="${user.id}" data-user-type="${user.usertype}">
                    <img src="${user.profile}" class="user-avatar" alt="User Avatar">
                    <div class="user-info">
                        <h3>${user.firstname} ${user.lastname}</h3>
                        <p>${user.usertype}</p>
                    </div>
                </div>
                <div class="user-details-buttons">
                    <div class="button-box" title="Hover icon to check restriction status.">
                        ${restrictionIcon}
                    </div>
                    <div class="button-box send-message-btn" title="Send Message" data-user-id="${user.id}">
                        <i class="bx bx-message"></i>
                    </div>
                    <div class="button-box view-profile-btn" title="View Profile" data-user-id="${user.id}" data-user-type="${user.usertype}">
                        <i class="bx bx-search" title="View Profile"></i>
                    </div>
                </div>
            </div>
        `;
        userListContainer.append(userHTML);  // Append each user's details
    });
    
    $('.view-profile-btn').on('click', function() {
        var userId = $(this).data('user-id');
        var userType = $(this).data('user-type');  // Get the user type
        
        // Redirect based on user type
        if (userType === 'Freelancer') {
            window.location.href = `system-view-freelancer-profile.php?freelancer_id=${userId}`;
        } else if (userType === 'Client') {
            window.location.href = `system-view-client-profile.php?client_id=${userId}`;
        }
    });
    
    // Send Message button click
    $('.send-message-btn').on('click', function() {
        // var userId = $(this).data('user-id');
        
        // Redirect to messaging page
        window.location.href = `admin-messaging.php`;
    });
}

// Adjusted selectors to match the updated admin page filtering structure
const searchBar = document.querySelector(".search input"),
      searchIcon = document.getElementById("searchBar"),
      searchFilterAddress = document.getElementById('filterAddress'),
      searchFilterGender = document.getElementById('filterGender'),
      searchFilterRestriction = document.getElementById('filterRestriction'),
      searchFilterUsertype = document.getElementById('filterUsertype'),
      usersListContainer = document.querySelector(".user-list-container");

// Search bar functionality with the new structure
searchIcon.onclick = () => {
    searchBar.classList.toggle("show");
    searchIcon.classList.toggle("active");
    searchBar.focus();

    if (!searchBar.classList.contains("show")) {
        searchBar.value = ""; // Reset only the search input
        filterAndSearchUsers(); // Keep filters and apply them
    }
};

// Fetch users based on search and filters (admin page)
function filterAndSearchUsers() {
    let searchTerm = searchBar.value.trim();
    let address = searchFilterAddress.value;
    let gender = searchFilterGender.value;
    let restriction = searchFilterRestriction.value;
    let usertype = searchFilterUsertype.value;

    // Make AJAX request to the PHP file
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "admin-search-filter-users.php", true);
    xhr.onload = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            let users = JSON.parse(xhr.response);  // Parse JSON response
            renderUsers(users);  // Render the filtered and searched users
        }
    };
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(`searchTerm=${searchTerm}&address=${address}&gender=${gender}&restriction=${restriction}&usertype=${usertype}`);  // Send search term and filters
}

// Render user results
function renderUsers(users) {
    usersListContainer.innerHTML = '';  // Clear existing content

    if (users.length > 0) {
        users.forEach(user => {
            var restrictionIcon = user.status == 3 ? 
                '<i class="bx bxs-lock-open unrestrict-button" title="User is restricted. Click to unrestrict."></i>' : 
                '<i class="bx bxs-no-entry restrict-button" title="User is not restricted. Click to restrict."></i>';

            const userHTML = `
                <div class="user-details-container">
                    <div class="user-details view-profile-btn" data-user-id="${user.id}" data-user-type="${user.usertype}">
                        <img src="${user.profile}" class="user-avatar" alt="User Image">
                        <div class="user-info">
                            <h3>${user.firstname} ${user.lastname}</h3>
                            <p>${user.usertype}</p>
                        </div>
                    </div>
                    <div class="user-details-buttons">
                        <div class="button-box" title="Hover icon to check restriction status.">
                            ${restrictionIcon} 
                        </div>
                        <div class="button-box send-message-btn" title="Send Message" data-user-id="${user.id}">
                            <i class="bx bx-message" title="Send Message"></i>
                        </div>
                        <div class="button-box view-profile-btn" title="View Profile" data-user-id="${user.id}" data-user-type="${user.usertype}">
                            <i class="bx bx-search" title="View Profile"></i>
                        </div>
                    </div>
                </div>
            `;
            usersListContainer.innerHTML += userHTML;
        });
    } else {
        usersListContainer.innerHTML = '<p class="no-user-results">No users found matching your criteria.</p>';
    }
    
    $('.view-profile-btn').on('click', function() {
        var userId = $(this).data('user-id');
        var userType = $(this).data('user-type');  // Get the user type
        
        // Redirect based on user type
        if (userType === 'Freelancer') {
            window.location.href = `system-view-freelancer-profile.php?freelancer_id=${userId}`;
        } else if (userType === 'Client') {
            window.location.href = `system-view-client-profile.php?client_id=${userId}`;
        }
    });

    // Send Message button click
    $('.send-message-btn').on('click', function() {
        // var userId = $(this).data('user-id');
        
        // Redirect to messaging page
        window.location.href = `admin-messaging.php`;
    });

}

// Trigger search and filters on change
searchBar.onkeyup = filterAndSearchUsers;
searchFilterAddress.addEventListener('change', filterAndSearchUsers);
searchFilterGender.addEventListener('change', filterAndSearchUsers);
searchFilterRestriction.addEventListener('change', filterAndSearchUsers);
searchFilterUsertype.addEventListener('change', filterAndSearchUsers);

// Fetch default list on page load
document.addEventListener('DOMContentLoaded', filterAndSearchUsers);
