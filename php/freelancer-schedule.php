<link rel="stylesheet" href="../css/freelancer-schedule.css">

<div id="scheduleModal" class="schedule-modal">
    <div class="schedule-modal-content">
        <div class="schedule-modal-header">
            <h3>Schedule List</h3>
            <span class="close-schedule-modal" id="close-schedule-modal">&times;</span>
        </div>
        <div class="schedule-table-container">
            <table>
                <thead>
                    <tr>
                        <th class="day-th">Day</th>
                        <th class="time-in-th">Time In</th>
                        <th class="time-out-th">Time Out</th>
                        <th class="config-th">Config</th>
                    </tr>
                </thead>
                <tbody id="scheduleTableBody">

            </tbody>
            </table>
        </div>
        <div class="schedule-modal-footer">
            <button class="add-schedule-btn" id="openAddScheduleModalBtn">Add Schedule</button>
        </div>
    </div>
</div>

<div id="addScheduleModal" class="schedule-modal">
    <div class="add-schedule-modal-content">
        <div class="add-schedule-modal-header">
            <h3>Add New Schedule</h3>
            <span class="close-add-schedule-modal" id="close-add-schedule-modal">&times;</span>
        </div>
        <div class="add-schedule-form-container">
            <form>
                <div class="form-group">
                    <label for="scheduleDay">Day</label>
                    <select class="inputting" name="scheduleDay" id="scheduleDay" required>
                        <option value="">Select Day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="scheduleTimeIn">Time In</label>
                    <input type="time" id="scheduleTimeIn" name="scheduleTimeIn" placeholder="Enter time in here">
                </div>
                <div class="form-group">
                    <label for="scheduleTimeOut">Time Out</label>
                    <input type="time" id="scheduleTimeOut" name="scheduleTimeOut" placeholder="Enter time out here">
                </div>
            </form>
        </div>
        <div class="add-schedule-modal-footer">
            <button class="add-schedule-btn" id="addSchedule">Save</button>
        </div>
    </div>
</div>

<div id="updateScheduleModal" class="schedule-modal">
    <div class="update-schedule-modal-content">
        <div class="update-schedule-modal-header">
            <h3>Update Schedule</h3>
            <span class="close-update-schedule-modal" id="close-update-schedule-modal">&times;</span>
        </div>
        <div class="update-schedule-form-container">
            <form id="updateScheduleForm">
                <div class="form-group">
                    <label for="updateScheduleDay">Day</label>
                    <select class="inputting" name="scheduleDay" id="updateScheduleDay" required>
                        <option value="">Select Day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="updateScheduleTimeIn">Time In</label>
                    <input type="time" id="updateScheduleTimeIn" name="scheduleTimeIn" placeholder="Enter time in here">
                </div>
                <div class="form-group">
                    <label for="updateScheduleTimeOut">Time Out</label>
                    <input type="time" id="updateScheduleTimeOut" name="scheduleTimeOut" placeholder="Enter time out here">
                </div>
                <input type="hidden" id="updateScheduleId" name="scheduleId">
            </form>
        </div>
        <div class="update-schedule-modal-footer">
            <button class="update-schedule-btn" id="updateSchedule">Save Changes</button>
        </div>
    </div>
</div>

<div id="deleteScheduleModal" class="delete-schedule-modal">
    <div class="delete-schedule-modal-content">
        <div class="delete-schedule-modal-header">
            <h3><i class="fas fa-exclamation-triangle" style="color: #f39c12;"></i> Warning</h3>
            <span class="delete-schedule-modal-close-btn" id="close-delete-schedule-modal">&times;</span>
        </div>
        <div class="delete-schedule-modal-body">
            <p>Are you sure you want to delete this schedule?</p>
        </div>
        <div class="delete-schedule-modal-footer">
            <button id="confirmDeleteSchedule" class="btn btn-danger">Yes</button>
        </div>
    </div>
</div>
