import Shepherd from 'https://cdn.jsdelivr.net/npm/shepherd.js@13.0.0/dist/esm/shepherd.mjs';

// Initialize the Shepherd tour with advanced options
const tour = new Shepherd.Tour({
    defaultStepOptions: {
        cancelIcon: {
            enabled: true // Enable close button
        },
        classes: 'custom-class-name shepherd', // Custom styling
        scrollTo: false, // Scroll to the element if out of view
        useModalOverlay: true, // Modal overlay to block user interaction
        modalOverlayOpeningPadding: 10, // Padding around the modal highlight
        floating: true, // Floating popover positioning
        popperOptions: {
            modifiers: [
                {
                    name: 'offset',
                    options: {
                        offset: [0, 20] // Adjust popover positioning
                    }
                }
            ]
        }
    },
    // Add some overall tour options
    useModalOverlay: true,
    exitOnEsc: true, // Allow tour exit on 'ESC' key
    keyboardNavigation: true, // Enable arrow key navigation
    advancedHighlighting: true, // Advanced highlighting of steps
    showCancelLink: true, // Show 'X' button to cancel the tour
    confirmCancel: true, // Ask for confirmation before canceling the tour
    cancelIcon: {
        enabled: true // Enable cancel (close) icon
    }
});

// Step 1: Dynamic content and multiple actions
tour.addStep({
    id: 'welcome-step',
    title: 'Welcome to GigHub Calendar!',
    text: () => {
        // Dynamically generate content based on user input or data
        const userName = localStorage.getItem('userName') || 'Freelancer';
        return `Hello, ${userName}! This is your personal calendar. Let me show you how to get started!`;
    },
    attachTo: {
        element: '.calendar',
        on: 'center' // Attach the popover in the center of the calendar
    },
    buttons: [
        {
            text: 'Exit',
            action: tour.cancel, // Allows the user to exit the tour
            classes: 'btn-exit'
        },
        {
            text: 'Next',
            action: tour.next, // Move to the next step
            classes: 'btn-next'
        }
    ],
    when: {
        show: function() {
            console.log('Welcome step is shown');
        },
        hide: function() {
            console.log('Welcome step is hidden');
        }
    }
});

tour.addStep({
    id: 'add-event-step',
    title: 'Add an Event by Clicking a Date',
    text: 'Click on any date to open the modal and add a new event.',
    attachTo: {
        element: '.fc-daygrid-day:nth-child(3)',
        on: 'right' // Attach to the right of the day grid
    },
    buttons: [
        {
            text: 'Back',
            action: tour.back, // Go back to the previous step
            classes: 'btn-back'
        },
        {
            text: 'Next',
            action: tour.next,
            classes: 'btn-next'
        }
    ],
    when: {
        show: function() {
            console.log('Step 2: Add Event by Clicking a Date is shown');
        }
    }
});

// Step 3: Advanced scrolling options and modals
tour.addStep({
    id: 'multi-day-step',
    title: 'Create a Multi-Day Event',
    text: 'You can drag across multiple days to create a longer event.',
    attachTo: {
        element: '.fc-daygrid-body tr:nth-child(2)',
        on: 'bottom' // Attach to the right side of the element
    },
    scrollTo: {
        behavior: 'smooth',
        block: 'center'
    },
    buttons: [
        {
            text: 'Back',
            action: tour.back,
            classes: 'btn-back'
        },
        {
            text: 'Next',
            action: tour.next,
            classes: 'btn-next'
        }
    ]
});

// Step 4: Event interaction and conditions
tour.addStep({
    id: 'event-details-step',
    title: 'View Event Details',
    text: 'Click on an existing event to view its details or delete it.',
    attachTo: {
        element: '.fc-event',
        on: 'bottom' // Attach popover to the bottom of the event
    },
    buttons: [
        {
            text: 'Back',
            action: tour.back,
            classes: 'btn-back'
        },
        {
            text: 'Next',
            action: tour.next,
            classes: 'btn-next'
        }
    ],
    when: {
        show: function() {
            if (!document.querySelector('.fc-event')) {
                // Skip this step if no events are available
                console.log('No events found, skipping this step');
                tour.next();
            }
        }
    }
});

// Step 4: Event interaction and conditions
tour.addStep({
    id: 'event-details-step',
    title: 'Drag to reschedule an Event',
    text: 'Drag an existing event to another date to reschedule.',
    attachTo: {
        element: '.fc-event',
        on: 'bottom' // Attach popover to the bottom of the event
    },
    buttons: [
        {
            text: 'Back',
            action: tour.back,
            classes: 'btn-back'
        },
        {
            text: 'Next',
            action: tour.next,
            classes: 'btn-next'
        }
    ],
    when: {
        show: function() {
            if (!document.querySelector('.fc-event')) {
                // Skip this step if no events are available
                console.log('No events found, skipping this step');
                tour.next();
            }
        }
    }
});
// Step 5: End of tour action
tour.addStep({
    id: 'end-step',
    title: 'End of Tour',
    text: 'This concludes the tour! You are ready to use the GigHub Calendar.',
    attachTo: {
        element: '.calendar',
        on: 'center' // Attach popover in the center of the calendar
    },
    buttons: [
        {
            text: 'Back',
            action: tour.back,
            classes: 'btn-back'
        },
        {
            text: 'Finish',
            action: tour.complete, // End the tour
            classes: 'btn-finish'
        }
    ],
    when: {
        show: function() {
            console.log('Tour is about to end');
        },
        complete: function() {
            console.log('Tour completed');
            alert('Thank you for taking the tour!');
        }
    }
});

// Start the tour when the help button is clicked
document.querySelector('#startTourButton').addEventListener('click', () => {
    tour.start();
});





