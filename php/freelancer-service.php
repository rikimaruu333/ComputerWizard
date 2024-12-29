
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
                        <input type="hidden" id="serviceCategoryName" name="serviceCategoryName">
                        <div class="form-group">
                            <label for="serviceName">Service Name</label>
                            <div class="service-input-container">
                                <input type="text" id="serviceName" name="serviceName" placeholder="Click icon to choose a service..." readonly>
                                <i class="bx bxs-briefcase" id="openServiceCategoryModalBtn" title="Click to choose a service"></i>
                            </div>

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
                        <input type="hidden" id="updateServiceCategoryName" name="serviceCategoryName">
                        <div class="form-group">
                            <label for="updateServiceName">Service Name</label>
                            <div class="service-input-container">
                                <input type="text" id="updateServiceName" name="serviceName" placeholder="Click icon to choose a service..." readonly>
                                <i class="bx bxs-briefcase" id="openUpdateServiceCategoryModalBtn" title="Click to choose a service"></i>
                            </div>
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














        <!-- Service Category Modal -->
<div class="service-category-modal" id="serviceCategoryModal">
    <div class="service-category-content">
        <div class="service-category-header">
            <h3>Select Service Category</h3>
            <span class="service-category-close-btn" id="closeServiceCategoryModalBtn">&times;</span>
        </div>
        <div class="service-category-container">
            <div class="service-category-list">
                <ul>
                    <li data-category="ships">Ships</li>
                    <li data-category="vehicles">Vehicles</li>
                    <li data-category="technical">Technical</li>
                    <li data-category="veterinarian">Veterinarian</li>
                    <li data-category="editor">Editor</li>
                    <li data-category="programmer">Programmer</li>
                    <li data-category="construction">Construction</li>
                    <li data-category="healthcare">Healthcare</li>
                    <li data-category="education">Education</li>
                    <li data-category="arts">Arts</li>
                    <li data-category="hospitality">Hospitality</li>
                    <li data-category="sales">Sales</li>
                    <li data-category="manufacturing">Manufacturing</li>
                    <li data-category="other">Other</li>
                </ul>
            </div>
            <div class="service-list-container" id="serviceListContainer">
                <h4 id="selectedServiceCategory">Category: </h4>
                <ul id="serviceList">
                    <!-- Services will appear here dynamically -->
                </ul>
            </div>
            <div class="custom-service-container" id="customServiceContainer" style="display: none;">
                <label for="customServiceInput">Custom Service:</label><br>
                <input type="text" id="customServiceInput" placeholder="Type your custom service here...">
            </div>
        </div>
        <div class="service-category-button-container">
            <button class="service-category-select-btn" id="confirmServiceCategoryBtn">Confirm</button>
        </div>
    </div>
</div>

<script type="module">
// Sample data for services in each category
import CategoryListData from '../js/systemJobCategoryList.js';

// Elements
const serviceCategoryModal = document.getElementById('serviceCategoryModal');
const closeServiceCategoryModalBtn = document.getElementById('closeServiceCategoryModalBtn');
const serviceListContainer = document.getElementById('serviceListContainer');
const serviceList = document.getElementById('serviceList');
const selectedServiceCategory = document.getElementById('selectedServiceCategory');
const openServiceCategoryModalBtn = document.getElementById('openServiceCategoryModalBtn');
const customServiceContainer = document.getElementById('customServiceContainer');
const customServiceInput = document.getElementById('customServiceInput');
const confirmServiceCategoryBtn = document.getElementById('confirmServiceCategoryBtn');
const serviceCategoryInput = document.getElementById('serviceCategoryName');
const serviceInput = document.getElementById('serviceName');

// Show modal when button is clicked
openServiceCategoryModalBtn.addEventListener('click', () => {
    serviceCategoryModal.style.display = 'block';
});

// Close modal
closeServiceCategoryModalBtn.addEventListener('click', () => {
    serviceCategoryModal.style.display = 'none';
});

// Handle category click
const categoryElements = document.querySelectorAll('.service-category-list ul li');
categoryElements.forEach((category) => {
    category.addEventListener('click', () => {
        const categoryName = category.getAttribute('data-category');

        // Highlight the selected category
        categoryElements.forEach((categoryItem) => categoryItem.classList.remove('selected'));
        category.classList.add('selected');

        selectedServiceCategory.textContent = `Category: ${category.textContent}`;
        serviceList.innerHTML = '';
        customServiceContainer.style.display = 'none';
        customServiceInput.value = '';

        if (categoryName === 'other') {
            serviceListContainer.style.display = 'none';
            customServiceContainer.style.display = 'block';
        } else {
            CategoryListData[categoryName].forEach((service) => {
                const li = document.createElement('li');
                li.textContent = service;
                li.addEventListener('click', () => {
                    // Highlight the selected service
                    document.querySelectorAll('#serviceList li').forEach((serviceItem) => serviceItem.classList.remove('selected'));
                    li.classList.add('selected');

                    if (service === 'Other') {
                        customServiceContainer.style.display = 'block';
                    } else {
                        customServiceContainer.style.display = 'none';
                        customServiceInput.value = '';
                    }
                });
                serviceList.appendChild(li);
            });

            serviceListContainer.style.display = 'block';
        }
    });
});

