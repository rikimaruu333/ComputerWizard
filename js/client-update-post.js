function attachUpdateModalEvent(card, post) {
    card.find('#openUpdatePostModalBtn').on('click', function() {
        // Populate modal with post data
        $('#updatePostCardImg').attr('src', post.profile || '../images/default.jpg');
        $('#updatePostCardName').text(`${post.firstname} ${post.lastname}`);
        $('#updatePostCardUsertype').text(post.usertype);
        $('#updatePostCardCaption').val(post.caption);
        $('#postCardUpdateJobCategory').val(post.job_category);
        $('#postCardUpdateJob').val(post.job);

        $('#updatePostId').val(post.post_id);
        
        // Display existing images
        const uploadedImagesContainer = $('.update-uploaded-post-image-container').empty();
        post.images.forEach(image => {
            $('<div>').addClass('update-uploaded-image-container')
                .append($('<img>').attr('src', image).addClass('update-uploaded-image'))
                .append($('<i>').addClass('bx bxs-checkbox-minus remove-image').on('click', function() {
                    $(this).parent().remove(); // Remove image from DOM
                    $('<input>').attr({ type: 'hidden', name: 'removeImages[]', value: image }).appendTo('#updatePostForm');
                }))
                .appendTo(uploadedImagesContainer);
        });

        $('#updatePostModal').show();
        $('#closeUpdatePostModalBtn').on('click', function() {
            $('#updatePostModal').hide();
        });
    });
}

$(document).ready(function() {
    let selectedImages = [];
    
    // Trigger file input click
    $("#updateUploadImg").on("click", () => $("#updatePicture").click());

    // Handle new image selection
    $("#updatePicture").on("change", function() {
        Array.from(this.files).forEach(file => {
            if (!selectedImages.some(selected => selected.name === file.name && selected.size === file.size)) {
                const reader = new FileReader();
                reader.onload = e => {
                    $('<div>').addClass('update-uploaded-image-container')
                        .append($('<img>').attr('src', e.target.result).addClass('update-uploaded-image'))
                        .append($('<i>').addClass('bx bxs-checkbox-minus remove-image').on('click', function() {
                            $(this).parent().remove(); // Remove image from DOM
                            selectedImages = selectedImages.filter(selected => selected !== file);
                        }))
                        .appendTo($('.update-uploaded-post-image-container'));
                };
                selectedImages.push(file);
                reader.readAsDataURL(file);
            }
        });
        $("#updatePicture").val(''); // Reset input
    });

    
    // Prevent form submission when clicking the "Select a Job Category" button
    $(".update-post-select-job-category-button").on("click", function(e) {
        e.preventDefault(); // Stop default form submission behavior
    });
    

    // Handle form submission
    $("#updatePostForm").submit(function(e) {
        e.preventDefault();
        
        // Check if job category and job are selected
        const updateJobCategory = $("#postCardUpdateJobCategory").val().trim();
        const updateJob = $("#postCardUpdateJob").val().trim();

        if (!updateJobCategory || !updateJob) {
            toastr.error('Please select both a job category and a job.');
            return; // Prevent form submission if fields are empty
        }

        const formData = new FormData(this);
        selectedImages.forEach(file => formData.append('pictures[]', file));
        
        $.ajax({
            url: 'client-update-post-process.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: () => {
                localStorage.setItem('postUpdated', 'true');
                window.location.reload();
            },
            error: xhr => toastr.error('Error updating post. Please try again.')
        });
    });

    // Show success message if post was updated
    if (localStorage.getItem('postUpdated') === 'true') {
        toastr.info('Post updated successfully!');
        localStorage.removeItem('postUpdated');
    }
});
