$(document).ready(function () {
    $.ajax({
        url: 'system-get-top-freelancers.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response && response.freelancers) {
                const userID = response.userID;
                populateFreelancerRecommendations(response.freelancers, userID);
            } else {
                console.error('Error fetching freelancers:', response.error);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error fetching freelancers:', error);
        }
    });
});

function populateFreelancerRecommendations(freelancers, userID) {
    const recommendationList = $('.freelancer-recommendation-list');
    recommendationList.empty(); // Clear any existing content

    freelancers.forEach(freelancer => {
        const rating = freelancer.rating ? parseFloat(freelancer.rating).toFixed(1) : 'N/A'; // Default to 'N/A' if no rating
        const freelancerContainer = $(`
            <div class="recommended-freelancer-container">
                <div class="freelancer-info" data-id="${freelancer.id}" title="Click to view profile.">
                    <div class="freelancer-info-img">
                        <img src="${freelancer.profile || '../images/userpic1.jpg'}" alt="${freelancer.firstname} ${freelancer.lastname}">
                    </div>
                    <div class="freelancer-details">
                        <h3>${freelancer.firstname} ${freelancer.lastname}</h3>
                        <p>${freelancer.usertype}</p>
                    </div>
                </div>
                <div class="freelancer-rating">
                    <p>${rating}</p>
                    <div class="stars">
                        ${renderStars(rating)}
                    </div>
                    <span>${freelancer.recommendation_count} recommendations</span>
                </div>
            </div>
        `);
        
        // Add click event for redirecting to profile page
        freelancerContainer.find('.freelancer-info').on('click', function () {
            const freelancerId = $(this).data('id');
            if (freelancerId === userID) {
                // Redirect to freelancer-dashboard.php if the IDs match
                window.location.href = 'freelancer-dashboard.php';
            } else {
                // Redirect to the freelancer's profile page
                window.location.href = `system-view-freelancer-profile.php?freelancer_id=${freelancerId}`;
            }
        });
        
        recommendationList.append(freelancerContainer);
    });
}


function renderStars(rating) {
    if (rating === 'N/A') return ''; // No stars if no rating

    const fullStars = Math.floor(rating);
    const halfStars = rating % 1 ? 1 : 0;
    const emptyStars = 5 - fullStars - halfStars;

    return `${'<i class="bx bxs-star"></i> '.repeat(fullStars)}
            ${'<i class="bx bxs-star-half"></i> '.repeat(halfStars)}
            ${'<i class="bx bx-star"></i> '.repeat(emptyStars)}`;
}
