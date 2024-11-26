document.addEventListener('DOMContentLoaded', function() {
    const socket = io('http://localhost:8080');

    const notificationBell = document.getElementById('notificationBell');
    const notificationCount = document.getElementById('notificationCount');
    const notificationList = document.getElementById('notificationList');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const userId = document.getElementById('head').getAttribute('data-user-id');
    
    // Identify user to join their specific room immediately upon connection
    socket.on('connect', () => {
        socket.emit('identify', userId);
    });

    // Reconnect on disconnect
    socket.on('disconnect', () => {
        console.log('Socket disconnected, attempting to reconnect...');
        socket.connect();
    });

    // Listen for booking notifications
    socket.on('booking-notification', (data) => {
        console.log('Received booking notification:', data);
        processNewNotification(data);
    });

    socket.on('booking-cancellation-notification', (data) => {
        console.log('Received booking cancellation notification:', data);
        processNewNotification(data);
    });

    socket.on('booking-rejection-notification', (data) => {
        console.log('Received booking rejection notification:', data);
        processNewNotification(data);
    });

    socket.on('booking-approval-notification', (data) => {
        console.log('Received booking approval notification:', data);
        processNewNotification(data);
    });

    // Listen for booking notifications
    socket.on('ongoing-transaction-notification', (data) => {
        console.log('Received booking notification:', data);
        processNewNotification(data);
    });

    // Listen for initial notifications on connection
    socket.on('initial-notifications', (data) => {
        if (data.success) {
            displayNotifications(data.notifications);
            updateNotificationCount(data.unreadCount);
        }
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
    function processNewNotification(data) {
        // Check if data has required fields and log if missing
        if (!data || typeof data !== 'object') {
            console.error('Invalid notification data:', data);
            return;
        }
        
        const notification = {
            action: data.action || 'Notification',
            message: data.message || 'You have a new notification',
            created_at: data.created_at || new Date().toISOString(), // Ensure it's a valid date string
            viewed: false,
        };
        
        console.log('Processed notification:', notification);

        // Avoid duplicate notifications by checking existing messages
        if (!Array.from(notificationList.children).some(item => item.innerText.includes(notification.message))) {
            displayNotifications([notification]);  // Directly updates the DOM
            updateNotificationCount((parseInt(notificationCount.innerText) || 0) + 1);  // Increment the count
        }
    }


    function displayNotifications(notifications) {
        notifications.forEach(notification => {
            const notificationItem = document.createElement('div');
            notificationItem.className = `notification-item ${notification.viewed ? '' : 'unread'}`; 
            notificationItem.innerHTML = `
                <p><strong>${notification.action}</strong>: ${notification.message}</p>
                <span>${formatDateToWords(notification.created_at)}</span>
            `;
            notificationList.prepend(notificationItem);
        });

        checkIfNoNotifications();  // Checks if notifications should display the 'No notifications' message
    }

    function updateNotificationCount(count) {
        notificationCount.innerText = count;
        notificationCount.style.display = count > 0 ? 'inline-block' : 'none';
    }

    // Toggle dropdown and mark notifications as viewed
    notificationBell.addEventListener('click', () => {
        const isDropdownVisible = notificationDropdown.style.display === 'block';
        notificationDropdown.style.display = isDropdownVisible ? 'none' : 'block';
        notificationBell.classList.toggle('active', !isDropdownVisible);

        if (isDropdownVisible) {
            fetch('http://localhost:8080/mark-notifications-viewed', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId })
            }).then(() => {
                notificationCount.style.display = 'none';
                notificationList.querySelectorAll('.notification-item.unread').forEach(item => {
                    item.classList.remove('unread');
                });
            }).catch(error => console.error('Failed to mark notifications as viewed:', error));
        }
    });

    function checkIfNoNotifications() {
        if (!notificationList.hasChildNodes()) {
            const noNotificationItem = document.createElement('div');
            noNotificationItem.className = 'no-notification-item';
            noNotificationItem.innerText = 'No new notifications';
            notificationList.appendChild(noNotificationItem);
        }
    }
});
