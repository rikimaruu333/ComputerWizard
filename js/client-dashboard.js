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






    const togglePost = document.querySelector('.toggle-post');
    const toggleTransaction = document.querySelector('.toggle-transaction');
    const postsContainer = document.querySelector('.posts-container');
    const transactionsContainer = document.querySelector('.transactions-container');

    // Function to update UI based on the current toggle state
    function updateUI() {
        const activeToggle = localStorage.getItem('activeToggle');

        if (activeToggle === 'transaction') {
            toggleTransaction.classList.add('active');
            togglePost.classList.remove('active');
            transactionsContainer.style.display = 'flex';
            postsContainer.style.display = 'none';
        } else {
            togglePost.classList.add('active');
            toggleTransaction.classList.remove('active');
            postsContainer.style.display = 'flex';
            transactionsContainer.style.display = 'none';
        }
    }

    // Set initial visibility based on local storage
    updateUI();

    togglePost.addEventListener('click', () => {
        // Add active class to posts toggle and remove from transactions
        togglePost.classList.add('active');
        toggleTransaction.classList.remove('active');

        // Show posts container and hide transactions container
        postsContainer.style.display = 'flex';
        transactionsContainer.style.display = 'none';

        // Save the state to local storage
        localStorage.setItem('activeToggle', 'post');
    });

    toggleTransaction.addEventListener('click', () => {
        // Add active class to transactions toggle and remove from posts
        toggleTransaction.classList.add('active');
        togglePost.classList.remove('active');

        // Show transactions container and hide posts container
        transactionsContainer.style.display = 'flex';
        postsContainer.style.display = 'none';

        // Save the state to local storage
        localStorage.setItem('activeToggle', 'transaction');
    });
    // If you want to reset the state back to default at some point (e.g., a logout action), you can clear the localStorage by calling:

    // localStorage.removeItem('activeToggle');




    function fetchClientData() {
        $.ajax({
            url: 'system-get-user-data.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    updateClientInfo(response.data);
                } else {
                    alert(response.message); 
                }
            },
            error: function () {
                alert('Error fetching Freelancer data');
            }
        });
    }
fetchClientData();
function updateClientInfo(data) {
    document.querySelector(".client-img #profile").src = data.profile ? data.profile : '../images/user.jpg';
    document.querySelector(".client-details #fullName").innerText = data.firstname + ' ' + data.lastname;
    document.querySelector(".client-details #email").innerText = data.email;
    document.querySelector(".client-post-transaction #transactionCount").innerText = `${data.transaction_count} transactions`;
}
});



