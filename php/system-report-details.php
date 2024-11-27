<link rel="stylesheet" href="../css/system-report-details.css">
    
    <div class="report-details-modal" id="reportDetailsModal">
        <div class="report-details-container">
            <div class="report-details-container-header">
                <h3>Report Details</h3>
                <span class="report-details-close-btn" id="closeReportDetailsModalBtn">&times;</span>
            </div>
            <div class="report-details-user-profiles">
                <img class="line-img" src="../images/pulse.png" alt="">
                <ul>
                    <li>
                        <img src="../images/userpic1.jpg" alt="">
                        <div class="progress one">
                            <i class="bx bxs-user-circle"></i>
                        </div>
                        <p class="text">Reporter</p>
                    </li>
                    <li>
                        <img src="../images/userpic.jpg" alt="">
                        <div class="progress two">
                            <i class="bx bxs-user-circle"></i>
                        </div>
                        <p class="text">Violator</p>
                    </li>
                </ul>
            </div>
            <div class="report-details">
                <p><strong><span id="modalReportDetailsContent"></span></strong></p>
                <p><span id="modalReportDetailsReason"></span></p><br>
                 <p>Proof:</p>
                <img id="reportProofImage" style="display:none; max-width: 100%; height: auto;" alt="Report Proof Image">
                <br>
                <p><span id="modalReportDetailsDate"></span></p>
            </div>
        </div>
    </div>
