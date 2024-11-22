<script>
    const isClient = "<?php echo $_SESSION['USER']->usertype; ?>" === "Client"; 
</script>

<link rel="stylesheet" href="../css/system-get-freelancer-service-and-schedule-list.css">
<div id="viewProfileServiceModal" class="service-modal">
        <div class="service-modal-content">
            <div class="service-modal-header">
                <h3>Service List</h3>
                <span class="close-service-modal" id="view-profile-close-service-modal">&times;</span>
            </div>
            <div class="service-table-container">
                <table>
                    <thead>
                        <tr>
                            <th class="service-th">Service</th>
                            <th class="rate-th">Rate</th>
                            <?php
                            if($_SESSION['USER']->usertype == "Client"){
                            ?>
                                <th class="config-th">Action</th>
                            <?php
                            }
                            ?>
                        </tr>   
                    </thead>
                    <tbody id="viewProfileServiceTableBody">

                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div id="viewProfileScheduleModal" class="schedule-modal">
        <div class="schedule-modal-content">
            <div class="schedule-modal-header">
                <h3>Schedule List</h3>
                <span class="close-schedule-modal" id="view-profile-close-schedule-modal">&times;</span>
            </div>
            <div class="schedule-table-container">
                <table>
                    <thead>
                        <tr>
                            <th class="day-th">Day</th>
                            <th class="time-in-th">Time In</th>
                            <th class="time-out-th">Time Out</th>
                        </tr>
                    </thead>
                    <tbody id="viewProfileScheduleTableBody">

                </tbody>
                </table>
            </div>
        </div>
    </div>