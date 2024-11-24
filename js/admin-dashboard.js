document.addEventListener("DOMContentLoaded", function () {
    // Elements for ongoing and ended transactions toggles
    const toggleOngoing = document.querySelector('.toggle-ongoing');
    const toggleEnded = document.querySelector('.toggle-ended');
    const ongoingTransactionContainer = document.querySelector('.ongoing-transaction-container');
    const endedTransactionContainer = document.querySelector('.ended-transaction-container');
    const adminDashboard = document.querySelector('.admin-dashboard'); // Container to scroll

    // Function to update UI based on the current toggle state
    function updateUI() {
        const activeTransactionToggle = localStorage.getItem('activeTransactionToggle');
        if (activeTransactionToggle === 'ended') {
            toggleOngoing.classList.remove('active');
            toggleEnded.classList.add('active');
            ongoingTransactionContainer.style.display = 'none';
            endedTransactionContainer.style.display = 'block';
        } else {
            toggleOngoing.classList.add('active');
            toggleEnded.classList.remove('active');
            ongoingTransactionContainer.style.display = 'block';
            endedTransactionContainer.style.display = 'none';
        }
    }

    // Set initial visibility based on local storage
    updateUI();

    // Function to scroll to the bottom of .admin-dashboard with smooth effect
    function scrollToBottom() {
        adminDashboard.scrollTo({
            top: adminDashboard.scrollHeight,
            behavior: "smooth"
        });
    }

    // Sub-toggles for Ongoing and Ended Transactions
    toggleOngoing.addEventListener('click', () => {
        toggleOngoing.classList.add('active');
        toggleEnded.classList.remove('active');
        ongoingTransactionContainer.style.display = 'block';
        endedTransactionContainer.style.display = 'none';
        localStorage.setItem('activeTransactionToggle', 'ongoing');
        scrollToBottom(); // Smooth scroll to the bottom on toggle
    });

    toggleEnded.addEventListener('click', () => {
        toggleEnded.classList.add('active');
        toggleOngoing.classList.remove('active');
        ongoingTransactionContainer.style.display = 'none';
        endedTransactionContainer.style.display = 'block';
        localStorage.setItem('activeTransactionToggle', 'ended');
        scrollToBottom(); // Smooth scroll to the bottom on toggle
    });

    $(document).ready(function() {
        // AJAX request to fetch all freelancer requests
        $.ajax({
            url: 'admin-fetch-freelancer-requests.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Iterate through fetched data and populate HTML
                $.each(response, function(index, freelancer) {
                    // Concatenate firstname and lastname to get full name
                    var fullname = freelancer.firstname + ' ' + freelancer.lastname;
                    
                    // Set default profile image if freelancer.profile is empty
                    var profileImage = freelancer.profile ? freelancer.profile : '../images/user.jpg';
                    
                    // Construct HTML for each freelancer with their ID
                    var html = `
                    <div class="freelancer-request-container">
                        <div class="freelancer-info">
                            <div class="freelancer-info-img">
                                <img src="${profileImage}" alt="" id="profile">
                            </div>
                            <div class="freelancer-details">
                                <h3>${fullname}</h3>
                                <p>Freelancer</p>
                            </div>
                        </div>
                        <div class="freelancer-button">
                            <i class="bx bx-info-circle viewFreelancerRegistrationDetails" data-freelancer-id="${freelancer.id}"></i>
                        </div>
                    </div>
                    `;
                    
                    // Append HTML to the list container
                    $('.freelancer-registration-request-list').append(html);
                });

            },
            error: function(xhr, status, error) {
                console.error('Error fetching freelancer requests:', error);
            }
        });

        // Event delegation: handle info icon click for dynamically added elements
        $(document).on('click', '.viewFreelancerRegistrationDetails', function() {
            var freelancerId = $(this).data('freelancer-id');
            $('#freelancerRegistrationModal').show(); // Show the modal

            // AJAX request to fetch the freelancer details by ID
            $.ajax({
                url: 'admin-fetch-freelancer-request-details.php', // Change this to your actual PHP file
                type: 'GET',
                data: {freelancer_id: freelancerId},
                dataType: 'json',
                success: function(freelancerData) {
                    if (!freelancerData.error) {
                        // Populate modal with freelancer data
                        $('#registrationModalProfileImage').attr('src', freelancerData.profile || '../images/user.jpg');
                        $('#registrationModalFirstName').text(freelancerData.firstname);
                        $('#registrationModalLastName').text(freelancerData.lastname);
                        $('#registrationModalAge').text(freelancerData.age);
                        $('#registrationModalAddress').text(freelancerData.address);
                        $('#registrationModalPhone').text(freelancerData.phone);
                        $('#registrationModalEmail').text(freelancerData.email);
                        $('#registrationModalGender').text(freelancerData.gender);
                        $('#registrationModalValidIDType').text(freelancerData.valid_id_type); // Update this field based on your data structure
                        $("#valid_id_type").val(freelancerData.valid_id_type);
                        $('#registrationModalRegistrationDate').text(freelancerData.date); // Update this field based on your data structure
                        $('#registrationModalValidID').attr('src', freelancerData.valid_id); // Update this field based on your data structure
                        $('#freelancerRegistrationApproveBtn').attr('data-freelancer-id', freelancerData.id); // Update this field based on your data structure
                        $('#freelancerRegistrationRejectBtn').attr('data-freelancer-id', freelancerData.id); // Update this field based on your data structure

                    } else {
                        console.error(freelancerData.error); // Handle error if freelancer not found
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching freelancer details:', error);
                }
            });
        });

        // Close modal on click
        $('#closeFreelancerRegistrationModal').click(function() {
            $('#freelancerRegistrationModal').hide();
        });
    });

    // Get elements
    const validateIdModalmodal = document.getElementById("validateIdModal");
    const openValidateIdModalButton = document.getElementById("frelancerRegistrationValidationBtn");
    const closeValidateIdModalButton = document.querySelector("#validateIdModal .close-btn");

    // Open the modal when "Validate ID" button is clicked
    openValidateIdModalButton.addEventListener("click", () => {
        validateIdModalmodal.style.display = "flex";
    });

    // Close the modal when the close button is clicked
    closeValidateIdModalButton.addEventListener("click", () => {
        validateIdModalmodal.style.display = "none";
    });
        
    // Handle form submission for validation
    $(document).on("submit", "#validateIdForm", function (e) {
        e.preventDefault();

        const freelancerId = $("#freelancerRegistrationApproveBtn").data("freelancer-id");
        const validIdType = $("#valid_id_type").val();
        const fullName = $("#fullname").val();

        Swal.fire({
            title: "Are you sure?",
            text: "You are finalizing this freelancer's approval.",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, approve!",
            customClass: {
                popup: 'small-swal-popup',
                icon: 'small-swal-icon',
                confirmButton: 'custom-confirm-question-button',
                cancelButton: 'custom-cancel-button'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX request to insert validation details and approve freelancer
                $.ajax({
                    url: "admin-freelancer-confirmation.php",
                    type: "POST",
                    data: {
                        freelancer_id: freelancerId,
                        id_type: validIdType,
                        id_full_name: fullName,
                        status: 1, // Approved status
                    },
                    success: function (response) {
                        const res = JSON.parse(response);
                        if (res.success) {
                            Swal.fire("Approved!", "The freelancer has been approved.", "success");
                            validateIdModal.style.display = "none"; // Close modal
                        } else {
                            Swal.fire("Error!", res.error || "An unknown error occurred.", "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error!", "Failed to approve the freelancer.", "error");
                    },
                });
            }
        });
    });


    $(document).on('click', '#freelancerRegistrationRejectBtn', function () {
        // Get freelancer ID (ensure this is passed when the modal opens)
        const freelancerId = $(this).data('freelancer-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You are rejecting this freelancer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, reject!',
            customClass: {
                popup: 'small-swal-popup',  
                icon: 'small-swal-icon',
                confirmButton: 'custom-confirm-button', 
                cancelButton: 'custom-cancel-button'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX request to reject the freelancer
                $.ajax({
                    url: 'admin-freelancer-confirmation.php',
                    type: 'POST',
                    data: {
                        freelancer_id: freelancerId,
                        status: 2, // Status for rejected
                    },
                    success: function (response) {
                        Swal.fire('Rejected!', 'The freelancer has been rejected.', 'success');
                        $('#freelancerRegistrationModal').hide(); // Close modal
                        // Optionally, refresh the list or remove the rejected freelancer
                    },
                    error: function (xhr, status, error) {
                        Swal.fire('Error!', 'Failed to reject the freelancer.', 'error');
                    },
                });
            }
        });
    });





    $(document).ready(function() {
        var myChart;
        

        // Function to fetch data and update chart based on selected month and year
        function fetchDataAndUpdateChart(selectedMonth, selectedYear) {
            $.ajax({
                url: 'admin-fetch-analysis-data.php',
                type: 'GET',
                dataType: 'json',
                data: { month: selectedMonth, year: selectedYear },
                success: function(response) {
                    var totalRegisteredClients = response.totalRegisteredClients;
                    var totalRegisteredFreelancers = response.totalRegisteredFreelancers;
                    var freelancerRequests = response.freelancerRequests;  // Pending requests (status = 0)

                    // Get the number of days in the selected month
                    var daysInMonth = new Date(selectedYear, selectedMonth, 0).getDate();
                    var labels = Array.from({ length: daysInMonth }, (_, i) => (i + 1).toString());

                    // Get the canvas context
                    var ctx = document.getElementById('myChart').getContext('2d');

                    // Create gradient for the chart background
                    var gradientBg = ctx.createLinearGradient(0, 0, 0, 400);
                    gradientBg.addColorStop(0, 'rgba(255, 99, 132, 0.2)');
                    gradientBg.addColorStop(1, 'rgba(255, 99, 132, 0)');

                    // Define the data for the chart
                    var chartData = {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Total Registered Clients',
                                data: totalRegisteredClients,
                                fill: true,  // Fill the area below the line
                                backgroundColor: gradientBg,
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 2,
                                pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: 'rgba(255, 99, 132, 1)',
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                tension: 0.4  // Smooth curves for the lines
                            },
                            {
                                label: 'Total Registered Freelancers',
                                data: totalRegisteredFreelancers,  // Only freelancers with status = 1
                                fill: false,
                                borderColor: '#1f65fb',
                                borderWidth: 2,
                                pointBackgroundColor: '#1f65fb',
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: '#1f65fb',
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                tension: 0.4  // Smooth curves for the lines
                            },
                            {
                                label: 'Pending Freelancer Requests',
                                data: freelancerRequests,  // Pending requests (status = 0)
                                fill: false,
                                borderColor: '#FEC400',
                                borderWidth: 2,
                                pointBackgroundColor: '#FEC400',
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: '#FEC400',
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                tension: 0.4  // Smooth curves for the lines
                            }
                        ]
                    };

                    // Chart configuration
                    var config = {
                        type: 'line',
                        data: chartData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'nearest',
                                intersect: false,
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    labels: {
                                        color: '#333',
                                        font: {
                                            size: 14,
                                        }
                                    }
                                },
                                tooltip: {
                                    enabled: true,
                                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    cornerRadius: 6,
                                    xPadding: 10,
                                    yPadding: 10
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false,
                                    },
                                    ticks: {
                                        color: '#333',
                                        font: {
                                            size: 14
                                        }
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: '#ddd',
                                        borderDash: [5, 5],
                                    },
                                    ticks: {
                                        color: '#333',
                                        font: {
                                            size: 14
                                        }
                                    }
                                }
                            },
                            animation: {
                                duration: 1000,
                                easing: 'easeInOutQuad'
                            }
                        }
                    };

                    // Destroy the previous chart if it exists
                    if (myChart) {
                        myChart.destroy();
                    }

                    // Create the chart
                    myChart = new Chart(ctx, config);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        }

        // Get the currently selected month and year from the dropdowns
        var selectedMonth = $('#selectMonth').val();
        var selectedYear = $('#selectYear').val();

        // Fetch data on page load with preselected month and year
        fetchDataAndUpdateChart(selectedMonth, selectedYear);

        // Handle month and year select changes
        $('#selectMonth, #selectYear').change(function() {
            selectedMonth = $('#selectMonth').val();
            selectedYear = $('#selectYear').val();
            fetchDataAndUpdateChart(selectedMonth, selectedYear);
        });
    });


    $(document).ready(function() {
        // Function to print the chart into a PDF
        $('#print-button').click(function() {
            // Get the canvas element
            let canvas = document.getElementById('chartContainer');
            
            // Use html2canvas to capture the canvas content
            html2canvas(canvas).then(function(canvasImage) {
                // Convert the canvas content to an image
                let imgData = canvasImage.toDataURL('image/png');
                
                // Create a new jsPDF instance
                let pdf = new jsPDF('landscape');
                
                // Add the captured image to the PDF
                pdf.addImage(imgData, 'PNG', 10, 10, canvas.width * 0.3, canvas.height * 0.3);
                
                // Save the PDF
                pdf.save('chart.pdf');
            });
        });
    });


    function formatDateToWords(datetime) {
        const date = new Date(datetime);
        const day = date.getDate();
        const monthNames = [
            'Jan.', 'Feb.', 'Mar.', 'Apr.', 'May', 'Jun.',
            'Jul.', 'Aug.', 'Sep.', 'Oct.', 'Nov.', 'Dec.'
        ];
        const monthName = monthNames[date.getMonth()];
        const year = date.getFullYear();
        const hour = date.getHours() % 12 || 12; // 12-hour format
        const minute = String(date.getMinutes()).padStart(2, '0');
        const ampm = date.getHours() >= 12 ? 'PM' : 'AM';

        // Calculate ordinal suffix
        let ordinal;
        if (day > 10 && day < 14) {
            ordinal = day + 'th';
        } else {
            switch (day % 10) {
                case 1: ordinal = day + 'st'; break;
                case 2: ordinal = day + 'nd'; break;
                case 3: ordinal = day + 'rd'; break;
                default: ordinal = day + 'th'; break;
            }
        }

        return `${monthName} ${ordinal}, ${year} at ${hour}:${minute} ${ampm}`;
    }

    $(document).ready(function() {
        // Fetch all bookings and populate the containers based on status
        $.ajax({
            url: 'system-fetch-all-transactions.php', // Make sure this points to your PHP script
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const bookings = response.bookings;
                    const ongoingContainer = $('#ongoingTransactionContainer');
                    const endedContainer = $('#endedTransactionContainer');

                    // Clear the containers before appending new data
                    ongoingContainer.empty();
                    endedContainer.empty();

                    // Loop through each booking and populate the appropriate container
                    bookings.forEach(function(booking) {
                        const bookingHtml = `
                            <div class="transaction-details-user-info">
                                <div class="transaction-profile">
                                    <img src="${booking.client_profile}" alt="Client Profile">
                                    <div class="details">
                                        <h3>${booking.client_name}</h3>
                                        <p>Client</p>
                                    </div>
                                </div>
                                <div class="transaction-line-img-container">
                                    <img class="line-img" src="../images/pulse.png" alt="">
                                </div>
                                <div class="transaction-profile">
                                    <img src="${booking.freelancer_profile}" alt="Freelancer Profile">
                                    <div class="details">
                                        <h3>${booking.freelancer_name}</h3>
                                        <p>Freelancer</p>
                                    </div>
                                </div>
                                <div class="transaction-buttons">
                                    <div class="info-request-button-container">
                                        <i class="bx bx-info-circle info-request-button" title="View Transaction Details" data-booking-id="${booking.booking_id}"></i>
                                    </div>
                                </div>
                            </div>
                        `;

                        // Append to the correct container based on booking status
                        if (booking.booking_status === 'Approved') {
                            ongoingContainer.append(bookingHtml);
                        } else if (booking.booking_status === 'Completed') {
                            endedContainer.append(bookingHtml);
                        }
                    });
                } else {
                    toastr.error(response.error || "Failed to fetch bookings.");
                }
            },
            error: function(xhr, status, error) {
                toastr.error("An error occurred. Please try again.");
                console.error(xhr.responseText);
            }
        });
    });

    // Event listener for clicking the info request button to fetch transaction details
    $(document).on('click', '.info-request-button', function() {
        const bookingId = $(this).data('booking-id');

        // Fetch transaction details for the selected booking
        $.ajax({
            url: 'system-fetch-transaction-details.php', // Adjust this endpoint as needed
            type: 'GET',
            data: { booking_id: bookingId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const booking = response.booking;

                    // Populate modal fields with booking details
                    $('#clientProfile img').attr('src', booking.client_profile);
                    $('#clientProfile .details h3').text(booking.client_name);
                    $('#freelancerProfile img').attr('src', booking.freelancer_profile);
                    $('#freelancerProfile .details h3').text(booking.freelancer_name);
                    
                    $('#modalTransactionDetails').html(`
                        ${booking.booking_status === 'Approved' || booking.booking_status === 'Completed' ? '<p><i class="bx bxs-check-circle"></i> <span>Approved by freelancer.</span></p>' : '<p><i class="bx bxs-x-circle"></i> <span>Approved by freelancer.</span></p>'}
                        <p><i class="bx bxs-check-circle"></i> <span>Transaction started on ${formatDateToWords(booking.start_date)}.</span></p>
                        ${booking.client_ET_request ? '<p><i class="bx bxs-check-circle"></i> <span>Job completed by freelancer.</span></p>' : '<p><i class="bx bxs-x-circle"></i> <span>Job completed by freelancer.</span></p>'}
                        ${booking.client_ET_request ? '<p><i class="bx bxs-check-circle"></i> <span>Review and rating submitted by client.</span></p>' : '<p><i class="bx bxs-x-circle"></i> <span>Review and rating submitted by client.</span></p>'}
                        ${booking.freelancer_ET_request ? '<p><i class="bx bxs-check-circle"></i> <span>Payment received by freelancer.</span></p>' : '<p><i class="bx bxs-x-circle"></i> <span>Payment received by freelancer.</span></p>'}
                        ${booking.booking_status === 'Completed' ? '<p><i class="bx bxs-check-circle"></i> <span>Transaction Completed.</span></p>' : ''}
                    `);

                    let bookingStatusMessage = '';
                    if (booking.booking_status === 'Approved') {
                        bookingStatusMessage = `<p><i class="bx bxs-calendar-check"></i> <span>Booking request submitted on ${formatDateToWords(booking.booking_date)}.</span></p>`;
                    } else if (booking.booking_status === 'Completed') {
                        bookingStatusMessage = `<p><i class="bx bxs-calendar-check"></i> <span>Transaction ended on ${formatDateToWords(booking.end_date)}.</span></p>`;
                    }
                    
                    $('#modalTransactionServiceDetails').html(`
                        ${bookingStatusMessage}
                        <p><i class="bx bxs-info-circle"></i> <span>Job will be done <b>${booking.job_type}</b> and payment will be given <b>${booking.payment_type}</b>.</span></p>
                    `);

                    // Add click events for profile redirection
                    $('#clientProfile').off('click').on('click', function() {
                        window.location.href = `system-view-client-profile.php?client_id=${booking.client_id}`;
                    });
                    $('#freelancerProfile').off('click').on('click', function() {
                        window.location.href = `system-view-freelancer-profile.php?freelancer_id=${booking.freelancer_id}`;
                    });

                    // Show the modal
                    $('#transactionRequestDetailsModal').show();
                } else {
                    toastr.error(response.error || "Failed to fetch transaction details.");
                }
            },
            error: function(xhr, status, error) {
                toastr.error("An error occurred while fetching transaction details. Please try again.");
                console.error(xhr.responseText);
            }
        });
    });

    // Close modal on button click
    $('#closeTransactionRequestDetailsModalBtn').click(function() {
        $('#transactionRequestDetailsModal').hide();
    });
});
