
<link rel="stylesheet" href="../css/system-user-settings.css">
<div class="settings-modal" id="settingsModal">
    <div class="settings-container">
        <div class="settings-header">
            <h2>Settings</h2>
            <span class="close-settings-modal" id="closeUserSettings">&times;</span>
        </div>
        <div class="settings-body">
            <div class="settings-sidebar">
                <ul>
                    <li class="" id="profileTab"><i class="bx bxs-user"></i> Profile</li>
                    <li class="" id="accountTab"><i class="bx bxs-cog"></i> Account</li>
                </ul>
            </div>
            <div class="profile-settings-content" id="profileContent">
                <div class="profile-section">
                    <h3>Profile Picture</h3>
                    <div class="profile-picture">
                        <img src="" alt="" id="settingsProfileImage">
                        <div class="profile-buttons">
                            <button class="btn btn-change" id="changeProfilePictureBtn">Change picture</button>
                            <button class="btn btn-delete" id="deleteProfilePictureBtn">Delete picture</button>
                        </div>
                    </div>
                    <input type="file" id="profilePictureInput" style="display: none;" accept="image/*">
                    <div class="profile-details">
                        <form action="">
                            <input type="hidden" id="settingsId" name="id" value="<?=$_SESSION['USER']->id; ?>">

                            <label for="email">Email</label>
                            <input type="text" id="settingsEmail" placeholder="Email" readonly required>
                            <p class="email-info"><i class="bx bx-info-circle"></i> Email cannot be changed.</p>
                            
                            <label for="firstName">First Name</label>
                            <input type="text" id="settingsFirstName" placeholder="First Name" required>

                            <label for="lastName">Last Name</label>
                            <input type="text" id="settingsLastName" placeholder="Last Name" required>

                            <label for="address">Address</label>
                            <select class="" name="address" id="settingsAddress" required>
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

                            <label for="gender">Gender</label>
                            <select class="" name="gender" id="settingsGender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>

                            <button class="btn btn-save" id="updateProfileSettingsBtn" type="submit">Save changes</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="account-settings-content" id="accountContent">
                <div class="account-section">
                    <h3>Account Settings</h3>
                    <div class="account-details">
                        <form action="" id="accountSettingsForm">
                            <label for="password">Enter Password</label>
                            <input type="password" id="oldPassword" placeholder="Old Password" required>

                            <label for="password">Change Password</label>
                            <input type="password" id="newPassword" placeholder="New Password" required>
                            <p class="password-length-msg"><i class="bx bx-info-circle"></i><span> Password must be at least 8 characters long</span></p>

                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" id="confirmPassword" placeholder="Confirm New Password" required>
                            <p class="confirm-password"><i class="bx bx-info-circle"></i><span> Password must match</span></p>

                            <button class="btn btn-save" id="updateAccountSettingsBtn" type="submit">Save changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>