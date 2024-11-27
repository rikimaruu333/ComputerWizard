
<link rel="stylesheet" href="../css/system-report-user.css"> 

<div class="report-profile-modal" id="reportProfileModal">
    <div class="report-profile-container">
        <div class="report-profile-container-header">
            <h3>Report Profile</h3>
            <span class="report-profile-close-btn" id="closeReportModalBtn">&times;</span>
        </div>
        <form class="report-profile-form" id="reportForm">
            <input type="hidden" id="reportUserId" class="input">
            <div class="report-profile-content">
                <img src="" alt="" id="reportProfileImg">
                <div class="details">
                    <h3 id="reportProfileName"></h3>
                    <p id="reportProfileUsertype"></p>
                </div>
            </div>
            <div class="proof-attachment">
                <label for="proofImage" class="proof-label">Attach Proof (Image):</label>
                <div class="file-upload">
                    <input type="file" id="proofImage" class="input" accept="image/*">
                    <label for="proofImage" class="upload-btn" title="Choose file">
                        <i class="bx bxs-plus-circle"></i> 
                        <span id="proofImageMessage" class="proof-message"></span>
                    </label>
                </div>
            </div>

            <p class="report-text-header">Please select a reason for reporting this profile:</p>
            <div class="report-options">
                <label>
                    <input type="radio" name="reportReason" value="Fake Account" required>
                    This is a fake account
                </label>
                <label>
                    <input type="radio" name="reportReason" value="Impersonation">
                    This profile is impersonating me or someone else
                </label>
                <label>
                    <input type="radio" name="reportReason" value="Inappropriate Content">
                    This profile has inappropriate content
                </label>
                <label>
                    <input type="radio" name="reportReason" value="Scam">
                    This profile is a scam or fraudulent
                </label>
                <label>
                    <input type="radio" name="reportReason" value="Other">
                    Other (please specify)
                </label>
                <textarea name="otherReason" placeholder="Please specify..." rows="4" style="display:none;"></textarea>
            </div>
            <button type="submit" class="submit-report-button">Submit Report</button>
        </form>
    </div>
</div>
