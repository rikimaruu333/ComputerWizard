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
            <textarea placeholder="What's on your mind, Ricky Monsales" spellcheck="false" name="post_description" id="updatePostCardCaption" required></textarea>
            <div class="update-post-options">
                <p>Add to your post</p>
                <ul class="list">
                    <li><img id="updateUploadImg" src="../images/gallery.png" alt="" title="Upload Images"></li>
                    <input type="file" id="updatePicture" name="pictures[]" style="display: none;" multiple>
                </ul>
            </div>
            <button>Update Post</button>
        </form>
    </div>
</div>