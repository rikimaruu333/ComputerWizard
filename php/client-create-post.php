
<link rel="stylesheet" href="../css/client-create-post.css">

<div class="create-post-modal" id="createPostModal">
    <div class="uploading-area">
        <div class="uploading-area-header">
            <h3>Uploaded Images</h3>
        </div>
        <div class="uploaded-post-image-container">
            <!-- Uploaded images will appear here -->
        </div>
    </div>
    <div class="create-post-container">
        <div class="create-post-container-header">
            <h3>Create Post</h3>
            <span class="create-post-close-btn" id="closeCreatePostModalBtn">&times;</span>
        </div>
        <form class="create-post-form" id="createPostForm">
            <div class="create-post-content">
                <img src="" alt="" id="postCardImg">
                <div class="details">
                    <h3 id="postCardName"></h3>
                    <p id="postCardUsertype"></p>
                </div>
            </div>
            <textarea placeholder="Enter job post description..." spellcheck="false" name="post_description" id="postCardCaption" required></textarea>
            <div class="create-post-job-category">
                <input type="text" name="post_job_category" id="postCardJobCategory" placeholder="Click the button below to select." title="Selected Job Category" readonly required>
                <input type="text" name="post_job" id="postCardJob" placeholder="Click the button below to select." title="Selected Job" readonly required>
                <button class="create-post-select-job-category-button" id="openJobCategoryModalBtn">Select a job category</button>
            </div>
            <div class="create-post-options">
                <p>Add to your post</p>
                <ul class="list">
                    <li><img id="uploadImg" src="../images/gallery.png" alt="" title="Upload Images"></li>
                    <input type="file" id="picture" name="pictures[]" style="display: none;" multiple>
                </ul>
            </div>
            <button type="submit" class="create-post-button">Post</button>
        </form>
    </div>
</div>


<!-- Job Category Modal -->
<div class="job-category-modal" id="jobCategoryModal">
    <div class="job-category-content">
        <div class="job-category-header">
            <h3>Select Job Category</h3>
            <span class="job-category-close-btn" id="closeJobCategoryModalBtn">&times;</span>
        </div>
        <div class="job-category-container">
            <div class="job-category-list">
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
            <div class="job-list-container" id="jobListContainer">
                <h4 id="selectedCategory">Category: </h4>
                <ul id="jobList">
                    <!-- Jobs will appear here dynamically -->
                </ul>
            </div>
            <div class="custom-job-container" id="customJobContainer" style="display: none;">
                <label for="customJobInput">Custom Job:</label><br>
                <input type="text" id="customJobInput" placeholder="Type your custom job here...">
            </div>
        </div>
        <div class="job-category-button-container">
            <button class="job-category-select-btn" id="confirmJobCategoryBtn">Confirm</button>
        </div>
    </div>
</div>


<script type="module">
// Sample data for services in each category
import CategoryListData from '../js/systemJobCategoryList.js';

// Elements
const jobCategoryModal = document.getElementById('jobCategoryModal');
const closeJobCategoryModalBtn = document.getElementById('closeJobCategoryModalBtn');
const jobListContainer = document.getElementById('jobListContainer');
const jobList = document.getElementById('jobList');
const selectedCategory = document.getElementById('selectedCategory');
const openJobCategoryModalBtn = document.getElementById('openJobCategoryModalBtn');
const customJobContainer = document.getElementById('customJobContainer');
const customJobInput = document.getElementById('customJobInput');
const confirmJobCategoryBtn = document.getElementById('confirmJobCategoryBtn');
const jobCategoryInput = document.getElementById('postCardJobCategory');
const jobInput = document.getElementById('postCardJob');

// Show modal when button is clicked
openJobCategoryModalBtn.addEventListener('click', () => {
    jobCategoryModal.style.display = 'block';
});

// Close modal
closeJobCategoryModalBtn.addEventListener('click', () => {
    jobCategoryModal.style.display = 'none';
});

// Handle category click
const categoryElements = document.querySelectorAll('.job-category-list ul li');
categoryElements.forEach((category) => {
    category.addEventListener('click', () => {
        const categoryName = category.getAttribute('data-category');

        // Highlight the selected category
        categoryElements.forEach((categoryItem) => categoryItem.classList.remove('selected'));
        category.classList.add('selected');

        selectedCategory.textContent = `Category: ${category.textContent}`;
        jobList.innerHTML = '';
        customJobContainer.style.display = 'none';
        customJobInput.value = '';

        if (categoryName === 'other') {
            jobListContainer.style.display = 'none';
            customJobContainer.style.display = 'block';
        } else {
            CategoryListData[categoryName].forEach((job) => {
                const li = document.createElement('li');
                li.textContent = job;
                li.addEventListener('click', () => {
                    // Highlight the selected job
                    document.querySelectorAll('#jobList li').forEach((jobItem) => jobItem.classList.remove('selected'));
                    li.classList.add('selected');

                    if (job === 'Other') {
                        customJobContainer.style.display = 'block';
                    } else {
                        customJobContainer.style.display = 'none';
                        customJobInput.value = '';
                    }
                });
                jobList.appendChild(li);
            });

            jobListContainer.style.display = 'block';
        }
    });
});

// Confirm selected job
confirmJobCategoryBtn.addEventListener('click', () => {
    const selectedJob = document.querySelector('#jobList li.selected');
    let jobText = '';
    const selectedCategoryText = selectedCategory.textContent.replace('Category: ', '');

    if (selectedJob) {
        jobText = selectedJob.textContent;
        if (jobText === 'Other') {
            if (customJobInput.value.trim() === '') {
                Swal.fire({
                    title: 'Input Required',
                    text: 'Please type your custom job.',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
                return;
            }
            jobText = customJobInput.value.trim();
        }
    } else if (customJobContainer.style.display === 'block' && customJobInput.value.trim() !== '') {
        jobText = customJobInput.value.trim();
    } else {
        Swal.fire({
            title: 'No Job Selected',
            text: 'Please select or input a job before confirming.',
            icon: 'warning',
            confirmButtonText: 'Confirm'
        });
        return;
    }

    // Populate hidden input fields
    jobCategoryInput.value = selectedCategoryText;
    jobInput.value = jobText;

    Swal.fire({
        title: 'Job Selected!',
        text: `Category: ${selectedCategoryText}, Job: ${jobText}`,
        icon: 'success',
        confirmButtonText: 'Done',
        customClass: {
            popup: 'small-swal-popup',
            icon: 'small-swal-icon',
            confirmButton: 'custom-confirm-question-button',
            cancelButton: 'custom-cancel-button'
        }
    }).then(() => {
        jobCategoryModal.style.display = 'none';
    });
});

// Close modal when clicking outside the container
window.addEventListener('click', (event) => {
    if (event.target === jobCategoryModal) {
        jobCategoryModal.style.display = 'none';
    }
});

</script>