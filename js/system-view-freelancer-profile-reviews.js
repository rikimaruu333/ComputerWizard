$(document).ready(function () {
    const freelancerId = getFreelancerIdFromURL();  // Get the freelancer ID from the URL
    if (!freelancerId) {
        alert('Freelancer ID not provided.');
        return;  // Exit if freelancerId is not found
    }

    fetchFreelancerReviews(freelancerId);  // Fetch reviews for the specific freelancer
});

// Function to get freelancer ID from URL parameters
function getFreelancerIdFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('freelancer_id');  // Get the freelancer_id from the URL
}

function fetchFreelancerReviews(freelancerId) {
    $.ajax({
        url: 'system-fetch-view-freelancer-reviews.php',  // PHP script to fetch reviews
        type: 'GET',
        data: { freelancer_id: freelancerId },  // Pass freelancerId to PHP script
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                populateReviews(data.reviews);  // Populate the reviews on success
                updateOverallRating(data.reviews);  // Update overall rating on success
            } else {
                console.error(data.error || 'Failed to fetch reviews.');
                displayNoReviewsMessage();  // Display message if no reviews
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Error:', errorThrown);  // Handle AJAX errors
        }
    });
}

// Send Message button click
$('#adminSendMessageBtn').on('click', function() {
    // var userId = $(this).data('user-id');
    
    // Redirect to messaging page
    window.location.href = `admin-messaging.php`;
});
// Send Message button click
$('#clientSendMessageBtn').on('click', function() {
    // var userId = $(this).data('user-id');
    
    // Redirect to messaging page
    window.location.href = `client-messaging.php`;
});
// Send Message button click
$('#freelancerSendMessageBtn').on('click', function() {
    // var userId = $(this).data('user-id');
    
    // Redirect to messaging page
    window.location.href = `freelancer-messaging.php`;
});

function populateReviews(reviews) {
    const container = $('.freelancer-review-container');
    container.empty();  // Clear existing content

    if (reviews.length === 0) {
        // If no reviews, display a placeholder
        const noReviewsMessage = `
            <div class="client-review-container">
                <div class="client-rating">
                    <div class="star">${generateStars(0)}</div>
                    <p>No reviews available.</p>
                </div>
                <div class="client-review">
                    <p>No feedback provided yet for this freelancer.</p>
                </div>
            </div>
        `;
        container.append(noReviewsMessage);
    } else {
        reviews.forEach(review => {
            const reviewElement = $(`
                <div class="client-review-container">
                    <div class="client-info">
                        <div class="client-img">
                            <img src="${review.profile || '../images/default-user.jpg'}" alt="Client Profile">
                        </div>
                        <div class="client-details">
                            <h3>${review.client_name}</h3>
                            <i>${review.usertype}</i>
                        </div>
                    </div>
                    <div class="client-rating">
                        <div class="star">${generateStars(review.rating)}</div>
                        <p>${formatDate(review.review_date)}</p>
                    </div>
                    <div class="client-review">
                        <p>${review.review}</p>
                    </div>
                </div>
            `);

            container.append(reviewElement);  // Append each review element
        });
    }
}


function generateStars(rating) {
    // Call the renderStars function to handle full, half, and empty stars
    return renderStars(rating);
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


function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric', month: '2-digit', day: '2-digit'  // Format date as MM/DD/YYYY
    });
}


function updateOverallRating(reviews) {
    const totalReviews = reviews.length;
    let totalRating = 0;
    const ratingCount = [0, 0, 0, 0, 0]; // Stores the count for each rating (5-1)

    if (totalReviews === 0) {
        // Set default values if no reviews exist
        $('#overall-rating').text('0.0');
        $('#star-rating').html(generateStars(0));
        $('#transaction-count').text('0 transactions');
        $('#rating-distribution').empty();
    } else {
        reviews.forEach(review => {
            totalRating += review.rating;
            ratingCount[5 - review.rating]++;
        });

        // Calculate overall rating (average)
        const averageRating = totalRating / totalReviews;
        $('#overall-rating').text(averageRating.toFixed(1));

        // Generate the star rating display with full and half stars
        $('#star-rating').html(generateStars(averageRating));

        // Populate the rating distribution and set the width of each bar
        $('#rating-distribution').empty(); // Clear existing bars
        ratingCount.forEach((count, index) => {
            const ratingText = 5 - index; // Converts index to rating (5, 4, 3, 2, 1)
            const percentage = (count / totalReviews) * 100;

            const ratingElement = $(`
                <div class="num">
                    ${ratingText}
                    <div class="bar-container">
                        <span style="width: ${percentage}%;"></span>
                    </div>
                </div>
            `);

            $('#rating-distribution').append(ratingElement);
        });

        // Update transaction count (total number of reviews)
        $('#transaction-count').text(`${totalReviews} transactions`);
    }
}

function displayNoReviewsMessage() {
    const container = $('.freelancer-review-container');
    container.empty();  // Clear the container before displaying the no reviews message
    const noReviewsMessage = `
        <div class="client-review-container">
            <div class="client-rating">
                <div class="star">${generateStars(0)}</div>
                <p>No reviews available.</p>
            </div>
            <div class="client-review">
                <p>No feedback provided yet for this freelancer.</p>
            </div>
        </div>
    `;
    container.append(noReviewsMessage);  // Append the no reviews message to the container
}
