const searchBar = document.querySelector(".search input"),
searchIcon = document.querySelector(".search button"),
usersList = document.querySelector(".users-list");

searchIcon.onclick = ()=>{
  searchBar.classList.toggle("show");
  searchIcon.classList.toggle("active");
  searchBar.focus();
  if(searchBar.classList.contains("active")){
    searchBar.value = "";
    searchBar.classList.remove("active");
  }
}

searchBar.onkeyup = ()=>{
  let searchTerm = searchBar.value;
  if(searchTerm != ""){
    searchBar.classList.add("active");
  }else{
    searchBar.classList.remove("active");
  }
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../chatphp/system-messaging-search.php", true);
  xhr.onload = ()=>{
    if(xhr.readyState === XMLHttpRequest.DONE){
        if(xhr.status === 200){
          let data = xhr.response;
          usersList.innerHTML = data;
        }
    }
  }
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("searchTerm=" + searchTerm);
}

setInterval(() =>{
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "../chatphp/system-messaging-users.php", true);
  xhr.onload = ()=>{
    if(xhr.readyState === XMLHttpRequest.DONE){
        if(xhr.status === 200){
          let data = xhr.response;
          if(!searchBar.classList.contains("active")){
            usersList.innerHTML = data;
          }
        }
    }
  }
  xhr.send();
}, 500);


