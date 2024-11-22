const urlParams = new URLSearchParams(window.location.search);
const clientId = urlParams.get('client_id');

$(document).ready(function() {
    if (!clientId) {
        alert('client ID not provided.');
        return; // This is now inside the document ready function
    }

    // Single AJAX request to fetch posts
    $.ajax({
        url: 'system-get-client-account-info.php',
        type: 'GET',
        data: { client_id: clientId },
        dataType: 'json',
        success: function(response) {
            if (response && !response.error) {
                populateClientInfo(response.client);

                if (Array.isArray(response.posts)) {
                    populateCard(response); // Fixed to pass the whole response
                } else {
                    console.error('Error: Invalid posts data format');
                }

                // Update transaction count
                $('#transactionCount').text(response.completed_transactions + ' transactions');

                const restrictionStatus = response.client.status;
                const iconContainer = $('#restrictButtonContainer');
                iconContainer.empty(); // Clear any existing icons

                // Append the appropriate icon based on restriction status
                if (restrictionStatus === '3') { // Restricted status
                    iconContainer.append('<i class="bx bxs-lock-open unrestrict-button" title="User is restricted. Click to unrestrict."></i>');
                } else { // Not restricted
                    iconContainer.append('<i class="bx bxs-no-entry restrict-button" title="User is not restricted. Click to restrict."></i>');
                }
            } else {
                console.error('Error fetching posts:', response.error);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching posts:', error);
        }
    });
});
function populateClientInfo(clientData) {
    $('#profile').attr('src', clientData.profile);
    $('#fullName').text(`${clientData.firstname} ${clientData.lastname}`);
    $('#email').text(clientData.email);

    $('#transactionCount').text(clientData.completed_transactions + ' transactions');

    const reportUserId = document.getElementById("reportUserId");
    const reportProfileImg = document.getElementById("reportProfileImg");
    const reportProfileName = document.getElementById("reportProfileName");
    const reportProfileUsertype = document.getElementById("reportProfileUsertype");

    reportUserId.value = clientData.id;
    reportProfileImg.src = clientData.profile ? clientData.profile : '../images/user.jpg';
    reportProfileName.innerText = `${clientData.firstname} ${clientData.lastname}`;
    reportProfileUsertype.innerText = clientData.usertype;
}


function populateCard(data) {
    var cardWithImageContainer = $('.posts-with-image');
    var cardWithoutImageContainer = $('.posts-without-image');
    var postTemplate = $('#postTemplate').html();

    // Clear existing posts
    cardWithImageContainer.empty();
    cardWithoutImageContainer.empty();

    // Populate user details
    $('#userTotalPosts').html(`<i class="bx bxs-book-content"></i> ${data.total_jobposts} posts`);

    data.posts.forEach(function(post) {
        var card = $(postTemplate).clone();

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

        card.find('#viewCommentsBtn').attr('data-post-id', post.post_id);
        card.find('.comments').html(post.total_comments + ' comments');
        card.find('.userImg img').attr('src', post.currentUserProfile);
        card.find('#post_id').val(post.post_id);
        card.find('#user_id').val(post.currentUserId);
        
        var formattedTime = moment(post.post_created).format('MMMM D, YYYY [at] h:mm A');
        card.find('.postTime').text(formattedTime); // Set the formatted time

        // Hide message if no caption
        if (!post.caption) {
            card.find('.message').hide();
        }
        // Conditionally show edit and delete buttons
        if (post.currentUserID === post.owner_id) {
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
    });
}

// Attach click event listener to the delete button
$(document).on('click', '.bxs-trash', function() {
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
                success: function(response) {
                    var result = JSON.parse(response); // Parse the JSON response

                    if (result.success) {
                        // Remove the post from the UI
                        postCard.remove();

                        // Show SweetAlert2 success message
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Post deleted successfully.',
                            icon: 'success',
                            customClass: {
                                popup: 'small-swal-popup',
                                icon: 'small-swal-icon',
                                confirmButton: 'custom-confirm-button',
                            }
                        });
                    } else {
                        // Show SweetAlert2 error message
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
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    // Show SweetAlert2 error message for AJAX error
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
