
$(document).ready(function() {
    // Function to open the modal and fetch comments
    $(document).on('click', '#viewCommentsBtn', function() {
        let postId = $(this).data('post-id'); // Get post ID from the clicked button
        fetchComments(postId);
    });

    // Close the modal
    $('#closeCommentModal').click(function() {
        $('#commentModal').hide();
    });

    function fetchComments(postId) {
        $.ajax({
            type: 'GET',
            url: 'system-fetch-comments.php', // PHP file to fetch comments
            data: { post_id: postId },
            success: function(response) {
                let data = JSON.parse(response);
                if (data.status === 'success') {
                    displayComments(data.comments);
                    $('#commentModal').show();
                } else {
                    alert('Failed to load comments.');
                }
            },
            error: function() {
                alert('An error occurred while fetching comments.');
            }
        });
    }

    function displayComments(comments) {
        let commentsContainer = $('#commentsContainer');
        commentsContainer.empty(); // Clear previous comments
    
        if (comments.length === 0) {
            // Append "No comments" message if there are no comments
            commentsContainer.append(`
                <div class="no-comments">
                    <p>No comments yet.</p>
                </div>
            `);
        } else {
            // Loop through comments and display them
            comments.forEach(comment => {
                let ownerButtons = comment.is_owner ? `
                    <div class="comment-actions">
                        <i class="bx bxs-edit" data-comment-id="${comment.comment_id}"></i>
                        <i class="bx bxs-trash delete-comment-button" data-comment-id="${comment.comment_id}"></i>
                    </div>
                ` : '';
    
                commentsContainer.append(`
                    <div class="comment-container">
                        <div class="comment-user-info">
                            <img src="${comment.profile}" alt="" class="comment-profile" />
                            <div class="comment-user-details">
                                <h3>${comment.username}</h3>
                                <p>${comment.usertype}</p>
                            </div>
                        </div>
                        <div class="comment-content">
                            <p>${comment.content}</p>
                        </div>
                        <div class="comment-datetime">
                            <p>${moment(comment.comment_date).fromNow()}</p>
                        </div>
                        ${ownerButtons}
                    </div>
                `);
            });
        }
    }



    $(document).on('click', '.delete-comment-button', function () {
        let commentId = $(this).data('comment-id'); // Get comment ID
    
        // Show confirmation dialog using SweetAlert2
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete this comment?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel',
            customClass: {
                popup: 'small-swal-popup',
                icon: 'small-swal-icon',
                confirmButton: 'custom-confirm-button',
                cancelButton: 'custom-cancel-button',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                deleteComment(commentId); // Call the deleteComment function if confirmed
            }
        });
    });
    
    
    function deleteComment(commentId) {
        $.ajax({
            type: 'POST',
            url: 'system-delete-comment.php', // PHP file to delete comments
            data: { comment_id: commentId },
            success: function(response) {
                let data = JSON.parse(response);
                if (data.status === 'success') {
                    Swal.fire('Deleted!', 'Comment deleted successfully.', 'success');
                    $(`.bxs-trash[data-comment-id="${commentId}"]`).closest('.comment-container').remove(); // Remove the comment from DOM
                } else {
                    alert(data.message || 'Failed to delete comment.');
                }
            },
            error: function() {
                alert('An error occurred while deleting the comment.');
            }
        });
    }




    $(document).on('click', '.bxs-edit', function () {
        let commentId = $(this).data('comment-id'); // Get comment ID
        let commentContainer = $(this).closest('.comment-container');
        let commentContent = commentContainer.find('.comment-content p').text(); // Get the existing content
    
        // Display edit form in place of comment content
        commentContainer.find('.comment-content').html(`
            <textarea class="edit-comment-text" rows="4">${commentContent}</textarea>
            <div class="edit-comment-actions">
                <button class="save-comment-button" data-comment-id="${commentId}">Save</button>
                <button class="cancel-edit-button">Cancel</button>
            </div>
        `);
    });
    
    // Cancel editing
    $(document).on('click', '.cancel-edit-button', function () {
        let commentContainer = $(this).closest('.comment-container');
        let originalContent = commentContainer.find('.edit-comment-text').val(); // Get original content
        commentContainer.find('.comment-content').html(`<p>${originalContent}</p>`); // Restore original content
    });
    
    // Save updated comment
    $(document).on('click', '.save-comment-button', function () {
        let commentId = $(this).data('comment-id'); // Get comment ID
        let commentContainer = $(this).closest('.comment-container');
        let newContent = commentContainer.find('.edit-comment-text').val(); // Get updated content
    
        if (newContent.trim() === '') {
            alert('Comment content cannot be empty.');
            return;
        }
    
        updateComment(commentId, newContent, commentContainer);
    });
    
    function updateComment(commentId, content, commentContainer) {
        $.ajax({
            type: 'POST',
            url: 'system-update-comment.php', // PHP file to update comments
            data: { comment_id: commentId, content: content },
            success: function (response) {
                let data = JSON.parse(response);
                if (data.status === 'success') {
                    toastr.info('Comment updated successfully.');
                    commentContainer.find('.comment-content').html(`<p>${content}</p>`); // Update the content in DOM
                } else {
                    alert(data.message || 'Failed to update comment.');
                }
            },
            error: function () {
                alert('An error occurred while updating the comment.');
            },
        });
    }
    
    
    
    
    
});