$(document).ready(function() {
  // Event listener for clicking on a user to start chatting
  $(document).on('click', '.chat-link', function(event) {
    event.preventDefault(); // Prevent the default anchor behavior
    const userId = $(this).data('id');
    const userType = $(this).data('usertype');
    $('#default-userinfo, #freelancer-userinfo, #client-userinfo').hide();

    // Clear previous user info content
    $('#freelancer-userinfo .freelancer-userinfo-details').empty();
    $('#freelancer-userinfo .freelancer-userinfo-data').empty();
    $('#client-userinfo .client-userinfo-details').empty();
    $('#client-userinfo .client-userinfo-data').empty();
    $('#default-userinfo .default-userinfo-details').empty();
    $('#default-userinfo .default-userinfo-data').empty();

    $.ajax({
        url: '../chatphp/system-messaging-chat-template.php',
        type: 'GET',
        data: { id: userId, usertype: userType },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const profilePic = response.profile; // User profile picture
                const fullName = response.fullname; // User full name
                const email = response.email; // User email
                const phone = response.phone; // User phone number

                if (response.usertype === 'Freelancer') {
                    // Build freelancer info
                    const freelancerDetails = `
                        <img src="${profilePic}" alt="" class="freelancer-profile-pic">
                        <h4 class="user-name">${fullName}</h4>
                        <p class="user-role">${email}</p>
                        <div class="freelancer-button">
                          <div class="freelancer-button-details">
                              <i class="fas fa-user-circle"></i>
                              <span>Profile</span>
                          </div>
                        </div>
                        
                    `;
                    const freelancerData = `
                        <div class="freelancer-user-phone">
                            <i class="bx bxs-phone"></i>
                            <p id="freelancer-user-phone">${phone}</p>
                        </div>
                        <div class="freelancer-user-transactions">
                            <i class="bx bxs-bookmarks
                            "></i>
                            <p id="freelancer-user-transactions">21 transactions</p>
                        </div>
                        <div class="freelancer-user-recommendation">
                            <i class="fas fa-thumbs-up"></i>
                            <p id="freelancer-user-recommendation">19 recommendations</p>
                        </div>
                    `;

                    // Append the generated HTML to the freelancer info container
                    $('#freelancer-userinfo .freelancer-userinfo-details').append(freelancerDetails);
                    $('#freelancer-userinfo .freelancer-userinfo-data').append(freelancerData);
                    $('#freelancer-userinfo').show(); // Show freelancer info

                } else if (response.usertype === 'Client') {
                    // Build client info
                    const clientDetails = `
                        <img src="${profilePic}" alt="" class="client-profile-pic">
                        <h4 class="user-name">${fullName}</h4>
                        <p class="user-role">${email}</p>
                        <div class="client-button">
                          <div class="client-button-details">
                              <i class="fas fa-user-circle"></i>
                              <span>Profile</span>
                          </div>
                        </div>
                    `;
                    const clientData = `
                        <div class="client-user-phone">
                            <i class="bx bxs-phone"></i>
                            <p id="client-user-phone">${phone}</p>
                        </div>
                        <div class="client-user-transactions">
                            <i class="bx bxs-bookmarks
                            "></i>
                            <p id="client-user-transactions">21 transactions</p>
                        </div>
                        <div class="client-user-jobposts">
                            <i class="bx bxs-receipt"></i>
                            <p id="client-user-jobposts">30 job posts</p>
                        </div>
                    `;

                    // Append the generated HTML to the client info container
                    $('#client-userinfo .client-userinfo-details').append(clientDetails);
                    $('#client-userinfo .client-userinfo-data').append(clientData);
                    $('#client-userinfo').show(); // Show client info

                } else {
                    // For admin or if no specific chat is selected, show the default info
                    const defaultDetails = `
                        <img src="../images/gighublogo.png" alt="" id="default-profile-pic">
                        <h4 class="user-name" id="default-user-name">GIGHUB</h4>
                    `;
                    const defaultData = `
                        <p id="default-user-message">
                            Effective communication is at the heart of successful collaborations, and <span>GigHub's</span> messaging feature is designed to make that <u>easy</u>, <u>efficient</u>, and <u>seamless</u>. <br> <br> Whether you're a freelancer connecting with a client or a business reaching out to talent, our messaging system <span>allows you to communicate in real time</span>, keeping every conversation organized and accessible.
                        </p>
                    `;
                    // Append the generated HTML to the default info container
                    $('#default-userinfo .default-userinfo-details').append(defaultDetails);
                    $('#default-userinfo .default-userinfo-data').append(defaultData);
                    $('#default-userinfo').show();
                }

                console.log(response);
                // Populate the chat template dynamically
                let chatTemplate = `
                  <div class="wrapper chating">
                      <section class="chat-area">
                          <header>
                              <img src="${response.profile}" alt="Profile Picture">
                              <div class="details">
                                  <span>${response.firstname} ${response.lastname}</span>
                                  <p>${response.usertype}</p>
                              </div>
                          </header>
                          <div class="chat-box">
                              <!-- Chat messages will be loaded here -->
                          </div>
                          <form action="#" class="typing-area">
                              <input type="text" class="incoming_id" name="incoming_id" value="${response.incoming_id}" hidden>
                              <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
                              <button><i class="fab fa-telegram-plane"></i></button>
                          </form>
                      </section>
                  </div>`;

                // Insert the chat template into the messaging-messages-container
                $('.messaging-messages-container').html(chatTemplate);
                // Fetch the chat messages after populating the template
                fetchChatMessages(response.incoming_id);
            } else {
                console.log('User not found or an error occurred.');
                toastr.error(response.message); // Optional notification using Toastr
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching user data:', error);
            toastr.error('Failed to load chat. Please try again.');
        }
    });
});

  $('.messaging-messages-container').on('submit', '.typing-area', function(event) {
    event.preventDefault(); // Prevent the form from submitting normally
    
    const incomingId = $('.incoming_id').val(); // Get the incoming user ID
    const message = $(this).find('.input-field').val(); // Get the message text
    
    if (message.trim() === "") {
        return; // Don't send empty messages
    }
    
    // AJAX request to send the message
    $.ajax({
        url: '../chatphp/system-messaging-insert-chat.php', // Your PHP file for sending messages
        type: 'POST',
        data: {
            incoming_id: incomingId,
            message: message
        },
        dataType: 'json', // Expecting JSON response
        success: function(response) {
            if (response.status === 'success') {
                // Clear the input field after sending
                $('.input-field').val('');
                // Optionally, you can fetch the updated chat messages
                fetchChatMessages(incomingId);
            } else {
                toastr.error(response.message); // Display error message
            }
        },
        error: function(xhr, status, error) {
            console.error('Error sending message:', error);
            toastr.error('Failed to send message. Please try again.');
        }
    });
});

  function fetchChatMessages(incomingId) {
    $.ajax({
      url: '../chatphp/system-messaging-get-chat.php',
      type: 'POST',
      data: { incoming_id: incomingId },
      dataType: 'json',  // Specify that we expect JSON in response
      success: function(response) {
          if (response.error) {
              console.log(response.error);
          } else {
              // Assuming the response is a list of messages
              $('.chat-box').empty();
              response.forEach(function(message) {
                var chatHtml = '';
                
                if (message.outgoing) {
                    // Outgoing message block
                    chatHtml = '<div class="chat outgoing">' +
                               '<div class="details">' +
                               '<p>' + message.msg + '</p>' +
                               '<div class="msg_datetime">' + message.datetime + '</div>' +
                               '</div></div>';
                } else {
                    // Incoming message block
                    chatHtml = '<div class="chat incoming">' +
                               '<img src="' + message.profile + '" alt="">' +
                               '<div class="details">' +
                               '<p>' + message.msg + '</p>' +
                               '<div class="msg_datetime">' + message.datetime + '</div>' +
                               '</div></div>';
                }
            
                $('.chat-box').append(chatHtml);
            });
            scrollToBottom();
          }
      },
      error: function(xhr, status, error) {
          console.error('Error fetching chat messages:', error);
          console.log(xhr.responseText);  // Log the raw error response
      }
  });
  }
  function scrollToBottom() {
    const chatBox = document.querySelector('.chat-box');
    chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom
}

});
