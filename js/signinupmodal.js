document.addEventListener('DOMContentLoaded', function () {
    var authModal = document.getElementById('auth-modal');
    var signInBtn = document.getElementById('sign-in-btn');
    var closeBtn = document.querySelector('.auth-modal .close');
    var triggers = document.querySelectorAll('.trigger-auth');

    signInBtn.addEventListener('click', function() {
        authModal.style.display = 'block';
    });

    triggers.forEach(function(trigger) {
        trigger.addEventListener('click', function() {
            authModal.style.display = 'block';
        });
    });

    closeBtn.addEventListener('click', function() {
        authModal.style.display = 'none';
    });
});






const container = document.getElementById('container');
const closebtn = document.getElementById('close');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
    closebtn.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
    closebtn.classList.remove("active");
});




// let eyeicon = document.getElementById("eyeicon");
// let password = document.getElementById("loginpassword");

// eyeicon.onclick = function(){
//     if(password.type == "password"){
//         password.type = "text";
//         eyeicon.src = "pics/blackhide.png";
//     }
//     else{
//         password.type = "password";
//         eyeicon.src = "pics/show.png";
//     }
// }


document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the form from submitting the default way

    var formData = new FormData(this);

    fetch('overview.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        var errorBox = document.querySelector('.error-box'); // Ensure this is the correct selector

        if (errorBox) {
            errorBox.style.display = 'none'; // Hide the error box initially

            if (data.errors && data.errors.length > 0) {
                errorBox.innerHTML = data.errors.join('<br>');
                errorBox.style.display = 'block'; // Show the error box
            } else if (data.redirect) {
                window.location.href = data.redirect;
            }
        } else {
            console.error('Error box element not found.');
        }
    })
    .catch(error => console.error('Error:', error));
});
