<link rel="stylesheet" href="../css/system-booking-modal.css">

<div class="booking-modal" id="bookingModal">
    <div class="booking-container">
        <div class="booking-container-header">
            <h3>Book User</h3>
            <span class="booking-close-btn" id="closeBookingModalBtn">&times;</span>
        </div>
        <input type="hidden" id="bookingUserId" value="">
        <input type="hidden" id="bookingServiceId" value="">
        <div class="booking-details-container">
            <div class="booking-details-info">
                <div class="booking-details-user-info">
                    <img src="" id="bookingUserProfile" alt="User Picture">
                    <div class="details">
                        <h3 id="bookingUserName">Don Quixote</h3>
                        <p id="bookingUserType">Freelancer</p>
                    </div>
                </div>
            </div>
            <div class="booking-details">
                <div class="booking-info">
                    <p>Service: <span id="bookingService"></span></p>
                    <p>Rate: ₱<span id="bookingRate"></span></p>
                </div>
                <div class="job-type-container">
                    <p>Choose Job Type:</p>
                    <div class="job-type-options">
                        <label>
                            <input type="radio" name="jobType" value="Onsite"> Onsite
                        </label>
                        <label>
                            <input type="radio" name="jobType" value="Online"> Online
                        </label>
                    </div>
                </div>
                <div class="payment-container">
                    <p>Choose Payment:</p>
                    <div class="job-type-options">
                        <label>
                            <input type="radio" name="payment" value="Onsite"> Onsite
                        </label>
                        <label>
                            <input type="radio" name="payment" value="Online"> Online
                        </label>
                    </div>
                </div>

                <div class="booking-details-btn">
                    <button type="submit" title="Book User" id="bookingUserBtn" class="btn btn-booking">Book User</button>
                </div>
            </div>
        </div>

    </div>
</div>
