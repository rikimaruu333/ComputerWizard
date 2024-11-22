<link rel="stylesheet" href="../css/system-restrict-user.css">
    
    <div class="restrict-modal" id="restrictModal">
        <div class="restrict-container">
            <div class="restrict-container-header">
                <h3>Restrict User</h3>
                <span class="restrict-close-btn" id="closeRestrictModalBtn">&times;</span>
            </div>
            <div class="restrict-view-reports">
                <u id="viewReportListBtn">View reports on this user.</u>
            </div>
            <div class="restrict-details">
                <form id="restrictUserForm">
                    <input type="hidden" id="restrictUserId" name="restrictUserId">

                    <div class="form-group">
                        <label for="restrictReason">Reason for Restriction:</label>
                        <textarea id="restrictReason" name="restrictReason" rows="4" placeholder="Enter the reason for restricting the user" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="unrestrictDate">Unrestrict on (Date):</label>
                        <input type="date" id="unrestrictDate" name="unrestrictDate" required>
                    </div>

                    <div class="form-group">
                        <label for="adminNotes">Admin Notes (optional):</label>
                        <textarea id="adminNotes" name="adminNotes" rows="3" placeholder="Add any additional notes (optional)"></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-restrict">Restrict User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    
    <div class="restrict-modal list" id="reportListModal">
        <div class="restrict-container">
            <div class="restrict-container-header">
                <h3>User Report List</h3>
                <span class="restrict-close-btn" id="closeReportListModalBtn">&times;</span>
            </div>
            <div class="restrict-details list">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Report Content</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="reportTableBody">
                        <!-- Dynamic content will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="unrestrict-modal" id="unrestrictModal">
        <div class="unrestrict-container">
            <div class="unrestrict-container-header">
                <h3>Unrestrict User</h3>
                <span class="unrestrict-close-btn" id="closeUnrestrictModalBtn">&times;</span>
            </div>
            <input type="hidden" id="unrestrictUserId" value="">
            
            <!-- Display unrestriction date -->
            <div class="unrestrict-details">
                <p>This user will be automatically unrestricted on: </p>
                <span id="unrestrictionDate"></span>

                <div class="form-group unrestrict">
                    <button type="submit" title="Unrestrict user manually." id="unrestrictUserBtn" class="btn btn-unrestrict">Unrestrict User Now</button>
                </div>
            </div>
        </div>
    </div>
