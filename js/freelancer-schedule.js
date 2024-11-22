$(document).ready(function() {
    const scheduleModal = document.getElementById("scheduleModal");
    const addScheduleModal = document.getElementById("addScheduleModal");
    const openScheduleModalBtn = document.getElementById("openScheduleModalBtn");
    const openAddScheduleModalBtn = document.getElementById("openAddScheduleModalBtn");
    const closeScheduleModalBtn = document.getElementById("close-schedule-modal");
    const closeAddScheduleModalBtn = document.getElementById("close-add-schedule-modal");

    // Ensure elements exist before assigning event listeners
    if (openScheduleModalBtn) {
        openScheduleModalBtn.onclick = function() {
            scheduleModal.style.display = "block";
            openScheduleModalBtn.classList.add("active");
        };
    }

    if (openAddScheduleModalBtn) {
        openAddScheduleModalBtn.onclick = function() {
            addScheduleModal.style.display = "block"; 
        };
    }

    if (closeScheduleModalBtn) {
        closeScheduleModalBtn.onclick = function() {
            scheduleModal.style.display = "none";
            if (openScheduleModalBtn) {
                openScheduleModalBtn.classList.remove("active");
            }
        };
    }

    if (closeAddScheduleModalBtn) {
        closeAddScheduleModalBtn.onclick = function() {
            addScheduleModal.style.display = "none";
        };
    }

    // Toastr configuration
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

    // Function to dynamically append new schedule row to the table
    function appendScheduleToTable(schedule) {
        // Convert time to 12-hour format
        const formattedTimeIn = formatTime(schedule.time_in);
        const formattedTimeOut = formatTime(schedule.time_out);
    
        let scheduleRow = `
            <tr data-id="${schedule.schedule_id}" id="schedule-${schedule.schedule_id}">
                <td>${schedule.day}</td>
                <td>${formattedTimeIn}</td>
                <td>${formattedTimeOut}</td>
                <td class="schedule-config">
                    <u class="schedule-edit-config" data-id="${schedule.schedule_id}" data-day="${schedule.day}" data-timein="${schedule.time_in}" data-timeout="${schedule.time_out}">Edit</u> |
                    <u class="schedule-delete-config" data-id="${schedule.schedule_id}">Delete</u>
                </td>
            </tr>
        `;
        $('#scheduleTableBody').append(scheduleRow);
    }
    
    // Helper function to convert 24-hour time to 12-hour time
    function formatTime(time) {
        const [hours, minutes] = time.split(':');
        const hoursInt = parseInt(hours, 10);
        const ampm = hoursInt >= 12 ? 'PM' : 'AM';
        const formattedHours = hoursInt % 12 || 12; // Converts 0 to 12
        return `${formattedHours}:${minutes} ${ampm}`; 
    }
    
    // Add Schedule - Realtime update
    $('#addSchedule').on('click', function(event) {
        event.preventDefault();

        let day = $('#scheduleDay').val();
        let timeIn = $('#scheduleTimeIn').val();
        let timeOut = $('#scheduleTimeOut').val();

        if (day === "" || timeIn === "" || timeOut === "") {
            toastr.error('Please fill in all fields');
            return;
        }

        $.ajax({
            url: 'freelancer-add-schedule.php',
            type: 'POST',
            dataType: 'json',
            data: {
                day: day,
                time_in: timeIn,
                time_out: timeOut
            },
            success: function(response) {
                if (response.error) {
                    toastr.error(response.error);
                } else {
                    toastr.success('Schedule added successfully!');
                    $('#addScheduleModal').hide();

                    // Append the new schedule to the table without reloading
                    appendScheduleToTable(response);
                }
            },
            error: function(xhr) {
                toastr.error('Failed to add schedule. Please try again.');
            }
        });
    });

    // Fetch and display schedules when modal is opened
    $('#openScheduleModalBtn').on('click', function() {
        $('#scheduleModal').show();
    
        $.ajax({
            url: 'freelancer-fetch-schedule.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (Array.isArray(response)) {
                    $('#scheduleTableBody').empty();
                    response.forEach(function(schedule) {
                        appendScheduleToTable(schedule);
                    });
                } else if (response.error) {
                    toastr.error('Error fetching schedules: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                toastr.error('Failed to fetch schedules. Please try again.');
            }
        });
    });

    // Close schedule modal
    $('#close-schedule-modal').on('click', function() {
        $('#scheduleModal').hide();
    });

    // Show the update schedule modal and pre-fill the fields
    $(document).on('click', '.schedule-edit-config', function() {
        const scheduleId = $(this).data('id');
        const day = $(this).data('day');
        const timeIn = $(this).data('timein');
        const timeOut = $(this).data('timeout');

        // Fill the modal fields with the current schedule data
        $('#updateScheduleDay').val(day);
        $('#updateScheduleTimeIn').val(timeIn);
        $('#updateScheduleTimeOut').val(timeOut);
        $('#updateScheduleId').val(scheduleId);

        // Show the update schedule modal
        $('#updateScheduleModal').show();
    });

    // Close the update schedule modal
    $('#close-update-schedule-modal').on('click', function() {
        $('#updateScheduleModal').hide();
    });

    // Update Schedule - Realtime update
    $('#updateSchedule').on('click', function(event) {
        event.preventDefault();

        const scheduleId = $('#updateScheduleId').val();
        const day = $('#updateScheduleDay').val();
        const timeIn = $('#updateScheduleTimeIn').val();
        const timeOut = $('#updateScheduleTimeOut').val();

        if (day === "" || timeIn === "" || timeOut === "") {
            toastr.error('Please fill in all fields');
            return;
        }

        $.ajax({
            url: 'freelancer-update-schedule.php',
            type: 'POST',
            dataType: 'json',
            data: {
                schedule_id: scheduleId,
                day: day,
                time_in: timeIn,
                time_out: timeOut
            },
            success: function(response) {
                if (response.error) {
                    toastr.error(response.error);
                } else {
                    toastr.info('Schedule updated successfully!');
                    $('#updateScheduleModal').hide();

                    // Update the schedule row in the table
                    $(`tr[data-id="${response.schedule_id}"]`).html(`
                        <td>${response.day}</td>
                        <td>${formatTime(response.time_in)}</td> <!-- Use the formatTime function here -->
                        <td>${formatTime(response.time_out)}</td> <!-- Use the formatTime function here -->
                        <td class="schedule-config">
                            <u class="schedule-edit-config" data-id="${response.schedule_id}" data-day="${response.day}" data-timein="${response.time_in}" data-timeout="${response.time_out}">Edit</u> |
                            <u class="schedule-delete-config" data-id="${response.schedule_id}">Delete</u>
                        </td>
                    `);
                }
            },
            error: function(xhr) {
                toastr.error('Failed to update schedule. Please try again.');
            }
        });
    });

    // Handle delete action
    $(document).on('click', '.schedule-delete-config', function() {
        const scheduleId = $(this).data('id');
        $('#deleteScheduleModal').show();
        $('#confirmDeleteSchedule').data('id', scheduleId); // Set schedule ID to confirm button
    });

    $('#confirmDeleteSchedule').on('click', function() {
        const scheduleIdToDelete = $(this).data('id');

        $.ajax({
            url: 'freelancer-delete-schedule.php',
            type: 'POST',
            data: { 
                schedule_id: scheduleIdToDelete
            },
            success: function(response) {
                let result;
                if (typeof response === 'string') {
                    try {
                        result = JSON.parse(response);
                    } catch (e) {
                        toastr.error('Unexpected error occurred. Please try again.');
                        return;
                    }
                } else {
                    result = response;
                }

                if (result.success) {
                    toastr.warning(result.success);
                    $('#deleteScheduleModal').hide();
                    
                    // Remove the deleted schedule from the table
                    $(`#schedule-${scheduleIdToDelete}`).remove(); // Remove the schedule row in real-time
                } else {
                    toastr.error(result.error);
                }
            },
            error: function(xhr) {
                toastr.error('Failed to delete the schedule. Please try again.');
            }
        });
    });

    $('#close-delete-schedule-modal').on('click', function() {
        $('#deleteScheduleModal').hide();
    });
});