$(document).ready(function() {
    fetchPosts();
    // Single AJAX request to fetch posts
});
function fetchPosts() {
    $.ajax({
        url: 'client-fetch-posts.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response && !response.error) {
                if (Array.isArray(response)) {
                    populateCard(response);
                } else {
                    console.error('Error: Invalid posts data format');
                }
            } else {
                console.error('Error fetching posts:', response.error);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching posts:', error);
        }
    });
}
function populateCard(posts) {
    var cardWithImageContainer = $('.posts-with-image');
    var cardWithoutImageContainer = $('.posts-without-image');
    var postTemplate = $('#postTemplate').html();

    // Clear existing posts
    cardWithImageContainer.empty();
    cardWithoutImageContainer.empty();

    posts.forEach(function(post) {
        var card = $(postTemplate).clone();

        // Populate user details
        $('#userTotalPosts').html(`<i class="bx bxs-book-content"></i> ${post.total_jobposts} posts`);

        // Populate freelancer list for each post
        var freelancerList = card.find('.post-freelancer-list');  // Make sure this selector is correct
        freelancerList.empty();  // Clear previous contents

        // Create a set to track added freelancers by their unique id (e.g., freelancer.id)
        var addedFreelancers = new Set();

        if (post.freelancers && post.freelancers.length > 0) {
            post.freelancers.forEach(function(freelancer) {
                // Check if this freelancer has already been added using their unique ID
                if (!addedFreelancers.has(freelancer.freelancer_id)) {
                    // If not added, append them to the list
                    freelancerList.append(`
                        <div class="post-freelancer" data-freelancer-id="${freelancer.freelancer_id}" data-post-id="${post.post_id}">
                            <img src="${freelancer.profile}" alt="${freelancer.firstname} ${freelancer.lastname}">
                            <div class="post-freelancer-details">
                                <h3>${freelancer.firstname} ${freelancer.lastname}</h3>
                            </div>
                        </div>
                    `);
                    
                    // Add the freelancer ID to the set to avoid future duplication
                    addedFreelancers.add(freelancer.freelancer_id);
                }
            });
        } else {
            freelancerList.append('<span>No freelancers found for this post.</span>');
        }


        // Check if the post is tagged, and append buttons accordingly
        var tagButton = `<i class="bx bxs-user-plus" data-post-id="${post.post_id}" id="tagFreelancerBtn" title="Tag the freelancer that took on this job"></i>`;
        var infoButton = `<i class="bx bx-info-circle" data-post-id="${post.post_id}" id="postTagDetailsBtn" title="Show post tag details"></i>`;

        card.find('p #jobCategory').text(post.job_category);
        card.find('p #jobNeeded').text(post.job);
        
        if (post.isTagged) {
            // Post is tagged, append the info button instead of the tag button
            card.find('.right').append(infoButton);
            card.find('#tagFreelancerBtn').hide(); // Hide the tag button if post is already tagged
        } else {
            // Post is not tagged, append the tag button
            card.find('.right').append(tagButton);
            card.find('#postTagDetailsBtn').hide(); // Hide the info button if post is not tagged
        }
                
        card.find('.profileImg img').attr('src', post.profile);
        card.find('.userNickName').text(post.firstname + " " + post.lastname);
        card.find('.userRealName').text(post.usertype);

        // Populate post message
        card.find('.message').text(post.caption);

        // Handle images
        var imgBg = card.find('.imgBg');
        imgBg.empty();

        // Append images
        if (post.images.length > 0) {
            imgBg.removeClass().addClass('imgBg');

            // Adjust image classes based on the number of images
            if (post.images.length === 2) {
                imgBg.addClass('two-images');
            } else if (post.images.length === 3) {
                imgBg.addClass('three-images');
            } else if (post.images.length === 4) {
                imgBg.addClass('four-images');
            } else if (post.images.length > 4) {
                imgBg.addClass('four-images-more');
            }

            post.images.forEach(function(image, index) {
                if (index < 4) {
                    imgBg.append($('<img>').attr('src', image).on('click', function() {
                        openModal(post.images, index); // Call modal function
                    }));
                }

                if (index === 3 && post.images.length > 4) {
                    var additionalImagesCount = post.images.length - 4;
                    var viewMoreDiv = $('<div class="content2">+' + additionalImagesCount + '</div>');
                    imgBg.append(viewMoreDiv);
                }
            });

            // Show expand button
            imgBg.append($('<div class="content1" id="expandBtn"><i class="bx bx-fullscreen"></i>EXPAND</div>').on('click', function() {
                openModal(post.images, 0); // Open modal from the first image
            }));
        } else {
            imgBg.hide(); // Hide image container if no images
        }
        card.find('#viewCommentsBtn').attr('data-post-id', post.post_id);
        card.find('#tagFreelancerBtn').attr('data-post-id', post.post_id);
        card.find('#changeTaggedFreelancerBtn').attr('data-post-id', post.post_id);
        card.find('#postTagDetailsBtn').attr('data-post-id', post.post_id);
        card.find('.userImg img').attr('src', post.profile);
        card.find('#post_id').val(post.post_id);
        card.find('#user_id').val(post.currentUserID);
        card.find('#commentCount').text(post.total_comments + ' comments');

        
        var formattedTime = moment(post.post_created).format('MMMM D, YYYY [at] h:mm A');
        card.find('.postTime').text(formattedTime); // Set the formatted time

        // Hide message if no caption
        if (!post.caption) {
            card.find('.message').hide();
        }

        // Append the card to the appropriate container
        if (post.images.length > 0) {
            cardWithImageContainer.append(card);
        } else {
            cardWithoutImageContainer.append(card);
        }
        // Attach click event listener for opening the update modal
        attachUpdateModalEvent(card, post);

        card.removeClass('postTemplate').show();

         // Handle the toggle of the freelancer list when the button is clicked
         card.find('#tagFreelancerBtn').on('click', function() {
            // Hide freelancer list for all posts
            $('.post-freelancer-list').not(freelancerList).slideUp(); // Hide all other freelancer lists

            // Slide toggle the freelancer list for the clicked post
            freelancerList.stop(true, true).slideToggle(); // Toggle visibility
        });

        // Close the dropdown if clicked outside
        $(document).on('click', function(event) {
            // Check if the click is outside the freelancer list or the tag button
            if (!$(event.target).closest('.post-freelancer-list, #tagFreelancerBtn').length) {
                // Close all freelancer lists
                $('.post-freelancer-list').slideUp();
            }
        });

        // Handle freelancer selection and tagging
        freelancerList.find('.post-freelancer').on('click', function() {
            var freelancerId = $(this).data('freelancer-id');
            var postId = $(this).data('post-id');
            var freelancerName = $(this).find('.post-freelancer-details h3').text(); // Fetch freelancer's name for confirmation
            var tagButton = $(this).find('.tag-button');  // Assuming your tag button has the class 'tag-button'

            // Show SweetAlert2 confirmation dialog
            Swal.fire({
                title: `Tag ${freelancerName}?`,
                text: "This action will tag the freelancer to the post and notify them.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, tag them!',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'small-swal-popup',
                    icon: 'small-swal-icon',
                    confirmButton: 'custom-confirm-question-button',
                    cancelButton: 'custom-cancel-button'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to update the post table
                    $.ajax({
                        url: 'system-tag-freelancer.php', // PHP script to handle the update
                        method: 'POST',
                        data: { post_id: postId, freelancer_id: freelancerId },
                        success: function(response) {
                            try {
                                var responseData = JSON.parse(response); // Parse the JSON response
                                if (responseData.success) {
                                    Swal.fire('Tagged!', 'Freelancer has been tagged successfully.', 'success');
                                    // Hide the tag button after successful tagging
                                    tagButton.hide();
                                } else {
                                    Swal.fire('Error!', 'Failed to tag the freelancer.', 'error');
                                }
                            } catch (e) {
                                Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'An error occurred while tagging the freelancer.', 'error');
                        }
                    });
                }
            });
        });

        // Close modal when the close button or outside the modal is clicked
        $('#tagDetailsModal .close, #tagDetailsModal').on('click', function(event) {
            if ($(event.target).is('#tagDetailsModal, .close')) {
                $('#tagDetailsModal').fadeOut();
            }
        });



    });
}

