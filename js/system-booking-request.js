document.addEventListener('DOMContentLoaded', function() {
    // Select elements
    const bookingRequestListBtn = document.getElementById('bookingRequestListBtn');
    const bookingRequestModal = document.getElementById('bookingRequestModal');
    const closeBookingRequestModalBtn = document.getElementById('closeBookingRequestModalBtn');

    // Check if the elements exist
    if (bookingRequestListBtn && bookingRequestModal && closeBookingRequestModalBtn) {
        // Show the modal when "Booking Requests" is clicked
        bookingRequestListBtn.addEventListener('click', () => {
            bookingRequestModal.style.display = 'block';
            bookingRequestListBtn.classList.add('active');
        });

        // Hide the modal when the close button is clicked
        closeBookingRequestModalBtn.addEventListener('click', () => {
            bookingRequestModal.style.display = 'none';
            bookingRequestListBtn.classList.remove('active');
        });
    } else {
        console.error("One or more elements were not found in the DOM.");
    }
    

    // AJAX to fetch booking requests
    $(document).ready(function() {
        $('#bookingRequestListBtn').on('click', function() {
            $.ajax({
                url: 'system-fetch-booking-requests.php',
                type: 'GET',
                success: function(response) {
                    const data = JSON.parse(response);

                    if (data.success) {
                        const bookings = data.bookings;
                        const userId = data.user_id;

                        $('.booking-request-details-info').empty();

                        // Check if there are no bookings
                        if (bookings.length === 0) {
                            const isClient = data.user_usertype === 'Client'; // Assuming user_type is provided in the response
                            const message = isClient ? 'No booking requests sent.' : 'No booking requests received.';
                            $('.booking-request-details-info').append(`<p class="no-booking-request">${message}</p>`);
                        } else {
                            bookings.forEach(booking => {
                                const isClient = userId === booking.client_id;
                                const profileImage = isClient ? booking.freelancer_profile : booking.client_profile;
                                const userName = isClient ? booking.freelancer_name : booking.client_name;
                                const userType = isClient ? 'Freelancer' : 'Client';
                                const profileId = isClient ? booking.freelancer_id : booking.client_id; // Choose the correct profile ID
                                const profileUrl = isClient
                                    ? `system-view-freelancer-profile.php?freelancer_id=${profileId}`
                                    : `system-view-client-profile.php?client_id=${profileId}`;

                                const bookingHTML = `
                                    <div class="booking-request-details-user-info">
                                        <div class="booking-request-profile" data-profile-url="${profileUrl}">
                                            <img src="${profileImage}" alt="User Profile">
                                            <div class="details">
                                                <h3>${userName}</h3>
                                                <p>${userType}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="booking-request-buttons">
                                            ${
                                                userId === booking.freelancer_id
                                                    ? `
                                                        <div class="reject-request-button-container">
                                                            <i class="bx bx-x-circle reject-request-button" title="Reject Request" data-booking-id="${booking.booking_id}"></i>
                                                        </div>
                                                        <div class="accept-request-button-container">
                                                            <i class="bx bx-check-circle accept-request-button" title="Accept Request" data-booking-id="${booking.booking_id}"></i>
                                                        </div>
                                                    `
                                                    : `
                                                        <div class="cancel-request-button-container">
                                                            <i class="bx bx-x-circle cancel-request-button" title="Cancel Request" data-booking-id="${booking.booking_id}"></i>
                                                        </div>
                                                    `
                                            }
                                            <div class="info-request-button-container">
                                                <i class="bx bx-info-circle info-request-button" title="View Request Info" data-booking-id="${booking.booking_id}"></i>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                
                                $('.booking-request-details-info').append(bookingHTML);
                            });
                        }

                        // Event delegation to handle dynamic elements for viewing profiles
                        $(document).on('click', '.booking-request-profile', function() {
                            const profileUrl = $(this).data('profile-url');
                            window.location.href = profileUrl;
                        });

                        $('#bookingRequestModal').show();
                    } else {
                        toastr.error(data.error || "Failed to fetch bookings.");
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error("An error occurred. Please try again.");
                    console.error(xhr.responseText);
                }
            });
        });

        // Cancel Booking Request
        $(document).on('click', '.cancel-request-button', function () {
            const bookingId = $(this).data('booking-id');
            const button = $(this); // Store a reference to the clicked button

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to cancel this booking request?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No, keep it',
                customClass: {
                    popup: 'small-swal-popup',
                    icon: 'small-swal-icon',
                    confirmButton: 'custom-confirm-button',
                    cancelButton: 'custom-cancel-button',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'system-booking-request-cancellation.php',
                        type: 'POST',
                        data: { booking_id: bookingId },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Cancelled!', 'Booking request has been successfully cancelled.', 'success');
                                button.closest('.booking-request-details-user-info').remove();
                            } else {
                                Swal.fire('Error!', response.error || 'Failed to cancel booking request.', 'error');
                            }
                        },
                        error: function (xhr, status, error) {
                            Swal.fire('Error!', 'An error occurred. Please try again.', 'error');
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });

        // Reject Booking Request
        $(document).on('click', '.reject-request-button', function () {
            const bookingId = $(this).data('booking-id');
            const button = $(this);

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to reject this booking request?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, reject it!',
                cancelButtonText: 'No, keep it',
                customClass: {
                    popup: 'small-swal-popup',
                    icon: 'small-swal-icon',
                    confirmButton: 'custom-confirm-button',
                    cancelButton: 'custom-cancel-button',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'system-booking-request-rejection.php',
                        type: 'POST',
                        data: { booking_id: bookingId },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Rejected!', 'Booking request has been rejected.', 'success');
                                button.closest('.booking-request-details-user-info').remove();
                            } else {
                                Swal.fire('Error!', response.error || 'Failed to reject booking request.', 'error');
                            }
                        },
                        error: function (xhr, status, error) {
                            Swal.fire('Error!', 'An error occurred. Please try again.', 'error');
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });

        // Accept Booking Request
        $(document).on('click', '.accept-request-button', function () {
            const bookingId = $(this).data('booking-id');
            const button = $(this);

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to accept this booking request?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, accept it!',
                cancelButtonText: 'No, keep it',
                customClass: {
                    popup: 'small-swal-popup',
                    icon: 'small-swal-icon',
                    confirmButton: 'custom-confirm-question-button',
                    cancelButton: 'custom-cancel-button',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'system-booking-request-approval.php',
                        type: 'POST',
                        data: { booking_id: bookingId },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Accepted!', 'Booking request has been approved.', 'success');
                                $('.booking-request-details-info').empty(); // Adjust selector to clear the container
                            } else {
                                Swal.fire('Error!', response.error || 'Failed to approve booking request.', 'error');
                            }
                        },
                        error: function (xhr, status, error) {
                            Swal.fire('Error!', 'An error occurred. Please try again.', 'error');
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });

        
        
        $(document).on('click', '.info-request-button, .info-transaction-button, .info-ended-transaction-button', function() {
            const bookingId = $(this).data('booking-id');
            const isRequestButton = $(this).hasClass('info-request-button');
            const isEndedTransactionInfoButton = $(this).hasClass('info-ended-transaction-button');
            
            // Set header text based on the clicked button
            const headerText = isRequestButton ? 'Booking request details' : 'Transaction details';
            $('.booking-request-container-header h3').text(headerText);
        
            // Add or remove class based on button clicked
            if (isRequestButton) {
                $('#bookingRequestDetailsModal').removeClass('unpile'); // Remove class if request button is clicked
            } else if (isEndedTransactionInfoButton) {
                $('#bookingRequestDetailsModal').removeClass('unpile'); // Remove class if request button is clicked
            } else {
                $('#bookingRequestDetailsModal').addClass('unpile'); // Add class if transaction button is clicked

            }
        
            // Fetch booking details
            fetchBookingDetails(bookingId, isRequestButton);
        });
        
        function fetchBookingDetails(bookingId, isRequestButton) {
            $.ajax({
                url: 'system-get-booking-details.php',
                type: 'GET',
                data: { booking_id: bookingId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const booking = response.booking;
                        const userType = response.user_usertype;
                        const paymentType = booking.payment_type;
                        const bookingStatus = booking.booking_status;
        
                        // Check if user is a Client or Freelancer and populate profiles accordingly
                        if (userType === 'Client') {
                            // Populate client profile
                            const clientProfileUrl = `client-dashboard.php`;
                            $('#clientProfile').html(`
                                <img src="${booking.client_profile}" alt="User Profile">
                                <div class="details">
                                    <h3>${response.is_sender ? 'You' : booking.client_name}</h3>
                                    <p>Client</p>
                                </div>
                            `).data('profile-url', clientProfileUrl);
        
                            // Populate freelancer profile
                            const freelancerProfileUrl = `system-view-freelancer-profile.php?freelancer_id=${booking.freelancer_id}`;
                            $('#freelancerProfile').html(`
                                <img src="${booking.freelancer_profile}" alt="User Profile">
                                <div class="details">
                                    <h3>${response.is_receiver ? 'You' : booking.freelancer_name}</h3>
                                    <p>Freelancer</p>
                                </div>
                            `).data('profile-url', freelancerProfileUrl);
                        } else if (userType === 'Freelancer') {
                            // Populate client profile
                            const clientProfileUrl = `system-view-client-profile.php?client_id=${booking.client_id}`;
                            $('#clientProfile').html(`
                                <img src="${booking.client_profile}" alt="User Profile" class="client-profile-image">
                                <div class="details">
                                    <h3>${response.is_sender ? 'You' : booking.client_name}</h3>
                                    <p>Client</p>
                                </div>
                            `).data('profile-url', clientProfileUrl);
        
                            // Populate freelancer profile
                            const freelancerProfileUrl = `freelancer-dashboard.php`;
                            $('#freelancerProfile').html(`
                                <img src="${booking.freelancer_profile}" alt="User Profile" class="freelancer-profile-image">
                                <div class="details">
                                    <h3>${response.is_receiver ? 'You' : booking.freelancer_name}</h3>
                                    <p>Freelancer</p>
                                </div>
                            `).data('profile-url', freelancerProfileUrl);
                        }
        
                        // Determine the booking date content based on booking status
                        let bookingDateContent = '';
                        if (bookingStatus === 'Pending') {
                            bookingDateContent = `<p>Booking Date: <span>${booking.booking_date}</span></p>`;
                        } else if (bookingStatus === 'Approved') {
                            bookingDateContent = `<p>Start Date: <span>${booking.start_date}</span></p>`;
                        } else if (bookingStatus === 'Completed') {
                            bookingDateContent = `<p>Start Date: <span>${booking.start_date}</span></p>
                                                  <p>End Date: <span>${booking.end_date}</span></p>`;
                        }
        
                        // Populate booking details
                        $('#modalBookingDetails').html(`
                            <p>Service: <span>${booking.service}</span></p>
                            <p>Service Rate: <span>₱${booking.service_rate}</span></p>
                            <p>Job Type: <span>${booking.job_type}</span></p>
                            <p>Payment Type: <span>${paymentType}</span></p>
                            ${bookingDateContent} 
                        `);
        
                        // Display the correct payment note based on user role and payment type
                        let paymentNote = getPaymentNote(response, paymentType);
                        $('#modalBookingDetailsNote').empty().append(`
                            <p><i class="bx bx-info-circle"></i>Note: <span>${paymentNote}</span></p>
                        `);
        
                        // Fetch and populate the review for the booking
                        fetchClientReview(bookingId);
        
                        // Show the modal
                        $('#bookingRequestDetailsModal').show();
                    } else {
                        toastr.error(response.error || "Failed to fetch booking details.");
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error("An error occurred. Please try again.");
                    console.error(xhr.responseText);
                }
            });
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric', month: '2-digit', day: '2-digit'
            });
        }
        
        
        function fetchClientReview(bookingId) {
            $.ajax({
                url: 'system-get-client-review.php', // New PHP script to get review details
                type: 'GET',
                data: { booking_id: bookingId },
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.review) {
                        const review = response.review;
                        const starRating = '<i class="bx bxs-star"></i> '.repeat(review.rating);
                        $('.client-review-container').show();
        
                        // Get the logged-in user ID (already passed from PHP)
                        const loggedInUserId = review.loggedInUserId;
                        const reviewClientId = review.client_id;
        
                        // Check if the logged-in user is the same as the client
                        const clientName = (loggedInUserId === reviewClientId) ? 'You' : review.client_name;
        
                        // Populate client review container
                        $('.booking-request-details-info-container .client-review-container').html(`
                            <div class="client-info">
                                <div class="client-img">
                                    <img src="${review.profile || '../images/user.jpg'}" alt="">
                                </div>
                                <div class="client-details">
                                    <h3>${clientName}</h3>
                                    <i>Client</i>
                                </div>
                            </div>
                            <div class="client-rating">
                                <div class="star">
                                    ${starRating}
                                </div>
                                <p>${formatDate(review.review_date)}</p>
                            </div>
                            <div class="client-review">
                                <p>${review.review}</p>
                            </div>
                        `);
                    } else {
                        $('.booking-request-details-info-container .client-review-container').hide();
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error("An error occurred while fetching the review.");
                    console.error(xhr.responseText);
                }
            });
        }
        
        
        
        
        function getPaymentNote(response, paymentType) {
            if (response.is_sender) {
                return paymentType === 'Online'
                    ? 'You have selected Online Payment. Please coordinate directly with the freelancer for their online wallet details. Remember, GigHub will not be involved in handling or managing payments.'
                    : 'You have selected Onsite Payment. Payment will be made directly to the freelancer at the service location. GigHub does not facilitate or manage any payments. Verify all payment details directly with the freelancer.';
            } else {
                return paymentType === 'Online'
                    ? 'The payment type chosen by the client is Online. Kindly send your online wallet details directly to the client. Please note, GigHub will not be involved in handling or managing payments.'
                    : 'The payment type chosen by the client is Onsite. Payment will be made directly at the service location. Please be aware that GigHub does not handle or facilitate any payments.';
            }
        }
        
        // Event delegation for profile clicks
        $(document).on('click', '#clientProfile, #freelancerProfile', function() {
            const profileUrl = $(this).data('profile-url'); // Get the stored URL
            if (profileUrl) {
                window.location.href = profileUrl; // Redirect to the profile URL
            }
        });
        // Close modal when close button is clicked
        $('#closeBookingRequestDetailsModalBtn').on('click', function() {
            $('#bookingRequestDetailsModal').hide();
            $('#bookingRequestDetailsModal').removeClass('unpile'); // Remove class when modal is closed
        });

        fetchAcceptedBookings();

        function fetchAcceptedBookings() {
            fetch('system-fetch-transactions.php')
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        console.error('Error:', data.error);
                        return;
                    }
        
                    const transactionsContainer = document.querySelector('.transactions-container');
                    const transactionsList = document.querySelector('.transactions');
                    transactionsList.innerHTML = ''; // Clear previous content


                    // Check if there are any bookings
                    if (data.bookings.length === 0 && data.user_usertype === 'Client') {
                        // Create and append empty message for Clients only
                        const emptyMessage = document.createElement('p'); 
                        emptyMessage.classList.add('empty-message');
                        emptyMessage.textContent = 'No ongoing transactions available.';
                        transactionsContainer.appendChild(emptyMessage);
                        return; // Exit the function as there are no transactions to display
                    }

        
                    data.bookings.forEach(booking => {


                        const transactionCard = document.createElement('div');
                        transactionCard.classList.add('transaction-card');
                        transactionCard.title = "You have an ongoing transaction with this user.";
        
                        const infoIcon = document.createElement('i');
                        infoIcon.classList.add('bx', 'bx-info-circle', 'info-transaction-button');
                        infoIcon.title = "Click to view transaction details.";
                        infoIcon.setAttribute('data-booking-id', booking.booking_id); // Set booking ID for fetching details
        
                        // Check if the logged-in user is the client or freelancer
                        const userImage = document.createElement('img');
                        if (data.user_usertype === 'Client' && booking.client_id === data.user_id) {
                            userImage.src = booking.freelancer_profile ? `../images/${booking.freelancer_profile}` : '../images/default_user.jpg';
                            userImage.alt = booking.freelancer_name;
                        } else if (data.user_usertype === 'Freelancer' && booking.freelancer_id === data.user_id) {
                            userImage.src = booking.client_profile ? `../images/${booking.client_profile}` : '../images/default_user.jpg';
                            userImage.alt = booking.client_name;
                        }


                        // Create End Transaction button
                        const endTransactionButton = document.createElement('button');
                        endTransactionButton.classList.add('end-transaction-button');
                        endTransactionButton.textContent = 'End Transaction';
                        endTransactionButton.title = "Click to this end transaction.";
                        endTransactionButton.setAttribute('data-booking-id', booking.booking_id); // Set booking ID for ending the transaction
                       // Event listener to open the modal when "End Transaction" is clicked
                        
                            // Check if the transaction has already been ended by the user
                        if ((data.user_usertype === 'Client' && booking.client_ET_request === 'End') || 
                            (data.user_usertype === 'Freelancer' && booking.freelancer_ET_request === 'End')) {
                            // Disable the button and change its text
                            endTransactionButton.disabled = true;
                            endTransactionButton.textContent = 'Processing...';
                            endTransactionButton.title = 'Please wait for the other side to end this transaction.';
                        } else {
                            // Event listener to open the modal when "End Transaction" is clicked
                            endTransactionButton.addEventListener('click', function() {
                                showTransactionDetailsModal(booking, data.user_usertype);
                            });
                        }
        
                        transactionCard.appendChild(infoIcon);
                        transactionCard.appendChild(userImage);
                        transactionsList.appendChild(transactionCard);
                        transactionCard.appendChild(endTransactionButton);
        
                    });
                })
                .catch(error => console.error('Error fetching accepted bookings:', error));
        }

        // Function to show transaction details modal with review options
        function showTransactionDetailsModal(bookingId, userType) {
            const modal = document.getElementById('transactionInfoDetailsModal');
            modal.style.display = 'block';
            console.log(userType);

            // Store bookingId in a data attribute on the modal for reference
            modal.setAttribute('data-booking-id', bookingId);

            if (userType === 'Client') {
                // Show client note and review form
                document.getElementById('modalTransactionDetailsNote').innerHTML = `
                    <p><i class="bx bx-info-circle"></i>Note: <span>Please ensure that the job has been completed by the freelancer before completing the transaction. After completion, we encourage you to leave a rating and review for the freelancer to help build trust and improve future services.</span></p>
                `;
            } else if (userType === 'Freelancer') {
                // Show freelancer note and hide review form
                document.getElementById('modalTransactionDetailsNote').innerHTML = `
                    <p><i class="bx bx-info-circle"></i>Note: <span>Please ensure that the payment was released by the client before completing the transaction.</span></p>
                `;
            }
        
            // Event listener to close the modal
            document.getElementById('closeTransactionInfoDetailsModalBtn').addEventListener('click', function() {
                modal.style.display = 'none';
            });
        }

        // Event listener for the End Transaction button
        $(document).on('click', '#endTransactionButton', function() {
            const bookingId = $('#transactionInfoDetailsModal').data('booking-id');
            
            endTransaction(bookingId);
        });

        // Function to end transaction (for freelancers)
        function endTransaction(bookingId) {
            $.ajax({
                url: 'system-freelancer-end-transaction.php',
                type: 'POST',
                data: { booking_id: bookingId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.info("Transaction ended successfully.");
                        // Optionally reload transactions
                        fetchAcceptedBookings();
                    } else {
                        toastr.error(response.message || "Failed to end transaction.");
                    }
                    // Close the modal after success/failure
                    $('#transactionInfoDetailsModal').hide();
                },
                error: function(xhr, status, error) {
                    toastr.error("An error occurred. Please try again.");
                    console.error(xhr.responseText);
                }
            });
        }
        // Function to send review and end transaction request together
        function submitReviewAndEndTransaction(bookingId, reviewContent, rating, recommendation) {
            $.ajax({
                url: 'system-review-submission-end-transaction.php',
                type: 'POST',
                data: {
                    booking_id: bookingId,
                    review: reviewContent,
                    rating: rating,
                    recommendation: recommendation
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success("Review submitted and transaction ended successfully.");
                        // Optionally reload transactions
                        fetchAcceptedBookings();
                    } else {
                        toastr.error(response.message || "Failed to submit review and end transaction.");
                    }
                    // Close the modal after success/failure
                    document.getElementById('transactionInfoDetailsModal').style.display = 'none';
                },
                error: function(xhr, status, error) {
                    toastr.error("An error occurred. Please try again.");
                    console.error(xhr.responseText);
                }
            });
        }

        // Event listener for End Transaction button
        $(document).on('click', '.end-transaction-button', function() {
            const bookingId = $(this).data('booking-id');
            showTransactionDetailsModal(bookingId);
        });

        // Event listener for the Submit Review button inside the modal
        $(document).on('click', '#transactionInfoDetailsModal .btn button', function(event) {
            event.preventDefault();

            // Get bookingId from modal's data attribute
            const modal = document.getElementById('transactionInfoDetailsModal');
            const bookingId = modal.getAttribute('data-booking-id');

            // Get rating and review content
            const rating = document.querySelector('input[name="rate"]:checked') ? document.querySelector('input[name="rate"]:checked').value : null;
            const reviewContent = document.querySelector('#transactionInfoDetailsModal textarea').value;
            const recommendation = document.querySelector('input[name="recommendation"]').checked ? 'Recommended' : 'Not Recommended';

            // Ensure review and rating are provided
            if (rating && reviewContent) {
                submitReviewAndEndTransaction(bookingId, reviewContent, rating, recommendation);
            } else {
                toastr.error("Both review content and rating are required.");
            }
        });
    });


   
    $(document).ready(function() {
        // Open the modal and fetch transaction data when the button is clicked
        $('#endedTransactionListBtn').on('click', function() {
            $.ajax({
                url: 'system-fetch-ended-transactions.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const transactions = response.bookings;
                        const userId = response.user_id;
                        const userType = response.user_usertype;
                        // Clear previous transactions
                        $('.transaction-details-details-info').empty();
    
                        if (transactions.length === 0) {
                            const message = 'No ended transactions.';
                            $('.transaction-details-details-info').append(`<p class="no-booking-request">${message}</p>`);
                        } else {
                        // Populate with new transactions
                            transactions.forEach(function(booking) {
                                const isSender = (booking.client_id === userId && userType === 'Client');
                                const isReceiver = (booking.freelancer_id === userId && userType === 'Freelancer');
        
                                let clientProfileHTML, freelancerProfileHTML;
        
                                // Determine user type to populate profiles accordingly
                                if (userType === 'Client') {
                                    // Populate client profile (user's profile)
                                    clientProfileHTML = `
                                        <div class="transaction-details-profile" data-profile-url="client-dashboard.php">
                                            <img src="${booking.client_profile}" alt="User Profile">
                                            <div class="details">
                                                <h3>${isSender ? 'You' : booking.client_name}</h3>
                                                <p>Client</p>
                                            </div>
                                        </div>
                                    `;
        
                                    // Populate freelancer profile (other user's profile)
                                    freelancerProfileHTML = `
                                        <div class="transaction-details-profile" data-profile-url="system-view-freelancer-profile.php?freelancer_id=${booking.freelancer_id}">
                                            <img src="${booking.freelancer_profile}" alt="User Profile">
                                            <div class="details">
                                                <h3>${isReceiver ? 'You' : booking.freelancer_name}</h3>
                                                <p>Freelancer</p>
                                            </div>
                                        </div>
                                    `;
        
                                    // Wrap each transaction in a container
                                    const transactionHTML = `
                                        <div class="transaction-details-user-info info">
                                            ${clientProfileHTML}
                                            <div class="transaction-line-img-container">
                                                <img class="line-img" src="../images/pulse.png" alt="">
                                            </div>
                                            ${freelancerProfileHTML}
                                            <div class="ended-transaction-buttons">
                                                <div class="info-ended-transaction-button-container">
                                                    <i class="bx bx-info-circle info-ended-transaction-button" title="View Transaction Details" data-booking-id="${booking.booking_id}"></i>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    // Append profiles to the container
                                    $('.transaction-details-details-info').append(transactionHTML);

                                } else if (userType === 'Freelancer') {
                                    // Populate client profile (other user's profile)
                                    clientProfileHTML = `
                                        <div class="transaction-details-profile" data-profile-url="system-view-client-profile.php?client_id=${booking.client_id}">
                                            <img src="${booking.client_profile}" alt="User Profile">
                                            <div class="details">
                                                <h3>${isSender ? 'You' : booking.client_name}</h3>
                                                <p>Client</p>
                                            </div>
                                        </div>
                                    `;
        
                                    // Populate freelancer profile (user's profile)
                                    freelancerProfileHTML = `
                                        <div class="transaction-details-profile" data-profile-url="freelancer-dashboard.php">
                                            <img src="${booking.freelancer_profile}" alt="User Profile">
                                            <div class="details">
                                                <h3>${isReceiver ? 'You' : booking.freelancer_name}</h3>
                                                <p>Freelancer</p>
                                            </div>
                                        </div>
                                    `;
        
                                    // Wrap each transaction in a container
                                    const transactionHTML = `
                                        <div class="transaction-details-user-info info">
                                            ${freelancerProfileHTML}
                                            <div class="transaction-line-img-container">
                                                <img class="line-img" src="../images/pulse.png" alt="">
                                            </div>
                                            ${clientProfileHTML}
                                            <div class="ended-transaction-buttons">
                                                <div class="info-ended-transaction-button-container">
                                                    <i class="bx bx-info-circle info-ended-transaction-button" title="View Transaction Details" data-booking-id="${booking.booking_id}"></i>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    // Append profiles to the container
                                    $('.transaction-details-details-info').append(transactionHTML);
                                }
        
                            });
                        }
                        // Show the modal
                        $('#endedTransactionListModal').show();
                        $('#endedTransactionListBtn').addClass('active');
    
                        // Add click event to each profile for redirection
                        $('.transaction-details-profile').off('click').on('click', function() {
                            const profileUrl = $(this).data('profile-url');
                            window.location.href = profileUrl;
                        });
                    } else {
                        console.error('Error fetching transactions:', response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching transactions:', error);
                }
            });
        });
    
        // Close the modal when the close button is clicked
        $('#closeEndedTransactionListModalBtn').on('click', function() {
            $('#endedTransactionListModal').hide();
            $('#endedTransactionListBtn').removeClass('active');

        });
    });
    
    
    
});
