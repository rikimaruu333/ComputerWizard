
    <link rel="stylesheet" href="../css/freelancer-service.css">
        

        <div id="serviceModal" class="service-modal">
            <div class="service-modal-content">
                <div class="service-modal-header">
                    <h3>Service List</h3>
                    <span class="close-service-modal" id="close-service-modal">&times;</span>
                </div>
                <div class="service-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th class="service-th">Service</th>
                                <th class="rate-th">Rate</th>
                                <th class="config-th">Config</th>
                            </tr>   
                        </thead>
                        <tbody id="serviceTableBody">

                        </tbody>
                    </table>
                </div>
                <div class="service-modal-footer">
                    <button class="add-service-btn" id="openAddServiceModalBtn">Add Service</button>
                </div>
            </div>
        </div>

        <div id="addServiceModal" class="service-modal">
            <div class="add-service-modal-content">
                <div class="add-service-modal-header">
                    <h3>Add New Service</h3>
                    <span class="close-add-service-modal" id="close-add-service-modal">&times;</span>
                </div>
                <div class="add-service-form-container">
                    <form>
                        <div class="form-group">
                            <label for="serviceName">Service Name</label>
                            <input type="text" id="serviceName" name="serviceName" placeholder="Enter service here">
                        </div>
                        <div class="form-group">
                            <label for="serviceRate">Rate</label>
                            <input type="text" id="serviceRate" name="serviceRate" placeholder="Enter rate here">
                        </div>
                    </form>
                </div>
                <div class="add-service-modal-footer">
                    <button class="add-service-btn" id="addService">Save</button>
                </div>
            </div>
        </div>

        <div id="updateServiceModal" class="service-modal">
            <div class="update-service-modal-content">
                <div class="update-service-modal-header">
                    <h3>Update Service</h3>
                    <span class="close-update-service-modal" id="close-update-service-modal">&times;</span>
                </div>
                <div class="update-service-form-container">
                    <form id="updateServiceForm">
                        <div class="form-group">
                            <label for="updateServiceName">Service Name</label>
                            <input type="text" id="updateServiceName" name="serviceName" placeholder="Enter service name here">
                        </div>
                        <div class="form-group">
                            <label for="updateServiceRate">Rate</label>
                            <input type="text" id="updateServiceRate" name="serviceRate" placeholder="Enter rate here">
                        </div>
                        <input type="hidden" id="updateServiceId" name="serviceId">
                    </form>
                </div>
                <div class="update-service-modal-footer">
                    <button class="update-service-btn" id="updateService">Save Changes</button>
                </div>
            </div>
        </div>

        <div id="deleteServiceModal" class="delete-service-modal">
            <div class="delete-service-modal-content">
                <div class="delete-service-modal-header">
                    <h3><i class="fas fa-exclamation-triangle" style="color: #f39c12;"></i> Warning</h3>
                    <span class="delete-service-modal-close-btn" id="close-delete-service-modal">&times;</span>
                </div>
                <div class="delete-service-modal-body">
                    <p>Are you sure you want to delete this service?</p>
                </div>
                <div class="delete-service-modal-footer">
                    <button id="confirmDeleteService" class="btn btn-danger">Yes</button>
                </div>
            </div>
        </div>