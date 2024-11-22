<link rel="stylesheet" href="../css/system-browse-job-posts.css">

<div class="posts-maincontainer">
    <div class="posts-header">
        <h1>Jobs</h1>
    </div>
        
        
    <div class="posts-container">
        <div class="posts-without-image">

        </div> 

        <div class="vertical-line"></div>

        <div class="posts-with-image">

        </div>
        <div id="noPostsMessage">
            No posts found for the selected filters.
        </div>
    </div>
    
    <div id="tagDetailsModal" class="tag-details-modal">
        <div class="tag-details-modal-content">
            <img class="profile-img" src="" alt="Freelancer Profile">
            <h3 class="freelancer-name"></h3>
            <p class="tagged-text"></p>
            <button class="modal-action-btn">View Profile</button>
        </div>
    </div>

                
    <div id="postTemplate" style="display: none;">
        <div class="card">
            <div class="card-config">
                <i class="bx bxs-user-circle" id= "postTagDetailsBtn" title="Show post tagged freelancer"></i>
            </div>
            <div class="top">
                <div class="userDetails" id="viewClientProfile" title="View Profile">
                    <input type="hidden" name="client_id" value="" id="postOwnerId">
                    <div class="profileImg">
                        <img src="../images/userpic.jpg" alt="">
                    </div>
                    <div class="details">
                        <p class="userNickName">Ricky Monsales</p>
                        <p class="userRealName">Client</p>
                    </div>
                </div>
            </div>
            <h4 class="message">
                We are looking for a technician to fix broken laptops.
            </h4>
            <div class="imgBg">
                <img src="../images/l.jpg" alt="" id="thumbnailImg">
                <div class="content1" id="expandBtn"><i class="bx bx-fullscreen"></i>EXPAND</div>
            </div>

            <div class="btns">
                <div class="left">
                    <div class="post-view-comments">
                    <i class="bx bx-comment-dots" data-post-id="" id="viewCommentsBtn" title="View comments"></i>
                        <h4 class="comments">12 comments</h4>
                    </div>
                </div>
            </div>
            <div class="addComments">
                <div class="userImg">
                    <img src="../images/userpic1.jpg" alt="">
                </div>
                <form class="commentForm">
                    <input type="hidden" name="post_id" id="post_id" value="">
                    <input type="hidden" name="user_id" id="user_id" value="">
                    <input type="text" class="text" name="comment_content" placeholder="Add a comment..." required>
                    <i class="bx bxs-send" id="sendCommentBtn"></i>
                </form>
            </div>
            <p class="postTime">1 hour ago</p>
        </div>
    </div>
                
                
    <div id="multipleImageModal" class="expand-image-modal">
        <span class="expand-image-modal-close">&times;</span>
        <div class="content">
            <a class="prev">&#10094;</a>
            <div id="modalContent" class="image-modal-content"></div>
            <a class="next">&#10095;</a>
        </div>
    </div>
</div>

<div class="posts-filter-container">
    <div class="filter-posts-header">
        <h1>FILTER</h1>
    </div>
    
    <div class="filter-selection">
        <select name="post-filter-order" id="filterOrder">
            <option value="Latest Post">Latest Post</option>
            <option value="Oldest Post">Oldest Post</option>
        </select>

        <select name="post-filter-address" id="filterAddress">
            <option value="">All Address</option>
            <option value="Anonang Norte, Bogo City, Cebu">Anonang Norte, Bogo City, Cebu</option>
            <option value="Anonang Sur, Bogo City, Cebu">Anonang Sur, Bogo City, Cebu</option>
            <option value="Banban, Bogo City, Cebu">Banban, Bogo City, Cebu</option>
            <option value="Binabag, Bogo City, Cebu">Binabag, Bogo City, Cebu</option>
            <option value="Bungtod, Bogo City, Cebu">Bungtod, Bogo City, Cebu</option>
            <option value="Carbon, Bogo City, Cebu">Carbon, Bogo City, Cebu</option>
            <option value="Cayang, Bogo City, Cebu">Cayang, Bogo City, Cebu</option>
            <option value="Cogon, Bogo City, Cebu">Cogon, Bogo City, Cebu</option>
            <option value="Dakit, Bogo City, Cebu">Dakit, Bogo City, Cebu</option>
            <option value="Don Pedro, Bogo City, Cebu">Don Pedro, Bogo City, Cebu</option>
            <option value="Gairan, Bogo City, Cebu">Gairan, Bogo City, Cebu</option>
            <option value="Guadalupe, Bogo City, Cebu">Guadalupe, Bogo City, Cebu</option>
            <option value="La Paz, Bogo City, Cebu">La Paz, Bogo City, Cebu</option>
            <option value="LPC, Bogo City, Cebu">LPC, Bogo City, Cebu</option>
            <option value="Libertad, Bogo City, Cebu">Libertad, Bogo City, Cebu</option>
            <option value="Lourdes, Bogo City, Cebu">Lourdes, Bogo City, Cebu</option>
            <option value="Malingin, Bogo City, Cebu">Malingin, Bogo City, Cebu</option>
            <option value="Marangog, Bogo City, Cebu">Marangog, Bogo City, Cebu</option>
            <option value="Nailon, Bogo City, Cebu">Nailon, Bogo City, Cebu</option>
            <option value="Odlot, Bogo City, Cebu">Odlot, Bogo City, Cebu</option>
            <option value="Pandan, Bogo City, Cebu">Pandan, Bogo City, Cebu</option>
            <option value="Polambato, Bogo City, Cebu">Polambato, Bogo City, Cebu</option>
            <option value="Sambag, Bogo City, Cebu">Sambag, Bogo City, Cebu</option>
            <option value="San Vicente, Bogo City, Cebu">San Vicente, Bogo City, Cebu</option>
            <option value="Siocon, Bogo City, Cebu">Siocon, Bogo City, Cebu</option>
            <option value="Sto. Nino, Bogo City, Cebu">Sto. Nino, Bogo City, Cebu</option>
            <option value="Sto. Rosario, Bogo City, Cebu">Sto. Rosario, Bogo City, Cebu</option>
            <option value="Sudlonon, Bogo City, Cebu">Sudlonon, Bogo City, Cebu</option>
            <option value="Taytayan, Bogo City, Cebu">Taytayan, Bogo City, Cebu</option>
        </select>

        <select name="post-filter-gender" id="filterGender">
            <option value="">All Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
    </div>
</div>