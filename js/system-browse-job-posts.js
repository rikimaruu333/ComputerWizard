

$(document).ready(function () {
    // Fetch all posts on page load
    fetchFilteredPosts();

    // Filter functionality on selection change
    $('#filterJobCategory, #filterOrder, #filterAddress, #filterGender').change(function () {
        fetchFilteredPosts();
    });
});

function fetchFilteredPosts() {
    var filterJobCategory = $('#filterJobCategory').val();
    var filterOrder = $('#filterOrder').val();
    var filterAddress = $('#filterAddress').val();
    var filterGender = $('#filterGender').val();

    $.ajax({
        url: 'system-fetch-and-filter-job-posts.php', 
        type: 'GET',
        data: {
            category: filterJobCategory,
            order: filterOrder,
            address: filterAddress,
            gender: filterGender
        },
        dataType: 'json',
        success: function (response) {
            if (response && !response.error) {
                if (Array.isArray(response)) {
                    if (response.length > 0) {
                        populateCard(response);
                        $('.posts-with-image').show();
                        $('.posts-without-image').show();
                        $('.vertical-line').show();
                        $('#noPostsMessage').hide();
                    } else {
                        $('#noPostsMessage').show();
                        $('.posts-with-image').hide();
                        $('.posts-without-image').hide();
                        $('.vertical-line').hide();
                    }
                } else {
                    console.error('Error: Invalid posts data format');
                }
            } else {
                console.error('Error fetching posts:', response.error);
            }
        },
        error: function (xhr, status, error) {
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
        
        card.find('#postOwnerId').val(post.owner_id);
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

        card.find('p #jobCategory').text(post.job_category);
        card.find('p #jobNeeded').text(post.job);
        
        // Show or hide the card-config based on tagged freelancer
        if (post.hasTaggedFreelancer) {
            card.find('.bxs-user-circle').show(); // Show if there are tagged freelancers
        } else {
            card.find('.bxs-user-circle').hide(); // Hide otherwise
        }

        card.find('#viewCommentsBtn').attr('data-post-id', post.post_id);
        card.find('.comments').html(post.total_comments + ' comments');
        card.find('.userImg img').attr('src', post.currentUserProfile);
        card.find('#post_id').val(post.post_id);
        card.find('#user_id').val(post.currentUserID);
        card.find('#postTagDetailsBtn').attr('data-post-id', post.post_id);
        
        var formattedTime = moment(post.post_created).format('MMMM D, YYYY [at] h:mm A');
        card.find('.postTime').text(formattedTime); // Set the formatted time

        // Hide message if no caption
        if (!post.caption) {
            card.find('.message').hide();
        }

        // Conditionally show edit and delete buttons
        if (post.currentUserID === post.id) {
            card.find('.bxs-edit').show(); // Show edit button if the user is the owner of the post
            card.find('.bxs-trash').show(); // Show delete button if the user is the owner
        } else if (post.currentUserUsertype === 'Admin') { 
            card.find('.bxs-trash').show(); // Show delete button if the current user is an Admin
            card.find('.bxs-edit').hide(); // Admins cannot edit the post
        } else {
            card.find('.bxs-edit').hide(); // Hide edit button if the user is not the owner
            card.find('.bxs-trash').hide(); // Hide delete button if the user is neither the owner nor an admin
        }


        // Append the card to the appropriate container
        if (post.images.length > 0) {
            cardWithImageContainer.append(card);
        } else {
            cardWithoutImageContainer.append(card);
        }

        card.removeClass('postTemplate').show();
        
        // Close modal when the close button or outside the modal is clicked
        $('#tagDetailsModal .close, #tagDetailsModal').on('click', function(event) {
            if ($(event.target).is('#tagDetailsModal, .close')) {
                $('#tagDetailsModal').fadeOut();
            }
        });
        
        // Navigate to freelancer profile when "View Profile" button is clicked
        $('.modal-action-btn').on('click', function(event) {
            var freelancerId = $(this).data('freelancer-id'); // Get the freelancer ID from the data attribute
            var currentUserId = $('#user_id').val(); // Get the current user ID from the hidden input field

            // Convert both to the same type (e.g., string) for comparison
            freelancerId = String(freelancerId);
            currentUserId = String(currentUserId);

            if (freelancerId === currentUserId) {
                // Redirect to the freelancer's dashboard if the ID matches the current user
                window.location.href = 'freelancer-dashboard.php';
            } else {
                // Redirect to the freelancer's profile
                window.location.href = `system-view-freelancer-profile.php?freelancer_id=${freelancerId}`;
            }
        });

    });
}

$(document).on('click', '#postTagDetailsBtn', function () {
    var postId = $(this).data('post-id');
    console.log(postId);

    $.ajax({
        url: 'system-get-tagged-freelancer.php', // Adjust to the file path of your PHP script
        method: 'POST',
        data: { post_id: postId },
        success: function (response) {
            console.log("Raw response:", response);
            try {
                var responseData = JSON.parse(response);
    
                if (responseData.success) {
                    var taggedUser = responseData.data.taggedUser;
    
                    if (taggedUser) {
                        // Populate modal with user details
                        $('#tagDetailsModal .profile-img').attr('src', taggedUser.profile);
                        $('#tagDetailsModal .freelancer-name').text(taggedUser.firstname + ' ' + taggedUser.lastname);
                        $('#tagDetailsModal .tagged-text').text('Tagged freelancer');
    
                        // Add freelancer_id to the "View Profile" button as a data attribute
                        $('#tagDetailsModal .modal-action-btn').data('freelancer-id', taggedUser.id);
    
                        // Show the modal
                        $('#tagDetailsModal').show();
                    } else {
                        Swal.fire('Error', 'No tagged user found for this post.', 'info');
                    }
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




$(document).ready(function () {
    fetchFilteredPosts();

    // Filter functionality on selection change
    $('#filterJobCategory, #filterOrder, #filterAddress, #filterGender').change(function () {
        fetchFilteredPosts();
    });

    // Attach click event listener to the userDetails
    $(document).on('click', '.userDetails', function() {
        const clientId = $(this).closest('.card').find('#postOwnerId').val();
        window.location.href = `system-view-client-profile.php?client_id=${clientId}`;
    });
});


$(document).ready(function() {
    // Attach click event listener to the delete button
    $(document).on('click', '#deletePostBtn', function () {
        // Get the post ID from the clicked post's form or hidden input field
        var postCard = $(this).closest('.card'); // Get the parent card container
        var postID = postCard.find('#post_id').val(); // Assuming post ID is stored in a hidden field

        // Show confirmation dialog using SweetAlert2
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete this post?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel',
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

                            // Show SweetAlert success message
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Post has been deleted successfully.',
                                icon: 'success',
                                customClass: {
                                    popup: 'small-swal-popup',
                                    icon: 'small-swal-icon',
                                    confirmButton: 'custom-confirm-button',
                                }
                            });
                        } else {
                            // Show SweetAlert error message
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error deleting the post: ' + result.error,
                                icon: 'error',
                                customClass: {
                                    popup: 'small-swal-popup',
                                    icon: 'small-swal-icon',
                                    confirmButton: 'custom-confirm-button',
                                }
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', error);
                        // Show SweetAlert error message
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while deleting the post.',
                            icon: 'error',
                            customClass: {
                                popup: 'small-swal-popup',
                                icon: 'small-swal-icon',
                                confirmButton: 'custom-confirm-button',
                            }
                        });
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
