document.addEventListener("DOMContentLoaded", function () {

    const profileTab = document.getElementById("profileTab");
    const accountTab = document.getElementById("accountTab");
    const profileContent = document.getElementById("profileContent");
    const accountContent = document.getElementById("accountContent");
    const settingsModal = document.getElementById("settingsModal");
    const openUserSettings = document.getElementById("openUserSettings");
    const closeUserSettings = document.getElementById("closeUserSettings");

    profileTab.classList.add("active");

    openUserSettings.onclick = function() {
        settingsModal.style.display = "block"; 
        openUserSettings.classList.add("active"); 
        fetchUserData();
    };
    
    closeUserSettings.onclick = function() {
        settingsModal.style.display = "none";
        openUserSettings.classList.remove("active"); 
    };

    profileTab.addEventListener("click", function () {  
        profileTab.classList.add("active");
        accountTab.classList.remove("active");

        profileContent.style.display = "block";
        accountContent.style.display = "none";
    });

    accountTab.addEventListener("click", function () {
        accountTab.classList.add("active");
        profileTab.classList.remove("active");

        accountContent.style.display = "block";
        profileContent.style.display = "none";
    });

    function fetchUserData() {
        $.ajax({
            url: 'system-get-user-data.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    populateSettingsModal(response.data);
                    console.log(response.data);
                } else {
                    alert(response.message); 
                }
            },
            error: function () {
                alert('Error fetching sser data');
            }
        });
    }
    
    function populateSettingsModal(data) {
        $('#settingsFirstName').val(data.firstname);
        $('#settingsLastName').val(data.lastname);
        $('#settingsEmail').val(data.email);
        $('#settingsAddress').val(data.address); 
        $('#settingsGender').val(data.gender);
        
        if (data.profile) {
            $('#settingsProfileImage').attr('src', data.profile);

            if (data.usertype === 'Freelancer') {
                $('.freelancer-img img#profile').attr('src', data.profile);
            } else if (data.usertype === 'Client') {
                $('.client-img img#profile').attr('src', data.profile);
            }

        } else {
            $('#settingsProfileImage').attr('src', '../images/user.jpg'); 
        }
    }
});

document.addEventListener("DOMContentLoaded", function () {

    toastr.options = {
        "closeButton": true,
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

    const deleteProfilePictureBtn = document.getElementById("deleteProfilePictureBtn");
    const changeProfilePictureBtn = document.getElementById("changeProfilePictureBtn");
    const profilePictureInput = document.getElementById("profilePictureInput");
    
    changeProfilePictureBtn.onclick = function () {
        profilePictureInput.click();
    };

    profilePictureInput.onchange = function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#settingsProfileImage').attr('src', e.target.result); // Preview the image
            };
            reader.readAsDataURL(file);
    
            // Call the upload function after reading the file
            uploadProfilePicture(file);
        }
    };

    deleteProfilePictureBtn.onclick = function () {
        if (confirm("Are you sure you want to delete your profile picture?")) {
            $.ajax({
                url: 'system-user-delete-profile.php', 
                type: 'POST',
                success: function (response) {
                    const jsonResponse = JSON.parse(response); // Parse JSON response
                    
                    if (jsonResponse.status === 'success') {
                        $('#settingsProfileImage').attr('src', '../images/user.jpg'); 
                        toastr.success(jsonResponse.message); // Display success message
                        fetchUserData(); // Fetch updated data
                    } else {
                        toastr.error(jsonResponse.message); // Display error message
                    }
                },
                error: function () {
                    toastr.error('Error deleting profile picture');
                }
            });
        }
    };
    

    function uploadProfilePicture(file) {
        const formData = new FormData();
        formData.append('profilePicture', file);
        
        $.ajax({
            url: 'system-user-change-profile.php', 
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                const jsonResponse = JSON.parse(response); // Parse JSON response
                
                if (jsonResponse.status === 'success') {
                    $('#settingsProfileImage').attr('src', jsonResponse.data);
                    toastr.success(jsonResponse.message); 
                    fetchUserData(); // Fetch updated data
                } else {
                    toastr.error(jsonResponse.message); // Display error message
                }
            },
            error: function () {
                toastr.error('Error uploading profile picture');
            }
        });
    }
    
});

$(document).ready(function() {
    $('#updateProfileSettingsBtn').on('click', function(e) {
        e.preventDefault();
        
        const firstName = $('#settingsFirstName').val();
        const lastName = $('#settingsLastName').val();
        const address = $('#settingsAddress').val();
        const gender = $('#settingsGender').val();
        const id = $('#settingsId').val();

        if (firstName || lastName || address || gender) {
            $.ajax({
                type: 'POST',
                url: 'system-user-update-profile-settings.php',
                data: {
                    firstName: firstName,
                    lastName: lastName,
                    address: address,
                    gender: gender,
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Show success message
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while updating profile details.');
                }
            });
        } else {
            toastr.warning('Please fill in at least one field to update.');
        }
    });
});


$(document).ready(function() {
    // Function to validate password match in real-time
    function validatePasswordMatch() {
        const passwordInput = document.querySelector("#newPassword");
        const confirmPasswordInput = document.querySelector("#confirmPassword");
        const confirmPasswordMsg = document.querySelector(".confirm-password");

        // Check if confirm password input is empty
        if (confirmPasswordInput.value.trim() === "") {
            confirmPasswordMsg.style.visibility = 'hidden';
        } 
        // Check if passwords match
        else if (confirmPasswordInput.value !== passwordInput.value) {
            confirmPasswordMsg.style.visibility = 'visible';
            confirmPasswordMsg.textContent = "Passwords do not match.";
            return false; 
        } else {
            confirmPasswordMsg.style.visibility = 'hidden';
            return true;
        }
    }

    // Function to validate password length in real-time
    function validatePasswordLength() {
        const passwordInput = document.querySelector("#newPassword");
        const passwordLengthMsg = document.querySelector(".password-length-msg");

        // Check if password length is less than 8
        if (passwordInput.value.length < 8) {
            passwordLengthMsg.style.visibility = 'visible';
            passwordLengthMsg.textContent = "Password must be at least 8 characters long.";
            return false;
        } else {
            passwordLengthMsg.style.visibility = 'hidden';
            return true;
        }
    }

    // Event listeners for password validation on input
    const passwordInput = document.querySelector("#newPassword");
    const confirmPasswordInput = document.querySelector("#confirmPassword");

    passwordInput.addEventListener('input', () => {
        validatePasswordMatch(); 
        validatePasswordLength(); 
    });

    confirmPasswordInput.addEventListener('input', () => {
        validatePasswordMatch(); 
    });

    // Form submission for account settings
    $('#accountSettingsForm').on('submit', function(event) {
        event.preventDefault(); 

        const oldPassword = $('#oldPassword').val();
        const newPassword = $('#newPassword').val();
        const confirmPassword = $('#confirmPassword').val();

        // Validate before submission
        let passwordsMatch = validatePasswordMatch();
        let passwordLengthValid = validatePasswordLength(); 

        // Check if passwords match and are valid
        if ((newPassword === confirmPassword) && passwordsMatch && passwordLengthValid) {
            $.ajax({
                type: 'POST',
                url: 'system-user-update-account-settings.php', 
                data: {
                    oldPassword: oldPassword,
                    newPassword: newPassword,
                    id: $('#settingsId').val() 
                },
                success: function(response) {
                    toastr.success(response.message); 
                },
                error: function(xhr, status, error) {
                    toastr.error(xhr.responseJSON.message); 
                }
            });
        } else {
            toastr.error("Please ensure the passwords match and meet the required criteria.");
        }
    });
});
