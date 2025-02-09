<link rel="stylesheet" href="../css/registrationmodal.css">
<div class="register-modal" id="register-modal">
    <div class="register-modal-content">
        <div class="register-container">
            <span class="close" id="close">&times;</span>
            <header class="registration-header">
                Registration
            </header>

            <form class="register-form" id="registrationForm">
                <input type="hidden" name="usertype" class="input" value="">
                <input type="hidden" name="profile" class="input" value="../images/user.jpg">
                <input type="hidden" name="status" class="input" value="">

                <div id="firstForm" class="form first">
                    <div class="registration-successful-client">
                        <div class="registration-success">
                            <header>
                                <i class="bx bxs-check-shield"></i>
                            </header>
                            <h4>Registration Successful</h4>
                            <p>Thanks for registering!</p>
                            <br>
                        </div>
                    </div>
                    <div class="details personal">
                        <span class="title">Personal Details</span>

                        <div class="fields">
                            <div class="input-field">
                                <label>First Name</label>
                                <input type="text" name="firstname" class="input1" placeholder="Enter first name" required>
                            </div>
                            <div class="input-field">
                                <label>Last Name</label>
                                <input type="text" name="lastname" class="input1" placeholder="Enter last name" required>
                            </div>
                            <div class="input-field">
                                <label>Address</label>
                                <select class="input1" name="address" id="address" required>
                                    <option value="">Select Address</option>
                                    <option value="Anonang Norte, Bogo City, Cebu">Anonang Norte, Bogo City, Cebu</option>
                                    <option value="Anonang Sur, Bogo City, Cebu">Anonang Sur, Bogo City, Cebu</option>
                                    <option value="Banban, Bogo City, Cebu">Banban, Bogo City, Cebu</option>
                                    <option value="Binabag, Bogo City, Cebu">Binabag, Bogo City, Cebu</option>
                                    <option value="Bungtod, Bogo City, Cebu">Bungtod, Bogo City, Cebu</option>
                                    <option value="Carbon, Bogo City, Cebu">Carbon, Bogo City, Cebu</option>
                                    <option value="Cayang, Bogo City, Cebu">Cayang, Bogo City, Cebu</option>
                                    <option value="Cogon, Bogo City, Cebu">Cogon, Bogo City, Cebu</option>
                                    <option value="Dakit, Bogo City, Cebu">Dakit, Bogo City, Cebu</option>
                                    <option value="Don Pedro, Bogo City, Cebu">Don Pedro, Bogo City, Cebu</option>
                                    <option value="Gairan, Bogo City, Cebu">Gairan, Bogo City, Cebu</option>
                                    <option value="Guadalupe, Bogo City, Cebu">Guadalupe, Bogo City, Cebu</option>
                                    <option value="La Paz, Bogo City, Cebu">La Paz, Bogo City, Cebu</option>
                                    <option value="LPC, Bogo City, Cebu">LPC, Bogo City, Cebu</option>
                                    <option value="Libertad, Bogo City, Cebu">Libertad, Bogo City, Cebu</option>
                                    <option value="Lourdes, Bogo City, Cebu">Lourdes, Bogo City, Cebu</option>
                                    <option value="Malingin, Bogo City, Cebu">Malingin, Bogo City, Cebu</option>
                                    <option value="Marangog, Bogo City, Cebu">Marangog, Bogo City, Cebu</option>
                                    <option value="Nailon, Bogo City, Cebu">Nailon, Bogo City, Cebu</option>
                                    <option value="Odlot, Bogo City, Cebu">Odlot, Bogo City, Cebu</option>
                                    <option value="Pandan, Bogo City, Cebu">Pandan, Bogo City, Cebu</option>
                                    <option value="Polambato, Bogo City, Cebu">Polambato, Bogo City, Cebu</option>
                                    <option value="Sambag, Bogo City, Cebu">Sambag, Bogo City, Cebu</option>
                                    <option value="San Vicente, Bogo City, Cebu">San Vicente, Bogo City, Cebu</option>
                                    <option value="Siocon, Bogo City, Cebu">Siocon, Bogo City, Cebu</option>
                                    <option value="Sto. Nino, Bogo City, Cebu">Sto. Nino, Bogo City, Cebu</option>
                                    <option value="Sto. Rosario, Bogo City, Cebu">Sto. Rosario, Bogo City, Cebu</option>
                                    <option value="Sudlonon, Bogo City, Cebu">Sudlonon, Bogo City, Cebu</option>
                                    <option value="Taytayan, Bogo City, Cebu">Taytayan, Bogo City, Cebu</option>
                                </select>
                            </div>
                            <div class="input-field">
                                <label>Phone</label>
                                <input type="number" name="phone" class="input1" placeholder="Enter phone number" required>
                            </div>
                            <div class="input-field">
                                <label>Age</label>
                                <input type="number" name="age" class="input1" id="age" placeholder="Enter your age" required>
                                <p class="required-age"><i class="bx bx-info-circle"></i><i> User must be 18+</i></p>
                            </div>
                            <div class="input-field">
                                <label>Gender</label>
                                <select class="input1" name="gender" id="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="details ID">
                        <span class="title">Account Details</span>

                        <div class="fields">
                            <div class="input-field">
                                <label>Email</label>
                                <input type="email" name="email" class="input1" placeholder="Enter email" id="registrationEmail" onkeyup="validateEmail(this.value)" required>
                                <p class="sendOtpBtn"><i class="bx bxs-right-arrow-circle"></i><u> Send OTP code</u></p>
                                <p class="valid-email"><i class="bx bx-info-circle"></i><i> Email already registered</i></p>
                            </div>
                            <div class="input-field">
                                <label>Password</label>
                                <input type="password" name="password" class="input1" id="password" placeholder="Enter password" required>
                                <p class="password-length-msg"><i class="bx bx-info-circle"></i><i> Password must be at least 8 characters long</i></p>
                            </div>
                            <div class="input-field">
                                <label>Confirm Password</label>
                                <input type="password" class="input1" id="confirm-password" placeholder="Confirm password" required>
                                <p class="confirm-password"><i class="bx bx-info-circle"></i><i> Password must match</i></p>
                            </div>
                        </div>
                        <div class="OTP-container OTP-box">
                            <header>
                                <i class="bx bxs-check-shield"></i>
                            </header>
                            <h4>Enter OTP Code</h4>
                            <p>Check your email</p>
                            <i id="verifyEmail"></i>
                            <div class="input-field otp-group">
                                <input type="text" />
                                <input type="text" />
                                <input type="text" />
                                <input type="text" />
                            </div>
                            <i class="verification-error-text">Incorrect 4-digit OTP</i>
                            <div class="verifyOtpBtn verify-button">Verify OTP</div>
                        </div>
                    </div>
                    <div class="nextBtn next-button">
                        <span class="btnText">Next</span>
                        <i class="bx bxs-chevron-right"></i>
                    </div>
                    <button class="submitBtn submit-button" style="visibility: hidden" id="client-submitBtn">
                        <span class="btnText">Done</span>
                        <i class="bx bxs-chevron-right"></i>
                    </button>
                </div>
                
                <div id="secondForm" class="form second">
                    <div class="registration-successful-freelancer">
                        <div class="registration-success">
                            <header>
                                <i class="bx bxs-check-shield"></i>
                            </header>
                            <h4>Registration Successful</h4>
                            <p>Thanks for registering!</p>
                            <br>
                        </div>
                    </div>
                    <div class="details">
                        <div class="select-id-type-container">
                            <select name="valid_id_type" id="valid_id_type">
                                <option value="" disabled selected>Select an ID</option>
                                <option value="ACR/ICR">ACR/ICR</option>
                                <option value="Barangay Certification">Barangay Certification</option>
                                <option value="Driver’s License">Driver’s License</option>
                                <option value="City Health Card/Health Certificate Card">City Health Card/Health Certificate Card</option>
                                <option value="GSIS e-Card">GSIS e-Card</option>
                                <option value="Company ID">Company ID</option>
                                <option value="Integrated Bar of the Philippines">Integrated Bar of the Philippines</option>
                                <option value="DSWD Certification">DSWD Certification</option>
                                <option value="Maritime Industry Authority (MARINA) ID">Maritime Industry Authority (MARINA) ID</option>
                                <option value="GOCC ID">GOCC ID</option>
                                <option value="NCDA ID">NCDA ID</option>
                                <option value="NBI Clearance">NBI Clearance</option>
                                <option value="Passport">Passport</option>
                                <option value="OFW ID">OFW ID</option>
                                <option value="Postal ID">Postal ID</option>
                                <option value="OWWA ID">OWWA ID</option>
                                <option value="PRC ID">PRC ID</option>
                                <option value="Pag-IBIG Loyalty Card">Pag-IBIG Loyalty Card</option>
                                <option value="School ID">School ID</option>
                                <option value="PhilHealth Insurance Card ng Bayan (PHICB)">PhilHealth Insurance Card ng Bayan (PHICB)</option>
                                <option value="Senior Citizen Card">Senior Citizen Card</option>
                                <option value="Police Clearance">Police Clearance</option>
                                <option value="SSS Card">SSS Card</option>
                                <option value="Postal ID (Paper-based card)">Postal ID (Paper-based card)</option>
                                <option value="Unified Multi-purpose ID">Unified Multi-purpose ID</option>
                                <option value="Seaman’s Book">Seaman’s Book</option>
                                <option value="Voter’s ID">Voter’s ID</option>
                                <option value="Tax Identification Number (TIN)">Tax Identification Number (TIN)</option>
                                <option value="Philippine Identification System (PhilSys) ID">Philippine Identification System (PhilSys) ID</option>
                            </select>
                        </div>
                        <span class="title">Attach valid ID</span>

                        <div class="fields">
                            <div class="upload-files">
                                <div class="file-upload-wrapper">
                                    <div class="files" id="file-upload-form">
                                        <input class="file-upload-input" type="file" name="file" accept="*/*" hidden>
                                        <i class="bx bxs-cloud-upload"></i>
                                        <p>Browse File to Upload</p>
                                    </div>
                                    <div class="uploaded-file-section">
                                        <section class="progress-area"></section>
                                        <section class="uploaded-area"></section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="backBtn back-button">
                        <i class="bx bxs-chevron-left"></i>
                        <span class="btnText">Back</span>
                    </div>
                    <button class="submitBtn submit-button" id="freelancer-submitBtn">
                        <span class="btnText">Done</span>
                        <i class="bx bxs-chevron-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


