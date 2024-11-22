<link rel="stylesheet" href="../css/system-booking-request.css">

<div class="booking-request-modal" id="bookingRequestModal">
    <div class="booking-request-container">
        <div class="booking-request-container-header">
            <h3>Booking requests</h3>
            <span class="booking-request-close-btn" id="closeBookingRequestModalBtn">&times;</span>
        </div>
        <div class="booking-request-details-container">
            <div class="booking-request-details">
                <div class="booking-request-details-info">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="booking-request-modal info" id="bookingRequestDetailsModal">
    <div class="booking-request-container info">
        <div class="booking-request-container-header info">
            <h3></h3>
            <span class="booking-request-close-btn info" id="closeBookingRequestDetailsModalBtn">&times;</span>
        </div>
        <div class="booking-request-details-user-info info">
            <div class="booking-request-profile info" id="clientProfile">
                
            </div>
            <div class="booking-request-line-img-container">
                <img class="line-img" src="../images/pulse.png" alt="">
            </div>
            <div class="booking-request-profile info" id="freelancerProfile">
                
            </div>
        </div>
        <div class="booking-request-details-container info">
            <div class="booking-request-details info">
                <div class="booking-request-details-info-container info">
                    <div class="booking-request-booking-details" id="modalBookingDetails">
                        
                    </div>
                    <div class="client-review-container" title="Client's Review.">
                        <div class="client-info">
                            <div class="client-img">
                                <img src="../images/user.jpg" alt="">
                            </div>
                            <div class="client-details">
                                <h3>Ricky Monsales</h3>
                                <i>Client</i>
                            </div>
                        </div>
                        <div class="client-rating">
                            <div class="star">
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                            </div>
                            <p>10/03/24</p>
                        </div>
                        <div class="client-review">
                            <p>Thank you for accepting my offer. You really exceeded my expectations. I highly recommend this freelancer!</p>
                        </div>
                    </div>
                    <div class="booking-request-booking-details-note" id="modalBookingDetailsNote">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="transaction-info-modal info" id="transactionInfoDetailsModal">
    <div class="transaction-info-container info">
        <div class="transaction-info-container-header info">
            <h3>End Transaction</h3>
            <span class="transaction-info-close-btn info" id="closeTransactionInfoDetailsModalBtn">&times;</span>
        </div>
        <div class="transaction-info-details-container info">
            <div class="transaction-info-details info">
                <div class="transaction-info-details-info-container info">
                    <div class="transaction-info-transaction-details" id="modalTransactionDetails">
                        <?php
                        if ($_SESSION['USER']->usertype === 'Client') {
                        ?>
                        <div class="container">
                            <h3 class="title">Rate this Freelancer</h3>
                            <div class="star-widget" id="reviewForm">
                                <div class="stars">
                                    <input type="radio" name="rate" id="rate-5" value="5">
                                    <label for="rate-5" class="fas fa-star"></label>
                                    <input type="radio" name="rate" id="rate-4" value="4">
                                    <label for="rate-4" class="fas fa-star"></label>
                                    <input type="radio" name="rate" id="rate-3" value="3">
                                    <label for="rate-3" class="fas fa-star"></label>
                                    <input type="radio" name="rate" id="rate-2" value="2">
                                    <label for="rate-2" class="fas fa-star"></label>
                                    <input type="radio" name="rate" id="rate-1" value="1">
                                    <label for="rate-1" class="fas fa-star"></label>
                                </div>
                                <form>
                                    <div class="textarea">
                                        <textarea cols="30" placeholder="Describe your experience..."></textarea>
                                    </div>
                                    <!-- Checkbox for recommendation -->
                                    <div class="checkbox-container">
                                        <label>
                                            <input type="checkbox" name="recommendation" value="Recommended">
                                            Do you recommend this freelancer?
                                        </label>
                                    </div>
                                    <div class="btn">
                                        <button type="submit" class="submit-review-button">Submit Review</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                    <?php
                    if ($_SESSION['USER']->usertype === 'Freelancer') {
                    ?>
                    <p class="transaction-info-end-confirmation">Are you sure you want to end this transaction?</p>
                    <?php
                    }
                    ?>
                    <div class="transaction-info-transaction-details-note" id="modalTransactionDetailsNote">
                        
                    </div>
                    
                    <?php
                    if ($_SESSION['USER']->usertype === 'Freelancer') {
                    ?>
                    <div class="transaction-info-end-btn-container">
                        <button id="endTransactionButton" class="end-transaction-button">End Transaction</button>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