// Handle selection of a new freelancer from the list
$('.tag-details-freelancer-list').on('click', '.post-freelancer', function () {
    var freelancerId = $(this).data('freelancer-id');
    var postId = $(this).data('post-id');
    var freelancerName = $(this).find('.post-freelancer-details h3').text(); // Get freelancer's name
    console.log(freelancerId);
    console.log(postId);
    console.log(freelancerName);

    // Show SweetAlert2 confirmation dialog
    Swal.fire({
        title: `Change tag to ${freelancerName}?`,
        text: "This will update the tagged freelancer for the post.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, update!',
        cancelButtonText: 'Cancel',
        customClass: {
            popup: 'small-swal-popup',
            icon: 'small-swal-icon',
            confirmButton: 'custom-confirm-question-button',
            cancelButton: 'custom-cancel-button'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Send AJAX request to update the post's tagged freelancer
            $.ajax({
                url: 'system-update-tagged-freelancer.php', // PHP script to handle the update
                method: 'POST',
                data: { post_id: postId, freelancer_id: freelancerId }, // Correct data format
                success: function (response) {
                    try {
                        var responseData = JSON.parse(response);
                        if (responseData.success) {
                            Swal.fire('Updated!', 'The tagged freelancer has been updated successfully.', 'success');
                        } else {
                            Swal.fire('Error!', responseData.error || 'Update failed.', 'error');
                        }
                    } catch (e) {
                        console.error('Parsing error:', e);
                        Swal.fire('Error!', 'Unexpected response format.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'An error occurred while updating the tagged freelancer.', 'error');
                }
            });
        }
    });
});

// Handle clicking the 'Change Tagged Freelancer' button
$('#changeTaggedFreelancerBtn').on('click', function () {
    var freelancerList = $(this).closest('.tag-details-config').find('.tag-details-freelancer-list');
    freelancerList.stop(true, true).slideToggle(); // Toggle visibility of freelancer list dropdown
});

// Close the freelancer list dropdown if clicked outside
$(document).on('click', function (event) {
    if (!$(event.target).closest('.tag-details-freelancer-list, #changeTaggedFreelancerBtn').length) {
        $('.tag-details-freelancer-list').slideUp();  // Hide freelancer list if clicking outside
    }
});


$(document).on('click', '#postTagDetailsBtn', function () {
    var postId = $(this).data('post-id');

    // Send AJAX request to fetch tagged user and freelancers
    $.ajax({
        url: 'system-get-tagged-freelancer.php', // Adjust to the file path of your PHP script
        method: 'POST',
        data: { post_id: postId },
        success: function (response) {
            try {
                var responseData = JSON.parse(response);
        
                if (responseData.success) {
                    var user = responseData.data.taggedUser;
                    var freelancers = responseData.data.freelancers;

                    // Populate modal with tagged user details (if any)
                    if (user) {
                        $('#tagDetailsModal .profile-img').attr('src', user.profile);
                        $('#tagDetailsModal .freelancer-name').text(user.firstname + ' ' + user.lastname);
                        $('#tagDetailsModal .tagged-text').text('Tagged freelancer');
        
                        // Add freelancer_id to the "View Profile" button as a data attribute
                        $('#tagDetailsModal .modal-action-btn').data('freelancer-id', user.id);
                    } else {
                        $('#tagDetailsModal .tagged-text').text('No tagged freelancer');
                    }

                    // Populate the freelancer list dropdown
                    var freelancerList = $('#tagDetailsModal .tag-details-freelancer-list');
                    freelancerList.empty(); // Clear previous entries
                            
                    // Create a set to track added freelancers by their unique id (e.g., freelancer.id)
                    var addedFreelancers = new Set();
                    
                    // Ensure that the currently tagged freelancer is not included
                    if (freelancers.length > 0) {
                        freelancers.forEach(function(freelancer) {

                            // Check if the freelancer is the currently tagged one
                            if (freelancer.freelancer_id !== user.id && !addedFreelancers.has(freelancer.freelancer_id)) {
                                freelancerList.append(`
                                    <div class="post-freelancer" data-freelancer-id="${freelancer.freelancer_id}" data-post-id="${postId}">
                                        <img src="${freelancer.profile}" alt="${freelancer.firstname} ${freelancer.lastname}">
                                        <div class="post-freelancer-details">
                                            <h3>${freelancer.firstname} ${freelancer.lastname}</h3>
                                        </div>
                                    </div>
                                `);
                                addedFreelancers.add(freelancer.freelancer_id);
                            }
                        });
                    } else {
                        freelancerList.append('<span class="no-tagged-freelancers">No freelancers available for this post.</span>');
                    }

                    // Show the modal
                    $('#tagDetailsModal').show();
                } else {
                    Swal.fire('Error', responseData.message, 'error');
                }
            } catch (e) {
                console.error("Parsing error:", e, response);
                Swal.fire('Error', 'An unexpected error occurred.', 'error');
            }
        },
        error: function () {
            Swal.fire('Error', 'Unable to fetch tagged freelancer details.', 'error');
        }
    });
});


// Navigate to freelancer profile when "View Profile" button is clicked
$(document).on('click', '.modal-action-btn', function () {
    var freelancerId = $(this).data('freelancer-id');
    if (freelancerId) {
        window.location.href = `system-view-freelancer-profile.php?freelancer_id=${freelancerId}`;
    } else {
        Swal.fire('Error', 'Freelancer ID is missing.', 'error');
    }
});




$(document).ready(function () {
    // Attach click event listener to the delete button
    $(document).on('click', '.delete-post-button', function () {
        var postCard = $(this).closest('.card'); // Get the parent card container
        var postID = postCard.find('#post_id').val(); // Assuming post ID is stored in a hidden field

        // Show SweetAlert2 confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this post? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'small-swal-popup',
                icon: 'small-swal-icon',
                confirmButton: 'custom-confirm-button',
                cancelButton: 'custom-cancel-button',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Make an AJAX request to delete the post
                $.ajax({
                    url: 'system-delete-post.php', // Server-side script to handle deletion
                    type: 'POST',
                    data: { post_id: postID }, // Pass the post ID to the server
                    success: function (response) {
                        var result = JSON.parse(response); // Parse the JSON response

                        if (result.success) {
                            // Remove the post from the UI
                            postCard.remove();

                            // Show SweetAlert2 success message
                            Swal.fire('Deleted!', 'Post deleted successfully.', 'success');
                        } else {
                            Swal.fire('Error!', 'Failed to delete the post: ' + result.error, 'error');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', error);
                        Swal.fire('Error!', 'An error occurred while deleting the post.', 'error');
                    }
                });
            }
        });
    });
});



