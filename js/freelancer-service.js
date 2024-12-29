$(document).ready(function() {
    const serviceModal = document.getElementById("serviceModal");
    const addServiceModal = document.getElementById("addServiceModal");
    const openServiceModalBtn = document.getElementById("openServiceModalBtn");
    const openAddServiceModalBtn = document.getElementById("openAddServiceModalBtn");
    const closeServiceModalBtn = document.getElementById("close-service-modal");
    const closeAddServiceModalBtns = document.getElementById("close-add-service-modal");

    openServiceModalBtn.onclick = function() {
        serviceModal.style.display = "block";
        openServiceModalBtn.classList.add("active");
    }
    openAddServiceModalBtn.onclick = function() {
        addServiceModal.style.display = "block"; 
    }
    closeServiceModalBtn.onclick = function() {
        serviceModal.style.display = "none";
        openServiceModalBtn.classList.remove("active");
    }
    closeAddServiceModalBtns.onclick = function() {
        addServiceModal.style.display = "none";
    }

    // Toastr options
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

    // Function to append a service to the table
    function appendServiceToTable(service) {
        let formattedRate = parseFloat(service.service_rate).toFixed(2); // Format to 2 decimal places
        let serviceRow = `
            <tr id="service-${service.service_id}" data-id="${service.service_id}">
                <td>${service.service}</td>
                <td>₱${formattedRate}</td>
                <td class="service-config">
                    <u class="service-edit-config" data-id="${service.service_id}" data-category="${service.service_category}" data-name="${service.service}" data-rate="${formattedRate}">Edit</u> |
                    <u class="service-delete-config" data-id="${service.service_id}">Delete</u>
                </td>
            </tr>
        `;
        $('#serviceTableBody').append(serviceRow);
    }

    // Fetch services when modal is opened
    $('#openServiceModalBtn').on('click', function() {
        
        $.ajax({
            url: 'freelancer-fetch-service.php',
            type: 'GET',
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
    }); 

    // Restrict serviceRate input to integers only
    $('#serviceRate').on('input', function() {
        let value = $(this).val();
        // Replace any non-numeric characters
        $(this).val(value.replace(/[^0-9]/g, ''));
    });
    // Restrict serviceRate input to integers only
    $('#updateServiceRate').on('input', function() {
        let value = $(this).val();
        // Replace any non-numeric characters
        $(this).val(value.replace(/[^0-9]/g, ''));
    });

    // Add Service - Realtime update
    $('#addService').on('click', function(event) {
        event.preventDefault();

        let serviceCategoryName = $('#serviceCategoryName').val();
        let serviceName = $('#serviceName').val();
        let serviceRate = $('#serviceRate').val();

        if (serviceName === "" || serviceRate === "") {
            toastr.error('Please fill in all fields');
            return;
        }

        // Ensure the rate is a valid integer
        if (!/^\d+$/.test(serviceRate)) {
            toastr.error('Rate must be a valid rate.');
            return;
        }

        $.ajax({
            url: 'freelancer-add-service.php',
            type: 'POST',
            dataType: 'json',
            data: {
                serviceCategoryName: serviceCategoryName,
                serviceName: serviceName,
                serviceRate: serviceRate
            },
            success: function(response) {
                if (response.error) {
                    toastr.error(response.error);
                } else {
                    toastr.success('Service added successfully!');
                    $('#addServiceModal').hide();
                    appendServiceToTable(response); // Append new service
                }
            },
            error: function(xhr) {
                toastr.error('Failed to add service. Please try again.');
            }
        });
    });

    $(document).on('click', '.service-edit-config', function() {
        const serviceId = $(this).data('id');
        const serviceCategoryName = $(this).data('category');
        const serviceName = $(this).data('name');
        const serviceRate = $(this).data('rate');

        // Fill the modal fields with the current service data
        $('#updateServiceCategoryName').val(serviceCategoryName);
        $('#updateServiceName').val(serviceName);
        $('#updateServiceRate').val(serviceRate);
        $('#updateServiceId').val(serviceId);

        // Show the update service modal
        $('#updateServiceModal').show();
    });

    // Close the update service modal
    $('#close-update-service-modal').on('click', function() {
        $('#updateServiceModal').hide();
    });

    // Update Service - Realtime update
    $('#updateService').on('click', function(event) {
        event.preventDefault();

        const serviceId = $('#updateServiceId').val();
        const serviceCategoryName = $('#updateServiceCategoryName').val();
        const serviceName = $('#updateServiceName').val();
        const serviceRate = $('#updateServiceRate').val();

        // Check if any of the fields are empty
        if (serviceCategoryName === "" || serviceName === "" || serviceRate === "") {
            toastr.error('Please fill in all fields');
            return;
        }
        
        // Ensure the rate is a valid number with optional decimal places
        if (!/^\d+(\.\d{1,2})?$/.test(serviceRate)) {
            toastr.error('Rate must be a valid number (e.g., 500 or 500.00).');
            return;
        }

        $.ajax({
            url: 'freelancer-update-service.php',
            type: 'POST',
            dataType: 'json',
            data: {
                service_id: serviceId,
                service_category: serviceCategoryName,
                service: serviceName,
                service_rate: serviceRate
            },
            success: function(response) {
                if (response.error) {
                    toastr.error(response.error);
                } else {
                    toastr.info('Service updated successfully!');
                    $('#updateServiceModal').hide();

                    // Update the existing service row in the table
                    $(`#service-${response.service_id}`).html(`
                        <td>${response.service}</td>
                        <td>₱${response.service_rate}</td>
                        <td class="service-config">
                            <u class="service-edit-config" data-id="${response.service_id}" data-name="${response.service}" data-rate="${response.service_rate}">Edit</u>
                            <u class="service-delete-config" data-id="${response.service_id}">Delete</u>
                        </td>
                    `);
                }
            },
            error: function(xhr) {
                toastr.error('Failed to update service. Please try again.');
            }
        });
    });

    // Delete Service - Realtime update
    $(document).on('click', '.service-delete-config', function() {
        const serviceId = $(this).data('id');
        $('#deleteServiceModal').show();
        $('#confirmDeleteService').data('id', serviceId); // Set service ID to confirm button
    });

    $('#confirmDeleteService').on('click', function() {
        const serviceIdToDelete = $(this).data('id');

        $.ajax({
            url: 'freelancer-delete-service.php',
            type: 'POST',
            data: { 
                service_id: serviceIdToDelete
            },
            success: function(response) {
                let result;
                if (typeof response === 'string') {
                    try {
                        result = JSON.parse(response); // Attempt to parse if it's a string
                    } catch (e) {
                        console.error('JSON parsing error:', e);
                        toastr.error('Unexpected error occurred. Please try again.');
                        return; // Exit if parsing fails
                    }
                } else {
                    result = response; // Use the object directly
                }

                if (result.success) {
                    toastr.warning(result.success);
                    $(`#service-${serviceIdToDelete}`).remove(); // Remove the row from the table
                    $('#deleteServiceModal').hide();
                } else {
                    toastr.error(result.error);
                }
            },
            error: function(xhr) {
                console.error('AJAX error:', xhr.responseText); // Log error response
                toastr.error('Failed to delete the service. Please try again.');
            }
        });
    });

    $('#close-delete-service-modal').on('click', function() {
        $('#deleteServiceModal').hide();
    });
});
