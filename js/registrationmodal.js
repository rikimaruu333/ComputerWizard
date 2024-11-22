document.addEventListener('DOMContentLoaded', function () {
    var registerModal = document.getElementById('register-modal');
    var freelancerSignUp = document.getElementById('freelancer-signupBtn');
    var clientSignUp = document.getElementById('client-signupBtn');
    var registerCloseBtn = document.querySelector('.register-modal .close');
    
    // Get the hidden input fields
    var userTypeInput = document.querySelector('input[name="usertype"]');
    var statusInput = document.querySelector('input[name="status"]');

    // Get the buttons
    var nextBtn = document.querySelector('.nextBtn');
    var clientSubmitBtn = document.getElementById('client-submitBtn');

    // Event listener for Freelancer sign-up
    freelancerSignUp.addEventListener('click', function() {
        userTypeInput.value = 'Freelancer'; // Set usertype to 'Freelancer'
        statusInput.value = '0'; // Set status to '0'

        // Show the Next button and hide the Client Done button
        nextBtn.style.visibility = 'visible';
        clientSubmitBtn.style.visibility = 'hidden';

        registerModal.style.display = 'block';
    });

    // Event listener for Client sign-up
    clientSignUp.addEventListener('click', function() {
        userTypeInput.value = 'Client'; // Set usertype to 'Client'
        statusInput.value = '1'; // Set status to '1'

        // Hide the Next button and show the Client Done button
        nextBtn.style.visibility = 'hidden';
        clientSubmitBtn.style.visibility = 'visible';

        registerModal.style.display = 'block';
    });

    // Event listener for closing the modal
    registerCloseBtn.addEventListener('click', function() {
        registerModal.style.display = 'none';
    });
});




const registerModal =  document.getElementById('register-modal'),
    registrationSuccessful =  document.querySelector('.registration-successful'),
    regStep1 =  document.getElementById('firstForm'),
    regStep2 =  document.querySelector('.OTP-box'),
    regStep3 =  document.getElementById('secondForm'),
    regEmail = document.getElementById('registrationEmail'),
    verifyEmail = document.getElementById('verifyEmail'),
    inputs = document.querySelectorAll('.otp-group input'),
    sendOtpBtn = document.querySelector('.sendOtpBtn'),
    nextBtn = document.querySelector('.nextBtn'),
    clientSubmitBtn = document.getElementById('client-submitBtn'),
    backBtn = document.querySelector('.backBtn'),
    verifyOtpBtn = document.querySelector('.verifyOtpBtn'),
    verifyErrorText = document.querySelector('.verification-error-text');

let OTP = "";

window.addEventListener('load', () => {
    emailjs.init("XnzWkndgRn2h6N10o");
    regStep2.style.visibility = 'hidden';
    regStep3.style.display = 'none'; 
    verifyErrorText.style.display = 'none';
    sendOtpBtn.classList.add('disable'),
    verifyOtpBtn.classList.add('disable');
    nextBtn.classList.add("nextBtn-inactive");
    clientSubmitBtn.classList.add("inactive");
});

const validateEmail = (email) => {
    let re = /\S+@\S+\.\S+/;
    if(re.test(email)){
        sendOtpBtn.classList.remove('disable');
    }
    else{
        sendOtpBtn.classList.add('disable');
    }
}

const generateOTP = () => {
    return Math.floor(1000 + Math.random() * 9000);
};

OTP = generateOTP();

inputs.forEach((input) => {
    input.addEventListener('keyup', (e) => {
        if(e.target.value.length >= 1){
            e.target.value = e.target.value.substr(0, 1);  
        }

        if(inputs[0].value !== '' && inputs[1].value !== '' && inputs[2].value !== '' && inputs[3].value !== ''){
            verifyOtpBtn.classList.remove('disable');
        } else {
            verifyOtpBtn.classList.add('disable');
        }
    });
});


const serviceID = "service_7edsjxk";
const templateID = "template_zy21wfh";

function validateAge() {
    const ageInput = document.querySelector("#age"); 
    const ageValue = parseInt(ageInput.value); 
    const requiredAgeMsg = document.querySelector(".required-age"); 

    if (ageInput.value.trim() === "") {
        requiredAgeMsg.style.visibility = 'hidden';
        return true;
    } else if (isNaN(ageValue) || ageValue < 18) {
        requiredAgeMsg.style.visibility = 'visible';
        return false;
    } else {
        requiredAgeMsg.style.visibility = 'hidden';
        return true;
    }
}

const ageInput = document.querySelector("#age");
ageInput.addEventListener('input', () => {
    validateAge(); 
});