// Confirm selected service
confirmServiceCategoryBtn.addEventListener('click', () => {
    const selectedService = document.querySelector('#serviceList li.selected');
    let serviceText = '';
    const selectedCategoryText = selectedServiceCategory.textContent.replace('Category: ', '');

    if (selectedService) {
        serviceText = selectedService.textContent;
        if (serviceText === 'Other') {
            if (customServiceInput.value.trim() === '') {
                Swal.fire({
                    title: 'Input Required',
                    text: 'Please type your custom service.',
                    icon: 'warning',
                    confirmButtonText: 'Ok',
                    customClass: {
                        popup: 'small-swal-popup',
                        icon: 'small-swal-icon',
                        confirmButton: 'custom-confirm-button',
                        cancelButton: 'custom-cancel-button'
                    }
                });
                return;
            }
            serviceText = customServiceInput.value.trim();
        }
    } else if (customServiceContainer.style.display === 'block' && customServiceInput.value.trim() !== '') {
        serviceText = customServiceInput.value.trim();
    } else {
        Swal.fire({
            title: 'No Service Selected',
            text: 'Please select or input a service before confirming.',
            icon: 'warning',
            confirmButtonText: 'Confirm',
            customClass: {
                popup: 'small-swal-popup',
                icon: 'small-swal-icon',
                confirmButton: 'custom-confirm-button',
                cancelButton: 'custom-cancel-button'
            }
        });
        return;
    }

    // Populate hidden input fields
    serviceCategoryInput.value = selectedCategoryText;
    serviceInput.value = serviceText;

    Swal.fire({
        title: 'Service Selected!',
        text: `Category: ${selectedCategoryText}, Service: ${serviceText}`,
        icon: 'success',
        confirmButtonText: 'Done',
        customClass: {
            popup: 'small-swal-popup',
            icon: 'small-swal-icon',
            confirmButton: 'custom-confirm-question-button',
            cancelButton: 'custom-cancel-button'
        }
    }).then(() => {
        serviceCategoryModal.style.display = 'none';
    });
});

// Close modal when clicking outside the container
window.addEventListener('click', (event) => {
    if (event.target === serviceCategoryModal) {
        serviceCategoryModal.style.display = 'none';
    }
});
// Elements specific to the update modal
const updateServiceCategoryInput = document.getElementById('updateServiceCategoryName');
const updateServiceInput = document.getElementById('updateServiceName');
const openUpdateServiceCategoryModalBtn = document.getElementById('openUpdateServiceCategoryModalBtn');

// Show the service category modal when the selection icon in the update modal is clicked
openUpdateServiceCategoryModalBtn.addEventListener('click', () => {
    serviceCategoryModal.style.display = 'block';
});

// Confirm selected service and populate fields in the update modal
confirmServiceCategoryBtn.addEventListener('click', () => {
    const selectedService = document.querySelector('#serviceList li.selected');
    let serviceText = '';
    const selectedCategoryText = selectedServiceCategory.textContent.replace('Category: ', '');

    if (selectedService) {
        serviceText = selectedService.textContent;
        if (serviceText === 'Other') {
            if (customServiceInput.value.trim() === '') {
                Swal.fire({
                    title: 'Input Required',
                    text: 'Please type your custom service.',
                    icon: 'warning',
                    confirmButtonText: 'Ok',
                    customClass: {
                        popup: 'small-swal-popup',
                        icon: 'small-swal-icon',
                        confirmButton: 'custom-confirm-button',
                        cancelButton: 'custom-cancel-button'
                    }
                });
                return;
            }
            serviceText = customServiceInput.value.trim();
        }
    } else if (customServiceContainer.style.display === 'block' && customServiceInput.value.trim() !== '') {
        serviceText = customServiceInput.value.trim();
    } else {
        Swal.fire({
            title: 'No Service Selected',
            text: 'Please select or input a service before confirming.',
            icon: 'warning',
            confirmButtonText: 'Confirm',
            customClass: {
                popup: 'small-swal-popup',
                icon: 'small-swal-icon',
                confirmButton: 'custom-confirm-button',
                cancelButton: 'custom-cancel-button'
            }
        });
        return;
    }

    // Populate hidden input fields
    updateServiceCategoryInput.value = selectedCategoryText;
    updateServiceInput.value = serviceText;

    Swal.fire({
        title: 'Service Selected!',
        text: `Category: ${selectedCategoryText}, Service: ${serviceText}`,
        icon: 'success',
        confirmButtonText: 'Done',
        customClass: {
            popup: 'small-swal-popup',
            icon: 'small-swal-icon',
            confirmButton: 'custom-confirm-question-button',
            cancelButton: 'custom-cancel-button'
        }
    }).then(() => {
        serviceCategoryModal.style.display = 'none';
    });
});

</script>