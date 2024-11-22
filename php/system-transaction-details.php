<link rel="stylesheet" href="../css/system-transaction-details.css">

<div class="transaction-details-modal" id="endedTransactionListModal">
    <div class="transaction-details-container-info info">
        <div class="transaction-details-container-header info">
            <h3>Ended Transactions</h3>
            <span class="transaction-details-close-btn info" id="closeEndedTransactionListModalBtn">&times;</span>
        </div>
        <div class="transaction-details-details-container">
            <div class="transaction-details-details">
                <div class="transaction-details-details-info">
                    
                    <div class="transaction-details-user-info info">
                        <div class="transaction-profile info" id="clientProfile">
                            
                        </div>
                        <div class="transaction-line-img-container">
                            <img class="line-img" src="../images/pulse.png" alt="">
                        </div>
                        <div class="transaction-profile info" id="freelancerProfile">
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>





<div class="transaction-details-modal info" id="transactionRequestDetailsModal">
    <div class="transaction-details-container-info info">
        <div class="transaction-details-container-header info">
            <h3>Transaction Details</h3>
            <span class="transaction-details-close-btn info" id="closeTransactionRequestDetailsModalBtn">&times;</span>
        </div>
        <div class="transaction-details-details-user-info info">
            <div class="transaction-details-profile info" id="clientProfile">
                <img src="../images/userpic.jpg" alt="User Profile">
                <div class="details">
                    <h3>Leonard Balabat</h3>
                    <p>Client</p>
                </div>
            </div>
            <div class="transaction-details-line-img-container">
                <img class="line-img" src="../images/pulse.png" alt="">
            </div>
            <div class="transaction-details-profile info" id="freelancerProfile">
                <img src="../images/userpic1.jpg" alt="User Profile">
                <div class="details">
                    <h3>Don Quixote</h3>
                    <p>Freelancer</p>
                </div>
            </div>
        </div>
        <div class="transaction-details-details-container info">
            <div class="transaction-details-details info">
                <div class="transaction-details-details-info-container info">
                    <div class="transaction-details-transaction-details" id="modalTransactionDetails">
                        <p> <i class="bx bxs-x-circle"></i> <span>Approved by freelancer.</span></p>
                        <p> <i class="bx bxs-check-circle"></i> <span>Transaction started on ${booking.created_at}.</span></p>
                        <p> <i class="bx bxs-x-circle"></i> <span>Job completed by freelancer.</span></p>
                        <p> <i class="bx bxs-check-circle"></i> <span>Review and rating submitted by client.</span></p>
                        <p> <i class="bx bxs-check-circle"></i> <span>Payment received by freelancer.</span></p>
                    </div>
                    <div class="transaction-details-transaction-details-note" id="modalTransactionServiceDetails">
                        <p> <i class="bx bxs-calendar-check"></i> <span>Booking request submitted on ${booking.booking_date}.</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>