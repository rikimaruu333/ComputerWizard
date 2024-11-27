<?php
    require "formfunctions.php";
    usercheck_login();
    
    if($_SESSION['USER']->usertype !== 'Client' && $_SESSION['USER']->usertype !== 'Admin') header("Location: freelancer-dashboard.php");
    if($_SESSION['USER']->usertype !== 'Client' && $_SESSION['USER']->usertype !== 'Freelancer') header("Location: admin-dashboard.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../toastr.min.css">
    <link rel="stylesheet" href="../css/globalstyle.css">
    <link rel="stylesheet" href="../css/client-dashboard.css">  
</head>
<body>
<?php include "systemclientheader.php";?>
<?php include "systemclientsidebar.php";?>
<?php include "system-post-comments.php";?>
<?php include "system-booking-request.php";?>
<?php include "system-transaction-details.php";?>
<?php include "client-create-post.php";?>
<?php include "client-update-post.php";?>

        <div class="client-dashboard">
            <div class="client-info">
                <div class="client-img">
                    <img src="" alt="" id="profile">
                </div>
                <div class="client-info-details">
                    <div class="client-details">
                        <div class="client-name-post-button">
                            <h3 id="fullName"></h3>
                            <button id="createPostBtn"><i class="bx bxs-book-content"></i>Create a Post</button>
                        </div>
                        <i id="email"></i>
                        <div class="client-post-transaction">
                            <span id="userTotalPosts"><i class="bx bxs-book-content"></i> 0 posts</span>
                            <span><i class="bx bx-transfer-alt"></i><span id="transactionCount"> 0 transactions</span></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="post-transaction-container">
                <div class="post-transaction-container-toggle">
                    <div class="toggle-post"><p>POSTS</p></div>
                    <div class="toggle-transaction"><p>TRANSACTIONS</p></div>
                </div>

                <div class="posts-container">
                    <div class="posts-without-image">

                    </div> 

                    <div class="vertical-line"></div>

                    <div class="posts-with-image">

                    </div>
                </div>

                <div class="transactions-container">
                    <div class="transactions">

                    </div>
                </div>

                <div id="tagDetailsModal" class="tag-details-modal">
                    <div class="tag-details-modal-content">
                        <div class="tag-details-config">
                            <i class="bx bx-repost" id="changeTaggedFreelancerBtn" title="Change tagged freelancer"></i>
                            <div class="tag-details-freelancer-list">
                                <!-- <div class="post-freelancer">
                                    <img src="../images/userpic1.jpg" alt="" id="profile">
                                    <div class="post-freelancer-details">
                                        <h3>Don Quixote</h3>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <img class="profile-img" src="" alt="Freelancer Profile">
                        <h3 class="freelancer-name"></h3>
                        <p class="tagged-text"></p>
                        <button class="modal-action-btn">View Profile</button>
                    </div>
                </div>



                
                
                <div id="postTemplate" style="display: none;">
                    <div class="card">
                        <div class="card-config">
                            <i class="bx bxs-edit" id= "openUpdatePostModalBtn"></i>
                            <i class="bx bxs-trash delete-post-button"></i>
                        </div>
                        <div class="top">
                            <div class="userDetails">
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
                                <div class="post-view-comments"  id="viewCommentsBtn">
                                    <i class="bx bx-comment-dots" data-post-id="" title="View comments"></i>
                                    <h4 class="comments" id="commentCount">0 comments</h4>
                                </div>
                            </div>
                            <div class="right">
                                <!-- <i class="bx bxs-user-plus" data-post-id="" id="tagFreelancerBtn" title="Tag the freelancer that took on this job"></i>
                                <i class="bx bx-info-circle" data-post-id=""  id="postTagDetailsBtn" title="Show post tag details"></i> -->
                                <div class="post-freelancer-list">
                                    <!-- <div class="post-freelancer">
                                        <img src="../images/userpic1.jpg" alt="" id="profile">
                                        <div class="post-freelancer-details">
                                            <h3>Don Quixote</h3>
                                        </div>
                                    </div> -->
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
                                <input type="text" class="text" id="addCommentInput" name="comment_content" placeholder="Add a comment..." required>
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


        </div>
        <div class="client-freelancer-recommendation">
            <div class="freelancer-recommendation-text">
                <h4>Recommended Freelancers</h4>
                <p>Recommended freelancers are verified and are selected based on their recommendation and rating records.</p>
             
            </div>
            <div class="freelancer-recommendation-list">
                <div class="recommended-freelancer-container">
                    <div class="freelancer-info">
                        <div class="freelancer-info-img">
                            <img src="../images/userpic1.jpg" alt="" id="profile">
                        </div>
                        <div class="freelancer-details">
                            <h3>Don Quixote</h3>
                            <p>Freelancer</p>
                        </div>
                    </div>
                    <div class="freelancer-rating">
                        <p>5.0</p>
                        <div class="stars">
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                        </div>
                        <span>19 recommendations</span>
                    </div>
                    <div class="recommended-freelancer-buttons">
                        <button>View Profile</button>
                        <button>Send Message</button>
                    </div>
                </div>
            </div>
        </div>

        
    <script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="../js/client-dashboard.js"></script>
    <script src="../js/client-create-post.js"></script>
    <script src="../js/client-update-post.js"></script>
    <script src="../js/system-notifications.js"></script>
    <script src="../js/system-check-restriction.js"></script>
    <script src="../js/system-user-settings.js"></script>
    <script src="../js/system-sidebar.js"></script>
    <script src="../js/system-fetch-comment.js"></script>
    <script src="../js/system-add-comment.js"></script>
    <script src="../js/system-booking-request.js"></script>
    <script src="../js/system-get-top-freelancers.js"></script>
</body>
</html>