function validatePasswordMatch() {
    const passwordInput = document.querySelector("#password");
    const confirmPasswordInput = document.querySelector("#confirm-password");
    const confirmPasswordMsg = document.querySelector(".confirm-password");

    if (confirmPasswordInput.value.trim() === "") {
        confirmPasswordMsg.style.visibility = 'hidden';

    } else if (confirmPasswordInput.value !== passwordInput.value) {
        confirmPasswordMsg.style.visibility = 'visible';
        return false; 
        
    } else {
        confirmPasswordMsg.style.visibility = 'hidden';
        return true;
    }
}

function validatePasswordLength() {
    const passwordInput = document.querySelector("#password");
    const passwordLengthMsg = document.querySelector(".password-length-msg");
    
    if (passwordInput.value.length < 8) {
        passwordLengthMsg.style.visibility = 'visible';
        return false;
    } else {
        passwordLengthMsg.style.visibility = 'hidden'; 
        return true;
    }
}

const passwordInput = document.querySelector("#password");
const confirmPasswordInput = document.querySelector("#confirm-password");

passwordInput.addEventListener('input', () => {
    validatePasswordMatch(); 
    validatePasswordLength(); 
});

confirmPasswordInput.addEventListener('input', () => {
    validatePasswordMatch(); 
});

sendOtpBtn.addEventListener('click', () => {
    const inputs = document.querySelectorAll(".input-field .input1");
    let allFilled = true;

    inputs.forEach(input => {
        if (input.value.trim() === "") {
            allFilled = false;
        }
    });

    let ageValid = validateAge(); 
    let passwordsMatch = validatePasswordMatch();
    let passwordLengthValid = validatePasswordLength(); 

    if (allFilled && ageValid && passwordsMatch && passwordLengthValid) {
        sendOtpBtn.classList.remove('disable');
        sendOtpBtn.classList.add('active');
        sendOtpBtn.innerHTML = '<i class="bx bx-mail-send"></i><u><i> Sending...</i></u>';

        let templateParameter = {
            from_name: "GigHub Team",
            OTP: OTP,
            message: "Note: If the OTP is not working, please try to register again. Thank you.",
            reply_to: regEmail.value
        };

        emailjs.send(serviceID, templateID, templateParameter).then(
            (res) => {
                sendOtpBtn.innerHTML = '<i class="bx bx-check-circle"></i><u><i> OTP code sent!</i></u>';
                regStep2.style.visibility = 'visible';
                verifyEmail.innerHTML = regEmail.value;
            }, (err) => {
                console.log('FAILED...', err);
            }
        );
    } else {
        if (!allFilled) {
            alert('Please fill all input fields');
        } else if (!ageValid) {
            alert('Please check system requirements.'); 
        } else if (!passwordsMatch) {
            alert('Please ensure your passwords match.'); 
        } else if (!passwordLengthValid) {
            alert('Password must be at least 8 characters long.'); 
        }
    }
});

document.getElementById("registrationEmail").addEventListener('keyup', (event) => {
    checkEmailDuplication(event.target.value);
});

function checkEmailDuplication(email) {
    const validEmailMsg = document.querySelector(".valid-email");
    const sendOtpBtn = document.querySelector(".sendOtpBtn"); 
    
    if (email.trim() === "") {
        validEmailMsg.style.visibility = 'hidden'; 
        sendOtpBtn.style.display = 'block'; 
        return;
    }

    fetch("../php/check-email-duplication.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Server response:', data);
        if (data.exists) {
            validEmailMsg.style.visibility = 'visible'; 
            sendOtpBtn.style.display = 'none'; 
        } else {
            validEmailMsg.style.visibility = 'hidden'; 
            sendOtpBtn.style.display = 'block'; 
        }
    })
    .catch(error => {
        console.error('Error checking email:', error);
    });
}




verifyOtpBtn.addEventListener('click', () => {  
    let values = "";
    inputs.forEach((input) => {
        values+=input.value;
    })

    if(OTP == values){
        regStep2.style.visibility = 'hidden';
        nextBtn.classList.remove("nextBtn-inactive");
        clientSubmitBtn.classList.remove("inactive");
        sendOtpBtn.innerHTML = '<i class="bx bxs-badge-check"></i><u><i> Email verified!</i></u>';
    } else {
        verifyOtpBtn.classList.add("error-shake");
        verifyErrorText.style.display = 'block';
        setTimeout(() => {
            verifyOtpBtn.classList.remove("error-shake");
        }, 1000);
    }
});

nextBtn.addEventListener('click', () => { 
    const inputs = document.querySelectorAll(".input-field .input1"); 
    let allFilled = true;

    inputs.forEach(input => {
        if (input.value.trim() === "") {
            allFilled = false;
        }
    });
    
    let ageValid = validateAge(); 
    let passwordsMatch = validatePasswordMatch();

    if (allFilled && ageValid && passwordsMatch) { 
        regStep1.style.display = 'none';
        regStep3.style.display = 'block';
    } else {
        if (!allFilled) {
            alert('Please fill all input fields');
        } else if (!ageValid) {
            alert('Please check system requirements.'); 
        } else if (!passwordsMatch) {
            alert('Please ensure your passwords match.'); 
        }
    }
});

