$(document).ready(function() {
    // Fetch freelancers
    $.ajax({
        url: 'client-fetch-freelancers.php',  // Backend script to fetch freelancers
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (Array.isArray(response)) {
                populateFreelancerList(response);  // Populate freelancers if data is returned
            } else {
                console.error('Error: Invalid data format');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching freelancers:', error);
        }
    });
});

function populateFreelancerList(freelancers) {
    var freelancerListContainer = $('.freelancer-list');
    freelancerListContainer.empty();  // Clear previous data

    freelancers.forEach(function(freelancer) {
        // Determine which schedule image to use based on availability
        var scheduleIcon = freelancer.is_available ? 
            '<img src="../images/av-schedule-icon.png" alt="Available Schedule" title="Freelancer is available. View profile to view more details.">' : 
            '<img src="../images/uv-schedule-icon.png" alt="Unavailable Schedule" title="Freelancer is currently unavailable. View profile to view more details.">';

        var freelancerHTML = `
            <div class="freelancer-details-container">
                <div class="freelancer-details">
                    <img src="${freelancer.profile}" class="freelancer-avatar" alt="Freelancer Avatar">
                    <div class="freelancer-info">
                        <h3>${freelancer.firstname} ${freelancer.lastname}</h3>
                        <p>${freelancer.usertype}</p>
                    </div>
                </div>
                <div class="freelancer-details-buttons">
                    <div class="button-box" title="Hover icon to check status.">
                        ${scheduleIcon} 
                    </div>
                    <div class="button-box send-message-btn" title="Send Message">
                        <i class="bx bx-message"></i>
                    </div>
                    <div class="button-box view-profile-btn" title="View Profile" data-freelancer-id="${freelancer.id}">
                        <i class="bx bx-search" title="View Profile"></i>
                    </div>
                </div>
            </div>
        `;
        freelancerListContainer.append(freelancerHTML);  // Append each freelancer's details
    });
    
    // Add click event listeners for each "View Profile" button
    $('.view-profile-btn').on('click', function() {
        var freelancerId = $(this).data('freelancer-id');
        window.location.href = `system-view-freelancer-profile.php?freelancer_id=${freelancerId}`;
    });
    
    // Send Message button click
    $('.send-message-btn').on('click', function() {
        // var userId = $(this).data('user-id');
        
        // Redirect to messaging page
        window.location.href = `client-messaging.php`;
    });
}

const searchBar = document.querySelector(".search input"),
      searchIcon = document.querySelector(".search button"),
      searchFilterAddress = document.getElementById('filterAddress'),
      searchFilterGender = document.getElementById('filterGender'),
      usersListContainer = document.querySelector(".freelancer-list-container");

// Event listener for the search bar toggle (open/close)
searchIcon.onclick = () => {
    searchBar.classList.toggle("show");
    searchIcon.classList.toggle("active");
    searchBar.focus();

    // If the search bar is closed (does not have 'show' class), reset only the search input, keep filters
    if (!searchBar.classList.contains("show")) {
        searchBar.value = ""; // Reset only the search input
        filterAndSearchFreelancers(); // Keep filters and apply them
    }
};

// Function to fetch freelancers based on search term and filters
function filterAndSearchFreelancers() {
    let searchTerm = searchBar.value.trim();  // Get search term
    let address = searchFilterAddress.value;  // Get selected address filter
    let gender = searchFilterGender.value;    // Get selected gender filter

    // Make the AJAX request
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "client-search-filter-freelancers.php", true); // Ensure this points to the correct PHP file
    xhr.onload = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            let freelancers = JSON.parse(xhr.response); // Parse the JSON response
            renderFreelancers(freelancers); // Render the filtered and searched freelancers
        }
    };
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(`searchTerm=${searchTerm}&address=${address}&gender=${gender}`); // Send search term and filters
}

// Function to render the freelancer results
function renderFreelancers(freelancers) {
    usersListContainer.innerHTML = ''; // Clear existing content

    if (freelancers.length > 0) {
        freelancers.forEach(freelancer => {
            // Determine which schedule image to use based on availability
            var scheduleIcon = freelancer.is_available ? 
                '<img src="../images/av-schedule-icon.png" alt="Available Schedule" title="Freelancer is available. View profile to view more details.">' : 
                '<img src="../images/uv-schedule-icon.png" alt="Unavailable Schedule" title="Freelancer is currently unavailable. View profile to view more details.">';

            const freelancerHTML = `
                <div class="freelancer-details-container">
                    <div class="freelancer-details">
                        <img src="${freelancer.profile}" class="freelancer-avatar" alt="Freelancer Image">
                        <div class="freelancer-info">
                            <h3>${freelancer.firstname} ${freelancer.lastname}</h3>
                            <p>${freelancer.usertype}</p>
                        </div>
                    </div>
                    <div class="freelancer-details-buttons">
                        <div class="button-box" title="Hover icon to check status.">
                            ${scheduleIcon} 
                        </div>
                        <div class="button-box send-message-btn" title="Send Message">
                            <i class="bx bx-message"></i>
                        </div>
                        <div class="button-box view-profile-btn" title="View Profile" data-freelancer-id="${freelancer.id}">
                            <i class="bx bx-search" title="View Profile"></i>
                        </div>
                    </div>
                </div>
            `;
            usersListContainer.innerHTML += freelancerHTML;
        });
    } else {
        usersListContainer.innerHTML = '<p class="no-freelancer-results">No freelancers found matching your criteria.</p>';
    }
    
// Add click event listeners for each "View Profile" button
    $('.view-profile-btn').on('click', function() {
        var freelancerId = $(this).data('freelancer-id');
        window.location.href = `system-view-freelancer-profile.php?freelancer_id=${freelancerId}`;
    });
    
    // Send Message button click
    $('.send-message-btn').on('click', function() {
        // var userId = $(this).data('user-id');
        
        // Redirect to messaging page
        window.location.href = `client-messaging.php`;
    });
}

// Add event listeners to trigger filter and search
searchBar.onkeyup = filterAndSearchFreelancers;
searchFilterAddress.addEventListener('change', filterAndSearchFreelancers);
searchFilterGender.addEventListener('change', filterAndSearchFreelancers);

// Fetch the default list of freelancers on page load
document.addEventListener('DOMContentLoaded', filterAndSearchFreelancers);







