
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
            <textarea placeholder="What's on your mind, Ricky Monsales" spellcheck="false" name="post_description" id="postCardCaption" required></textarea>
            <div class="create-post-options">
                <p>Add to your post</p>
                <ul class="list">
                    <li><img id="uploadImg" src="../images/gallery.png" alt="" title="Upload Images"></li>
                    <input type="file" id="picture" name="pictures[]" style="display: none;" multiple>
                </ul>
            </div>
            <button>Post</button>
        </form>
    </div>
</div>