backBtn.addEventListener('click', () => {  
    regStep1.style.display = 'block';
    regStep3.style.display = 'none';
});








const files = document.querySelector(".file-upload-wrapper .files"),
    fileInput = document.querySelector(".file-upload-input"),
    progressArea = document.querySelector(".progress-area"),
    uploadedArea = document.querySelector(".uploaded-area");

files.addEventListener("click", () => {
  fileInput.click();
});

fileInput.onchange = ({ target }) => {
  let file = target.files[0];
  if (file) {
    let fileName = file.name;
    if (fileName.length >= 12) {
      let splitName = fileName.split('.');
      fileName = splitName[0].substring(0, 10) + "... ." + splitName[1];
    }
    uploadFile(file, fileName);
  }
};

function uploadFile(file, name) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/registration-file-upload.php");
    let fileSize;

    xhr.upload.addEventListener("progress", ({ loaded, total }) => {
      let fileLoaded = Math.floor((loaded / total) * 100);
      let fileTotal = Math.floor(total / 1000);
      (fileTotal < 1024) ? fileSize = fileTotal + " KB" : fileSize = (loaded / (1024 * 1024)).toFixed(2) + " MB";
  
      let progressHTML = `<li class="row">
                            <i class="bx bxs-file"></i>
                            <div class="content">
                              <div class="details">
                                <span class="name">${name} • Uploading</span>
                                <span class="percent">${fileLoaded}%</span>
                              </div>
                              <div class="progress-bar">
                                <div class="progress" style="width: ${fileLoaded}%"></div>
                              </div>
                            </div>
                          </li>`;
      uploadedArea.classList.add("onprogress");
      progressArea.innerHTML = progressHTML;
    });
  
    xhr.onload = function () {
      if (xhr.status === 200) {
        let response = JSON.parse(xhr.responseText);
        if (response.status === 'success') {
          let uniqueFileName = response.fileName;  
          progressArea.innerHTML = "";
          let uploadedHTML = `<li class="row">
                                <div class="content upload">
                                  <i class="bx bxs-file"></i>
                                  <div class="details">
                                    <span class="name">${name} • Uploaded</span>
                                    <span class="size">${fileSize}</span>
                                  </div>
                                </div>
                                <i class="bx bx-x" id="upload-cancel" data-filename="${uniqueFileName}"></i>
                              </li>`;
          uploadedArea.classList.remove("onprogress");
          uploadedArea.insertAdjacentHTML("afterbegin", uploadedHTML);
  
          document.querySelector("#upload-cancel").addEventListener("click", function () {
            let fileToDelete = this.getAttribute("data-filename");
            cancelFileUpload(fileToDelete);
            this.closest("li").remove();  
          });
        } else {
          console.log(response.message);  
        }
      }
    };
  
    let data = new FormData();
    data.append("file", file); 
    xhr.send(data);
  }
  
  function cancelFileUpload(fileName) {
    let cancelXHR = new XMLHttpRequest();
    cancelXHR.open("POST", "../php/registration-file-upload-delete.php", true);
    cancelXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    cancelXHR.send("fileName=" + encodeURIComponent(fileName));
  
    cancelXHR.onload = function () {
      if (cancelXHR.status === 200) {
        let response = JSON.parse(cancelXHR.responseText);
        console.log(response.message);
      } else {
        console.log("Error deleting the file.");
      }
    };
  }
  



$(document).ready(function() {
    $('#registrationForm').on('submit', function(event) {
        event.preventDefault(); 

        const formData = new FormData(this);
        const fileInput = document.querySelector(".file-upload-input");
        const userType = $('input[name="usertype"]').val(); // Get the usertype value

        // Append file data if available
        if (fileInput.files.length > 0) {
            formData.append('file', fileInput.files[0]);
        }
        // Add an action identifier
        formData.append('action', 'usersignup');

        $.ajax({
            type: 'POST',
            url: '../php/registration-process.php',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (userType === 'Freelancer') {
                        // Show the Freelancer success message
                        $('.registration-successful-freelancer').addClass("success-shake").show();
                        setTimeout(() => {
                            $('.registration-successful-freelancer').removeClass("success-shake").hide();
                            $('#register-modal').hide();
                        }, 1500);
                    } else {
                        // Show the Client success message
                        $('.registration-successful-client').addClass("success-shake").show();
                        setTimeout(() => {
                            $('.registration-successful-client').removeClass("success-shake").hide();
                            $('#register-modal').hide();
                        }, 1500);
                    }
                } else {
                    if (response.errors) {
                        response.errors.forEach(error => {
                            alert(error); 
                        });
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error); 
            }
        });
    });
});
