
$(document).ready(function() {
    // Trigger form submit when the send icon is clicked
    $(document).on('click', '#sendCommentBtn', function() {
        // Find the form associated with this button
        let form = $(this).closest('form');
        form.submit();
    });

    // Handle form submission with AJAX
    $(document).on('submit', '.commentForm', function(e) {
        e.preventDefault(); // Prevent default form submission

        let form = $(this);
        let formData = form.serialize(); // Serialize form data

        $.ajax({
            type: 'POST',
            url: 'system-add-comment.php', // URL to handle comment addition
            data: formData,
            success: function(response) {
                let data = JSON.parse(response);
                if (data.status === 'success') {
                    
                    form.find('input[name="comment_content"]').val('');
                    
                    toastr.success('Comment added successfully!');
                } else {
                    toastr.error('Failed to add comment.');
                }
            },
            error: function() {
                toastr.error('An error occurred while adding the comment.');
            }
        });
    });
});