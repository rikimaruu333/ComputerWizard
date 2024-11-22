document.addEventListener('DOMContentLoaded', function () {
    
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: 'freelancer-fetch-calendar-events.php',  // Load events from PHP backend
        editable: true,
        selectable: true,
        select: function(info) {
            // Reset the modal input fields
            document.getElementById('eventTitle').value = '';
            document.getElementById('eventDescription').value = '';
            
            // Set start date
            var startDate = moment(info.start).format('YYYY-MM-DD'); // Format for date input
            document.getElementById('eventStartDate').value = startDate; // Set start date

            // Set end date based on selection duration
            if (info.start.getTime() !== info.end.getTime()) {
                // If the selection spans multiple days, set the end date
                var endDate = moment(info.end).subtract(1, 'days').format('YYYY-MM-DD'); // Use the day before the end date
                document.getElementById('eventEndDate').value = endDate; // Set end date
            } else {
                // If only one day is selected, set end date the same as start date
                document.getElementById('eventEndDate').value = startDate; // Use the same date for end
            }
            // Set default times
            document.getElementById('eventStartTime').value = '00:00'; // Set default start time to 12:00 AM
            document.getElementById('eventEndTime').value = '23:59'; // Set end time to 11:59 PM
        
            document.getElementById('addEventModal').style.display = 'flex';
            calendar.unselect(); // Unselect the date after showing the modal
        },
        
        eventClick: function(info) {
            // Set event ID for editing or deleting
            document.getElementById('event_id').value = info.event.id;
            document.getElementById('calendarModalEventTitle').innerText = info.event.title;
            document.getElementById('calendarModalEventDescription').innerText = info.event.extendedProps.description || 'No description provided';
            document.getElementById('calendarModalEventStart').innerText = moment(info.event.start).format('MMMM Do YYYY, h:mm A');
            document.getElementById('calendarModalEventEnd').innerText = info.event.end ? moment(info.event.end).format('MMMM Do YYYY, h:mm A') : 'N/A';
            
            document.getElementById('eventModal').style.display = 'flex';
        },
        eventDrop: function(info) {
            // Call updateEvent with required parameters
            const event = info.event;
            const start = moment(event.start).format('YYYY-MM-DD');
            const end = event.end ? moment(event.end).format('YYYY-MM-DD') : start; // Use start date if no end date
            const startTime = moment(event.start).format('HH:mm');
            const endTime = event.end ? moment(event.end).format('HH:mm') : '23:59'; // Default end time if no end date
            
            updateEvent(event.title, event.extendedProps.description, start, startTime, end, endTime, event.id);
        },
        
        dayCellDidMount: function(info) {
            // Highlight today's date
            var today = new Date();
            var date = new Date(info.date);
            if (date.toDateString() === today.toDateString()) {
                info.el.style.backgroundColor = '#edf4ff'; // Set the background color for the current date
            }
        }
    });

    calendar.render();

    // Close the event details modal
    document.getElementById('closeEventModal').addEventListener('click', function() {
        document.getElementById('eventModal').style.display = 'none';
    });

    // Close the add event modal
    document.getElementById('closeAddEventModal').addEventListener('click', function() {
        document.getElementById('addEventModal').style.display = 'none';
    });

    // Handle saving of the event
    document.getElementById('saveEventButton').addEventListener('click', function() {
        var title = document.getElementById('eventTitle').value;
        var description = document.getElementById('eventDescription').value;
        var startDate = document.getElementById('eventStartDate').value; // Should be in YYYY-MM-DD format
        var startTime = document.getElementById('eventStartTime').value; // Should be in HH:mm format
        var endDate = document.getElementById('eventEndDate').value; // Should be in YYYY-MM-DD format
        var endTime = document.getElementById('eventEndTime').value; // Should be in HH:mm format
    
        // Call function to save event to the database with all six variables
        saveEvent(title, description, startDate, startTime, endDate, endTime);
    });
    
    function saveEvent(title, description, startDate, startTime, endDate, endTime) {
        // Make AJAX request to save the event
        fetch('freelancer-add-calendar-event.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                title: title,
                description: description,
                startDate: startDate, // Pass start date separately
                startTime: startTime, // Pass start time separately
                endDate: endDate,     // Pass end date separately
                endTime: endTime      // Pass end time separately
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                calendar.addEvent({
                    title: title,
                    description: description,
                    start: startDate + 'T' + startTime + ':00', // Set start with combined date and time
                    end: endDate + 'T' + endTime + ':00'        // Set end with combined date and time
                });
                document.getElementById('addEventModal').style.display = 'none'; // Hide modal
                // Reset input fields
                document.getElementById('eventTitle').value = '';
                document.getElementById('eventDescription').value = '';
                document.getElementById('eventStartDate').value = '';
                document.getElementById('eventEndDate').value = '';
                document.getElementById('eventStartTime').value = '00:00';
                document.getElementById('eventEndTime').value = '23:59';
    
                // Show success toast
                toastr.success('Event added successfully!', 'Success');
            } else {
                toastr.error('Error saving event: ' + data.message, 'Error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('An error occurred while saving the event.', 'Error');
        });
    }
    

    function updateEvent(title, description, startDate, startTime, endDate, endTime, id) {
        // Make AJAX request to update the event in the database
        fetch('freelancer-update-calendar-event.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: id,
                title: title,
                description: description,
                startDate: startDate, // Pass start date separately
                startTime: startTime, // Pass start time separately
                endDate: endDate,     // Pass end date separately
                endTime: endTime      // Pass end time separately
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                calendar.getEventById(id).setProp('title', title);
                calendar.getEventById(id).setExtendedProp('description', description);
                calendar.getEventById(id).setDates(startDate + 'T' + startTime + ':00', endDate + 'T' + endTime + ':00');
    
                // Show success toast
                toastr.info('Event updated successfully!', 'Success');
            } else {
                toastr.error('Error updating event: ' + data.message, 'Error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('An error occurred while updating the event.', 'Error');
        });
    }
    document.getElementById('deleteEventButton').addEventListener('click', function() {
        var eventId = document.getElementById('event_id').value; // Get the ID of the event to delete
        deleteEvent(eventId);
    });

    function deleteEvent(id) {
        // Show SweetAlert2 confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this event? This action cannot be undone.',
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
                // Make AJAX request to delete the event
                fetch('freelancer-delete-calendar-event.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the event from the calendar
                        calendar.getEventById(id).remove();
                        document.getElementById('eventModal').style.display = 'none'; // Hide the event modal
                        Swal.fire('Deleted!', 'Event deleted successfully.', 'success'); // Success message
                    } else {
                        Swal.fire('Error!', 'Failed to delete the event: ' + data.message, 'error'); // Error message
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'An error occurred while deleting the event.', 'error'); // Error message
                });
            }
        });
    }
    
    
    
});
