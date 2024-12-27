document.addEventListener('DOMContentLoaded', function() {

    const createPostModal = document.getElementById('createPostModal');
    const openModalBtn = document.getElementById('createPostBtn');
    const closeModalBtn = document.getElementById('closeCreatePostModalBtn');

    openModalBtn.addEventListener('click', () => {
        createPostModal.style.display = 'block';
    });

    closeModalBtn.addEventListener('click', () => {
        createPostModal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === createPostModal) {
            createPostModal.style.display = 'none';
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    function fetchClientData() {
    $.ajax({
        url: 'system-get-user-data.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                updateClientInfo(response.data);
            } else {
                alert(response.message); 
            }
        },
        error: function () {
            alert('Error fetching Freelancer data');
        }
    });
}
fetchClientData();
function updateClientInfo(data) {
    document.getElementById("postCardImg").src = data.profile ? data.profile : '../images/user.jpg';
    document.getElementById("postCardName").innerText = data.firstname + ' ' + data.lastname;
    // document.getElementById("postCardCaption").placeholder = 'Whats on your mind, ' + data.firstname + ' ' + data.lastname;
    document.getElementById("postCardUsertype").innerText = data.usertype;
}
});



$(document).ready(function() {
    let selectedImages = []; // Array to keep track of selected files and their identifiers

    // Trigger file input click on image icon click
    $("#uploadImg").on("click", function() {
        $("#picture").click();
    });

    // Handle file input changes
    $("#picture").on("change", function() {
        const files = this.files;
        const uploadedImageContainer = $('.uploaded-post-image-container');

        // Append new images
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            // Check if the file is already in the selectedImages array
            if (selectedImages.some(selectedFile => selectedFile.name === file.name && selectedFile.size === file.size)) {
                continue; // Skip adding if the file is already selected
            }

            reader.onload = function(e) {
                const imgContainer = $('<div>').addClass('uploaded-image-container');
                const img = $('<img>').attr('src', e.target.result).addClass('uploaded-image');
                const removeBtn = $('<i>').addClass('bx bxs-checkbox-minus remove-image');

                // Create a unique identifier for this file
                const fileIdentifier = {
                    file: file,
                    name: file.name,
                    type: file.type,
                    size: file.size
                };

                // Add the file to the selectedImages array
                selectedImages.push(fileIdentifier);

                // Remove image functionality
                removeBtn.on('click', function() {
                    imgContainer.remove(); // Remove image from DOM

                    // Remove the file from the selectedImages array
                    selectedImages = selectedImages.filter(selectedFile => selectedFile.file !== file); // Filter out the removed file
                });

                imgContainer.append(img).append(removeBtn);
                uploadedImageContainer.append(imgContainer);
            };

            reader.readAsDataURL(file);
        }

        $("#picture").val('');
    });

    // Prevent form submission when clicking the "Select a Job Category" button
    $(".create-post-select-job-category-button").on("click", function(e) {
        e.preventDefault(); // Stop default form submission behavior
    });
    
    // Form submission
    $("#createPostForm").submit(function(e) {
        e.preventDefault();

        // Check if job category and job are selected
        const jobCategory = $("#postCardJobCategory").val().trim();
        const job = $("#postCardJob").val().trim();

        if (!jobCategory || !job) {
            toastr.error('Please select both a job category and a job.');
            return; // Prevent form submission if fields are empty
        }
        
        var formData = new FormData(this); 

        // Append only the selected images to form data
        selectedImages.forEach((fileIdentifier) => {
            formData.append('pictures[]', fileIdentifier.file, fileIdentifier.name);
        });

        $.ajax({
            url: 'client-create-post-process.php',
            type: 'POST',
            data: formData,
            processData: false,  // Don't process the files
            contentType: false,  // Don't set the content-type header
            success: function(response) {
                localStorage.setItem('postCreated', 'true');
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                toastr.error('Error creating post. Please try again.');
            }
        });
    });

    // Check if post created successfully
    if (localStorage.getItem('postCreated') === 'true') {
        toastr.success('Post created successfully!');
        localStorage.removeItem('postCreated');
    }
});
