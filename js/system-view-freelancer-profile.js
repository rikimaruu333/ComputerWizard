document.addEventListener("DOMContentLoaded", function () {
    
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "15000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    const urlParams = new URLSearchParams(window.location.search);
    const freelancerId = urlParams.get('freelancer_id');

    if (!freelancerId) {
        alert('Freelancer ID not provided.');
        return;
    }


    const toggleAlbum = document.querySelector('.toggle-album');
    const toggleTagged = document.querySelector('.toggle-tagged');
    const albumImageContainer = document.querySelector('.album-container');
    const postsContainer = document.querySelector('.posts-container');

    // Function to update UI based on the current toggle state
    function updateUI() {
        const activeToggle = localStorage.getItem('activeToggle');

        if (activeToggle === 'tagged') {
            toggleTagged.classList.add('active');
            toggleAlbum.classList.remove('active');
            postsContainer.style.display = 'block';
            albumImageContainer.style.display = 'none';
        } else {
            toggleAlbum.classList.add('active');
            toggleTagged.classList.remove('active');
            albumImageContainer.style.display = 'block';
            postsContainer.style.display = 'none';
        }
    }

    // Set initial visibility based on local storage
    updateUI();

    // Add event listeners for toggles
    toggleAlbum.addEventListener('click', () => {
        // Update classes for toggles
        toggleAlbum.classList.add('active');
        toggleTagged.classList.remove('active');

        // Show album container and hide posts container
        albumImageContainer.style.display = 'block';
        postsContainer.style.display = 'none';

        // Save the state to local storage
        localStorage.setItem('activeToggle', 'album');
    });

    toggleTagged.addEventListener('click', () => {
        // Update classes for toggles
        toggleTagged.classList.add('active');
        toggleAlbum.classList.remove('active');

        // Show posts container and hide album container
        postsContainer.style.display = 'block';
        albumImageContainer.style.display = 'none';

        // Save the state to local storage
        localStorage.setItem('activeToggle', 'tagged');
    });
    // If you want to reset the state back to default at some point (e.g., a logout action), you can clear the localStorage by calling:

    // localStorage.removeItem('activeToggle');

    fetchFreelancerAccountInfo(freelancerId);
    setupServiceModal(freelancerId);
    setupScheduleModal(freelancerId);
    setupAlbumScroll();
    fetchPosts();

    // Function to fetch freelancer account info
    function fetchFreelancerAccountInfo(freelancerId) {
        $.ajax({
            url: 'system-get-freelancer-account-info.php',
            type: 'GET',
            data: { freelancer_id: freelancerId },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    setFreelancerInfo(response.data.freelancer);
                    updateAlbumImages(response.data.albumImages);

                    // Display recommendation count
                    const recommendationCount = response.data.recommendationCount || 0;
                    document.querySelector(".freelancer-details .recommendation-count").innerText = `${recommendationCount} recommendations`;

                    // Check the restriction status and display the correct icon
                    const restrictionStatus = response.data.freelancer.status;
                    const iconContainer = $('#restrictButtonContainer');
                    iconContainer.empty(); // Clear any existing icons
    
                    // Append the appropriate icon based on restriction status
                    if (restrictionStatus === '3') { // Restricted status
                        iconContainer.append('<i class="bx bxs-lock-open unrestrict-button" title="User is restricted. Click to unrestrict."></i>');
                    } else { // Not restricted
                        iconContainer.append('<i class="bx bxs-no-entry restrict-button" title="User is not restricted. Click to restrict."></i>');
                    }
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('Error fetching freelancer account info');
            }
        });
    }
    
    // Function to set freelancer info in the UI
    function setFreelancerInfo(data) {
        const profileImg = document.querySelector(".freelancer-img #profile");
        const fullNameElem = document.querySelector(".freelancer-details #fullName");
        const emailElem = document.querySelector(".freelancer-details #email");
        // Set profile image
        profileImg.src = data.profile ? data.profile : '../images/user.jpg';

        // Set freelancer name and email
        fullNameElem.innerText = `${data.firstname} ${data.lastname}`;
        emailElem.innerText = data.email;


        const reportUserId = document.getElementById("reportUserId");
        const reportProfileImg = document.getElementById("reportProfileImg");
        const reportProfileName = document.getElementById("reportProfileName");
        const reportProfileUsertype = document.getElementById("reportProfileUsertype");

        reportUserId.value = data.id;
        reportProfileImg.src = data.profile ? data.profile : '../images/user.jpg';
        reportProfileName.innerText = `${data.firstname} ${data.lastname}`;
        reportProfileUsertype.innerText = data.usertype;
    }

    // Function to update album images in the UI
    function updateAlbumImages(images) {
        const albumContainer = document.querySelector('.album-images-container');
        albumContainer.innerHTML = '';

        const defaultImageSrc = "../images/default-image.jpg";
        const imagesPerRow = 4; // Define how many images per row
        const totalImages = images.length;
        const totalRows = Math.ceil(totalImages / imagesPerRow);
        const totalImagesToShow = totalRows * imagesPerRow; // Total slots to fill

        const template = document.getElementById('imageTemplate');

        for (let i = 0; i < totalImagesToShow; i++) {
            const clone = document.importNode(template.content, true);
            const imgElement = clone.querySelector('img');
            const expandBtn = clone.getElementById('expandBtn');
            const albumIdInput = clone.querySelector('input[name="albumId"]');

            if (i < totalImages) {
                // Populate with actual album images
                imgElement.src = "../album/" + images[i].album_img;
                albumIdInput.value = images[i].album_id; // Set the album ID
                expandBtn.style.visibility = "visible"; // Show the expand button
            } else {
                // Populate with default images for empty slots
                imgElement.src = defaultImageSrc;
                albumIdInput.value = "";
                expandBtn.style.visibility = "hidden"; // Hide the expand button for default images
            }

            albumContainer.appendChild(clone);
        }
    }


  

    // Function to set up the album scroll functionality
    function setupAlbumScroll() {
        const albumContainer = document.querySelector('.album-images-container');
        const albumScrollArrow = document.getElementById("albumScrollArrow");

        // Function to check if the album is scrollable and update the arrow's visibility
        function checkScrollability() {
            const isScrollable = albumContainer.scrollHeight > albumContainer.clientHeight;
            const isAtBottom = albumContainer.scrollHeight - albumContainer.scrollTop === albumContainer.clientHeight;

            albumScrollArrow.style.display = isScrollable ? 'block' : 'none'; // Show or hide the arrow
            albumScrollArrow.classList.toggle("up-arrow", isAtBottom); // Add class for up arrow if at bottom
        }

        // Event listener for scroll arrow click
        albumScrollArrow.addEventListener("click", function () {
            if (this.classList.contains("up-arrow")) {
                // Scroll to the top smoothly
                albumContainer.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            } else {
                // Scroll to the bottom smoothly
                albumContainer.scrollTo({
                    top: albumContainer.scrollHeight,
                    behavior: 'smooth'
                });
            }
            checkScrollability(); // Update scrollability after the action
        });

        // Update scrollability when the user scrolls manually
        albumContainer.addEventListener('scroll', function () {
            checkScrollability(); // Check scrollability on manual scroll
        });
    }

    // Function to set up service modal functionality
    function setupServiceModal(freelancerId) {
        const serviceModal = document.getElementById("viewProfileServiceModal");
        const openServiceModalBtn1 = document.getElementById("viewProfileOpenServiceModalBtn");
        const closeServiceModalBtn = document.getElementById("view-profile-close-service-modal");

        openServiceModalBtn1.onclick = function () {
            serviceModal.style.display = "block";
            openServiceModalBtn1.classList.add("active");
            fetchServicesForFreelancer(freelancerId); // Fetch services when modal opens
        }

        closeServiceModalBtn.onclick = function () {
            serviceModal.style.display = "none";
            openServiceModalBtn1.classList.remove("active");
        }



        
        function appendServiceToTable(service) {
            const { hasOngoingBooking, alreadyBookedByClient, is_available } = service;

            let bookingStatus = 'Book Service';
            let statusClass = '';
            if (!is_available) {
                bookingStatus = 'Unavailable';
                statusClass = 'unavailable';
            } else if (hasOngoingBooking) {
                bookingStatus = 'Ongoing Booking';
                statusClass = 'unavailable';
            } else if (alreadyBookedByClient) {
                bookingStatus = 'Already Booked';
                statusClass = 'unavailable';
            }

            let serviceRow = `
                <tr id="service-${service.service_id}" data-id="${service.service_id}">
                    <td>${service.service}</td>
                    <td>₱${service.service_rate}</td>
                    ${isClient ? `
                        <td class="service-config">
                            <i class="bx bxs-bookmark-alt-plus"></i>
                            <u class="service-book-config ${statusClass}" 
                            data-id="${service.service_id}" 
                            data-name="${service.service}" 
                            data-rate="${service.service_rate}">
                            ${bookingStatus}
                            </u>
                        </td>` : ''}
                </tr>
            `;
            $('#viewProfileServiceTableBody').append(serviceRow);
    
            const bookButton = $(`#service-${service.service_id} .service-book-config`);
    
            if (statusClass === 'unavailable') {
                bookButton.off('click');
            } else {
                bookButton.on('click', function() {
                    const serviceId = $(this).data('id');
                    fetchServiceDetails(serviceId);
                });
            }
        }
        

        function fetchServiceDetails(serviceId) {
            $.ajax({
                url: 'system-get-freelancer-service-details.php', // Adjust to your PHP file path
                type: 'GET',
                data: { service_id: serviceId },
                success: function(response) {
                    if (response.error) {
                        console.error(response.error);
                        alert(response.error);
                    } else {
                        populateBookingModal(response.service, response.freelancer);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching service details: ", error);
                    alert('Could not fetch service details. Please try again.');
                }
            });
        }
        


        // Fetch services for the selected freelancer
        function fetchServicesForFreelancer(freelancerId) {
            $('#viewProfileServiceTableBody').empty();
    
            $.ajax({
                url: 'system-get-freelancer-service-list.php',
                type: 'GET',
                data: { freelancer_id: freelancerId },
                dataType: 'json',
                success: function(response) {
                    if (Array.isArray(response)) {
                        $('#serviceTableBody').empty();
                        response.forEach(function(service) {
                            appendServiceToTable(service);
                        });
                    } else if (response.error) {
                        toastr.error('Error fetching services: ' + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    toastr.error('Failed to fetch services. Please try again.');
                }
            });
        }
        
        function populateBookingModal(service, freelancer) {
            // Populate modal with freelancer and service details
            $('#bookingUserId').val(service.freelancer_id); // Assuming the freelancer ID is available
            $('#bookingServiceId').val(service.service_id); // Assuming the freelancer ID is available
            $('#bookingUserProfile').attr('src', freelancer.profile);
            $('#bookingUserName').text(freelancer.firstname + ' ' + freelancer.lastname);
            $('#bookingUserType').text(freelancer.usertype);
            $('#bookingService').text(service.service);
            $('#bookingRate').text(service.service_rate);
        
            // Open the modal
            $('#bookingModal').show();
        }
        
        // Close modal function
        $('#closeBookingModalBtn').on('click', function() {
            $('#bookingModal').hide();
        });

        $(document).ready(function() {
            // Check if the booking success flag is set in localStorage
            if (localStorage.getItem('bookingSuccess') === 'true') {
                toastr.info('Booking Successful!'); // Show success message
                localStorage.removeItem('bookingSuccess'); // Clear the flag
            }
        });
        
        // Booking submission with validation
        $('#bookingUserBtn').on('click', function(event) {
            event.preventDefault(); // Prevent default form submission
        
            // Collect booking information from modal fields
            const freelancerId = $('#bookingUserId').val();
            const serviceId = $('#bookingServiceId').val();
            const jobType = $('input[name="jobType"]:checked'); // Checked job type
            const paymentType = $('input[name="payment"]:checked'); // Checked payment type
        
            // Ensure both job type and payment type are selected
            if (!jobType.length || !paymentType.length) {
                toastr.error('Please select a job type and payment option.');
                return;
            }
        
            // Prepare booking data for submission
            const bookingData = {
                freelancer_id: freelancerId,
                service_id: serviceId,
                job_type: jobType.val(),
                payment_type: paymentType.val()
            };
        
            // Submit booking data via AJAX
            $.ajax({
                url: 'system-booking-submission.php',
                type: 'POST',
                data: bookingData,
                success: function(response) {
                    response = JSON.parse(response); // Parse JSON response
                    if (response.success) {
                        localStorage.setItem('bookingSuccess', 'true');
                        window.location.reload(); // Reload the page
                    } else {
                        toastr.error("Booking failed: " + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    toastr.error("An error occurred. Please try again.");
                }
            });
        });
        

    }

    function setupScheduleModal(freelancerId) {
        const scheduleModal = document.getElementById("viewProfileScheduleModal");
        const openScheduleModalBtn = document.getElementById("viewProfileOpenScheduleModalBtn");
        const closeScheduleModalBtn = document.getElementById("view-profile-close-schedule-modal");

        openScheduleModalBtn.onclick = function () {
            scheduleModal.style.display = "block";
            openScheduleModalBtn.classList.add("active");
            fetchFreelancerSchedules(freelancerId); // Fetch schedules when modal opens
        };

        closeScheduleModalBtn.onclick = function () {
            scheduleModal.style.display = "none";
            openScheduleModalBtn.classList.remove("active");
        };

        // Function to fetch freelancer schedule
        function fetchFreelancerSchedules(freelancerId) {
            // Clear the existing schedules before making the AJAX request
            $('#viewProfileScheduleTableBody').empty();

            $.ajax({
                url: 'system-get-freelancer-schedule-list.php',
                type: 'GET',
                data: { freelancer_id: freelancerId },
                dataType: 'json',
                success: function (response) {
                    if (Array.isArray(response)) {
                        $('#scheduleTableBody').empty(); // Clear the existing schedule table
                        
                        // Define a mapping for the days of the week to ensure correct sorting
                        const dayOrder = {
                            "Monday": 1,
                            "Tuesday": 2,
                            "Wednesday": 3,
                            "Thursday": 4,
                            "Friday": 5,
                            "Saturday": 6,
                            "Sunday": 7
                        };
        
                        // Sort the schedules according to the day order
                        response.sort((a, b) => dayOrder[a.day] - dayOrder[b.day]);
        
                        // Append each sorted schedule row
                        response.forEach(function (schedule) {
                            appendScheduleToTable(schedule);
                        });
                    } else if (response.error) {
                        toastr.error('Error fetching schedules: ' + response.error);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    toastr.error('Failed to fetch schedules. Please try again.');
                }
            });
        }
        

        // Function to append a schedule item to the schedule container
        function appendScheduleToTable(schedule) {
            const formattedTimeIn = formatTime(schedule.time_in);   // Format time_in
            const formattedTimeOut = formatTime(schedule.time_out); // Format time_out
        
            let scheduleRow = `
                <tr id="schedule-${schedule.schedule_id}" data-id="${schedule.schedule_id}">
                    <td>${schedule.day}</td>
                    <td>${formattedTimeIn}</td>
                    <td>${formattedTimeOut}</td>
                </tr>
            `;
            $('#viewProfileScheduleTableBody').append(scheduleRow);
        }

        function formatTime(time) {
            const [hours, minutes] = time.split(':');
            const hoursInt = parseInt(hours, 10);
            const ampm = hoursInt >= 12 ? 'PM' : 'AM';
            const formattedHours = hoursInt % 12 || 12; // Converts 0 to 12
            return `${formattedHours}:${minutes} ${ampm}`; 
        }
        
    }
    
    const modal = document.getElementById("imageModal");
    const fullImage = document.getElementById("fullImage");
    const closeBtn = document.getElementById("expandCloseBtn");
    const deleteBtn = document.getElementById("deleteAlbumImage");

    document.querySelector('.album-images-container').addEventListener('click', function(event) {
        if (event.target.closest('#expandBtn')) {
            const imgElement = event.target.closest('.album-imgBg').querySelector('img.thumbnail');
            const imgSrc = imgElement.src;
            const albumId = event.target.closest('.album-imgBg').querySelector('input[name="albumId"]').value;

            modal.style.display = "block";
            fullImage.src = imgSrc; 

            deleteBtn.dataset.albumId = albumId;
            deleteBtn.dataset.freelancerId = freelancerId; 
        }
    });

    closeBtn.addEventListener("click", function() {
        modal.style.display = "none";
    });

    window.addEventListener("click", function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    deleteBtn.addEventListener("click", function() {
        const albumId = this.dataset.albumId; 
        const freelancerId = this.dataset.freelancerId;  // Get the freelancerId
    
        // Show SweetAlert2 confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this image from the freelancer album? This action cannot be undone.',
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
                // Make the AJAX request to delete the image
                $.ajax({
                    url: 'system-delete-freelancer-album.php',
                    type: 'POST',
                    data: { 
                        album_id: albumId,
                        freelancer_id: freelancerId  // Pass the freelancerId here
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            // Show SweetAlert2 success message
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success',
                                customClass: {
                                    popup: 'small-swal-popup',
                                    icon: 'small-swal-icon',
                                    confirmButton: 'custom-confirm-button',
                                }
                            });
    
                            modal.style.display = "none"; // Hide the modal
                            fetchFreelancerAccountInfo(freelancerId);  // Refresh album images after delete
                        } else {
                            // Show SweetAlert2 error message
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                customClass: {
                                    popup: 'small-swal-popup',
                                    icon: 'small-swal-icon',
                                    confirmButton: 'custom-confirm-button',
                                }
                            });
                        }
                    },
                    error: function() {
                        // Show SweetAlert2 error message for AJAX error
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while deleting the image.',
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




    function displayNoTaggedPostsMessage() {
        $('.posts-container').html(`
            <div class="no-posts">
                <p>No tagged posts found.</p>
            </div>
        `);
    }
    function fetchPosts() {
        $.ajax({
            url: 'system-view-freelancer-fetch-tagged-posts.php',
            type: 'GET',
            data: { freelancer_id: freelancerId },
            dataType: 'json',
            success: function(response) {
                if (response && !response.error) {
                    if (Array.isArray(response) && response.length > 0) {
                        populateCard(response);
                    } else {
                        displayNoTaggedPostsMessage();
                    }
                } else {
                    console.error('Error fetching posts:', response.error);
                    displayNoTaggedPostsMessage();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching posts:', error);
                displayNoTaggedPostsMessage();
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

            var infoButton = `<i class="bx bx-info-circle" data-post-id="${post.post_id}" id="postTagDetailsBtn" title="Show post tag details"></i>`;

            
            if (post.isTagged) {
                // Post is tagged, append the info button instead of the tag button
                card.find('.right').append(infoButton);
            } 
                    
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


            card.find('#viewCommentsBtn').attr('data-post-id', post.post_id);
            card.find('#postTagDetailsBtn').attr('data-post-id', post.post_id);
            card.find('.userImg img').attr('src', post.currentUserProfile);
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

            card.removeClass('postTemplate').show();

            // Close modal when the close button or outside the modal is clicked
            $('#tagDetailsModal .close, #tagDetailsModal').on('click', function(event) {
                if ($(event.target).is('#tagDetailsModal, .close')) {
                    $('#tagDetailsModal').fadeOut();
                }
            });
        });
    }

    // Attach click event listener to the userDetails
    $(document).on('click', '.userDetails', function() {
        const clientId = $(this).closest('.card').find('#postOwnerId').val();
        console.log(clientId);
        window.location.href = `system-view-client-profile.php?client_id=${clientId}`;
    });

    
    
    var currentIndex; // to keep track of the current image index

    function openModal(images, index) {
        currentIndex = index; // Set current index
        $('#multipleImageModal #modalContent').empty(); // Clear previous content
        images.forEach(function(image) {
            $('#multipleImageModal #modalContent').append($('<img>').attr('src', image));
        });
    
        showImage(currentIndex); // Show the first image
        $('#multipleImageModal .prev').toggle(images.length > 1); // Show/hide prev button based on the number of images
        $('#multipleImageModal .next').toggle(images.length > 1); // Show/hide next button based on the number of images
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
    $('#multipleImageModal .expand-multi-image-modal-close').on('click', function() {
        $('#multipleImageModal').css('display', 'none');
    });
    
    // Navigate images
    $('#multipleImageModal .prev').on('click', function() {
        showImage(currentIndex - 1);
    });
    
    $('#multipleImageModal .next').on('click', function() {
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





    
    
});