var currentIndex; // to keep track of the current image index

function openModal(images, index) {
    currentIndex = index; // Set current index
    $('#modalContent').empty(); // Clear previous content
    images.forEach(function(image) {
        $('#modalContent').append($('<img>').attr('src', image));
    });

    showImage(currentIndex); // Show the first image
    $('.prev').toggle(images.length > 1); // Show/hide prev button based on the number of images
    $('.next').toggle(images.length > 1); // Show/hide next button based on the number of images
    $('#multipleImageModal').css('display', 'block'); // Show modal
}

function showImage(index) {
    var images = $('#modalContent img'); // Get all images in modal
    if (index < 0) {
        currentIndex = images.length - 1; // Wrap around to last image
    } else if (index >= images.length) {
        currentIndex = 0; // Wrap around to first image
    } else {
        currentIndex = index; // Set current index
    }
    images.hide(); // Hide all images
    $(images[currentIndex]).show(); // Show the current image
}

// Close modal
$('.expand-image-modal-close').on('click', function() {
    $('#multipleImageModal').css('display', 'none');
});

// Navigate images
$('.prev').on('click', function() {
    showImage(currentIndex - 1);
});

$('.next').on('click', function() {
    showImage(currentIndex + 1);
});

// Keyboard navigation
$(document).keydown(function(e) {
    if ($('#multipleImageModal').is(':visible')) {
        if (e.key === 'ArrowLeft') {
            showImage(currentIndex - 1);
        } else if (e.key === 'ArrowRight') {
            showImage(currentIndex + 1);
        } else if (e.key === 'Escape') {
            $('#multipleImageModal').css('display', 'none');
        }
    }
});