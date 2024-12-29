<link rel="stylesheet" href="../css/client-update-post.css">

<div class="update-post-modal" id="updatePostModal">
    <div class="update-uploading-area">
        <div class="update-uploading-area-header">
            <h3>Uploaded Images</h3>
        </div>
        <div class="update-uploaded-post-image-container">
        </div>
    </div>
    <div class="update-post-container">
        <div class="update-post-container-header">
            <h3>Update Post</h3>
            <span class="update-post-close-btn" id="closeUpdatePostModalBtn">&times;</span>
        </div>
        <form class="update-post-form" id="updatePostForm">
            <input type="hidden" name="post_id" id="updatePostId">
            <div class="update-post-content">
                <img src="" alt="" id="updatePostCardImg">
                <div class="details">
                    <h3 id="updatePostCardName"></h3>
                    <p id="updatePostCardUsertype"></p>
                </div>
            </div>
            <textarea placeholder="Enter job post description..." spellcheck="false" name="post_description" id="updatePostCardCaption" required></textarea>
            <div class="update-post-job-category">
                <input type="text" name="post_job_category" id="postCardUpdateJobCategory" placeholder="Click the button below to select." title="Selected Job Category" readonly required>
                <input type="text" name="post_job" id="postCardUpdateJob" placeholder="Click the button below to select." title="Selected Job" readonly required>
                <button class="update-post-select-job-category-button" id="openUpdateJobCategoryModalBtn">Select a job category</button>
            </div>
            <div class="update-post-options">
                <p>Add to your post</p>
                <ul class="list">
                    <li><img id="updateUploadImg" src="../images/gallery.png" alt="" title="Upload Images"></li>
                    <input type="file" id="updatePicture" name="pictures[]" style="display: none;" multiple>
                </ul>
            </div>
            <button type="submit" class="update-post-button">Update Post</button>
        </form>
    </div>
</div>



<!-- Job Category Modal -->
<div class="update-job-category-modal" id="updateJobCategoryModal">
    <div class="update-job-category-content">
        <div class="update-job-category-header">
            <h3>Select Job Category</h3>
            <span class="update-job-category-close-btn" id="closeUpdateJobCategoryModalBtn">&times;</span>
        </div>
        <div class="update-job-category-container">
            <div class="update-job-category-list">
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
            <div class="update-job-list-container" id="updateJobListContainer">
                <h4 id="updateSelectedCategory">Category: </h4>
                <ul id="updateJobList">
                    <!-- update-Jobs will appear here dynamically -->
                </ul>
            </div>
            <div class="custom-update-job-container" id="customUpdateJobContainer" style="display: none;">
                <label for="customUpdateJobInput">Custom Job:</label><br>
                <input type="text" id="customUpdateJobInput" placeholder="Type your custom job here...">
            </div>
        </div>
        <div class="update-job-category-button-container">
            <button class="update-job-category-select-btn" id="confirmUpdateJobCategoryBtn">Confirm</button>
        </div>
    </div>
</div>






<script type="module">
// Sample data for jobs in each category
import CategoryListData from '../js/systemJobCategoryList.js';

// Elements
const updateJobCategoryModal = document.getElementById('updateJobCategoryModal');
const closeUpdateJobCategoryModalBtn = document.getElementById('closeUpdateJobCategoryModalBtn');
const updateJobListContainer = document.getElementById('updateJobListContainer');
const updateJobList = document.getElementById('updateJobList');
const updateSelectedCategory = document.getElementById('updateSelectedCategory');
const openUpdateJobCategoryModalBtn = document.getElementById('openUpdateJobCategoryModalBtn');
const customUpdateJobContainer = document.getElementById('customUpdateJobContainer');
const customUpdateJobInput = document.getElementById('customUpdateJobInput');
const confirmUpdateJobCategoryBtn = document.getElementById('confirmUpdateJobCategoryBtn');
const updateJobCategoryInput = document.getElementById('postCardUpdateJobCategory');
const updateJobInput = document.getElementById('postCardUpdateJob');

// Show modal when button is clicked
openUpdateJobCategoryModalBtn.addEventListener('click', () => {
    updateJobCategoryModal.style.display = 'block';
});

// Close modal
closeUpdateJobCategoryModalBtn.addEventListener('click', () => {
    updateJobCategoryModal.style.display = 'none';
});

// Handle category click
const updateCategoryElements = document.querySelectorAll('.update-job-category-list ul li');
updateCategoryElements.forEach((category) => {
    category.addEventListener('click', () => {
        const categoryName = category.getAttribute('data-category');

        // Highlight the selected category
        updateCategoryElements.forEach((categoryItem) => categoryItem.classList.remove('selected'));
        category.classList.add('selected');

        updateSelectedCategory.textContent = `Category: ${category.textContent}`;
        updateJobList.innerHTML = '';
        customUpdateJobContainer.style.display = 'none';
        customUpdateJobInput.value = '';

        if (categoryName === 'other') {
            updateJobListContainer.style.display = 'none';
            customUpdateJobContainer.style.display = 'block';
        } else {
            CategoryListData[categoryName].forEach((job) => {
                const li = document.createElement('li');
                li.textContent = job;
                li.addEventListener('click', () => {
                    // Highlight the selected job
                    document.querySelectorAll('#updateJobList li').forEach((jobItem) => jobItem.classList.remove('selected'));
                    li.classList.add('selected');

                    if (job === 'Other') {
                        customUpdateJobContainer.style.display = 'block';
                    } else {
                        customUpdateJobContainer.style.display = 'none';
                        customUpdateJobInput.value = '';
                    }
                });
                updateJobList.appendChild(li);
            });

            updateJobListContainer.style.display = 'block';
        }
    });
});

// Confirm selected job
confirmUpdateJobCategoryBtn.addEventListener('click', () => {
    const selectedJob = document.querySelector('#updateJobList li.selected');
    let jobText = '';
    const updateSelectedCategoryText = updateSelectedCategory.textContent.replace('Category: ', '');

    if (selectedJob) {
        jobText = selectedJob.textContent;
        if (jobText === 'Other') {
            if (customUpdateJobInput.value.trim() === '') {
                Swal.fire({
                    title: 'Input Required',
                    text: 'Please type your custom job.',
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
            jobText = customUpdateJobInput.value.trim();
        }
    } else if (customUpdateJobContainer.style.display === 'block' && customUpdateJobInput.value.trim() !== '') {
        jobText = customUpdateJobInput.value.trim();
    } else {
        Swal.fire({
            title: 'No Job Selected',
            text: 'Please select or input a job before confirming.',
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
    updateJobCategoryInput.value = updateSelectedCategoryText;
    updateJobInput.value = jobText;

    Swal.fire({
        title: 'Job Selected!',
        text: `Category: ${updateSelectedCategoryText}, Job: ${jobText}`,
        icon: 'success',
        confirmButtonText: 'Done',
        customClass: {
            popup: 'small-swal-popup',
            icon: 'small-swal-icon',
            confirmButton: 'custom-confirm-question-button',
            cancelButton: 'custom-cancel-button'
        }
    }).then(() => {
        updateJobCategoryModal.style.display = 'none';
    });
});

// Close modal when clicking outside the container
window.addEventListener('click', (event) => {
    if (event.target === updateJobCategoryModal) {
        updateJobCategoryModal.style.display = 'none';
    }
});

</script>