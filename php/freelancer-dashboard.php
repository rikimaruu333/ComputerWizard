<?php
    require "formfunctions.php";
    usercheck_login();

    if($_SESSION['USER']->usertype !== 'Freelancer' && $_SESSION['USER']->usertype !== 'Admin') header("Location: client-dashboard.php");
    if($_SESSION['USER']->usertype !== 'Freelancer' && $_SESSION['USER']->usertype !== 'Client') header("Location: admin-dashboard.php");
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
    <link rel="stylesheet" href="../css/freelancer-dashboard.css">
</head>
<body>
<?php include "systemfreelancerheader.php";?>
<?php include "systemfreelancersidebar.php";?>
<?php include "system-booking-request.php";?>
<?php include "system-transaction-details.php";?>
<?php include "system-post-comments.php";?>

        <div class="freelancer-dashboard">
            <div class="freelancer-info">
                <div class="freelancer-img">
                    <img src="" alt="" id="profile">
                </div>
                <div class="freelancer-info-details">
                    <div class="freelancer-details">
                        <div class="freelancer-name-album-button">
                            <h3 id="fullName"></h3>
                            <button id="openAlbumFileUploadModal"><i class="bx bxs-image-add"></i>Add to Album</button>
                            <a href="freelancer-calendar.php">
                                <button id=""><i class="bx bx-calendar"></i>Go to Calendar</button>
                            </a>
                        </div>
                        <i id="email"></i>
                        <span><i class="bx bx-like"></i> <span class="recommendation-count">0 recommendations</span></span>
                    </div>
                </div>
                
                <div class="transactions-container">
                    <div class="transactions">

                    </div>
                </div>

            </div>
            
            <div class="album-file-upload-modal" id="albumFileUploadModal">
                <div class="album-file-upload-modal-content">
                    <div class="album-file-upload-modal-header">
                        <h2>Upload an Image</h2>
                        <span class="close-album-file-upload-modal" id="closeAlbumFileUploadModal">&times;</span>
                    </div>
                    <div class="upload-files">
                        <div class="file-upload-wrapper">
                            <form id="fileUploadForm" enctype="multipart/form-data">
                                <div class="files" id="file-upload-form">
                                    <input type="hidden" name="id" id="freelancerId" value="<?= $_SESSION['USER']->id ?>">
                                    <input class="file-upload-input" type="file" name="file" accept="*/*" hidden>
                                    <i class="bx bxs-cloud-upload"></i>
                                    <p>Browse File to Upload</p>
                                </div>
                            </form>
                            <div class="uploaded-image-section">
                                <img id="uploadedImage" src="" alt="Uploaded Image" style="display:none; max-width: 100%; height: auto;">
                            </div>
                            <div class="uploaded-file-section">
                                <section class="progress-area"></section>
                                <section class="uploaded-area"></section>
                            </div>
                        </div>
                    </div>
                    <div class="album-file-upload-modal-footer">
                        <button class="add-album-btn" id="addAlbum">Add</button>
                    </div>
                </div>
            </div>


            <div class="album-tagged-container">
                <div class="album-tagged-container-toggle">
                    <div class="toggle-album"><p>ALBUM</p></div>
                    <div class="toggle-tagged"><p>TAGGED</p></div>
                </div>
                <div class="album-container"> 
                    <div class="album-images-container"> 
                    </div>
                    <i class="album-scroll-arrow bx bxs-down-arrow-circle" title="Auto scroll" id="albumScrollArrow"></i>
                    <div class="announcement">
                        <p><u>Tips:</u> Attach your achievements and certifications throughout your freelancing career to showcase your skills and experiences. <u>Attract your clients!</u></p>
                    </div>
                </div>

                
                <div class="posts-container">
                    <div class="posts-without-image">

                    </div> 

                    <div class="vertical-line"></div>

                    <div class="posts-with-image">

                    </div>
                </div>

                <template id="imageTemplate">
                    <div class="album-imgBg">
                        <input type="hidden" name="albumId" id="albumId" value="">
                        <img src="" alt="Album Image" class="thumbnail">
                        <div class="content1" id="expandBtn">
                            <i class="bx bx-fullscreen"></i> 
                            EXPAND
                        </div>
                    </div>
                </template>

                <div id="tagDetailsModal" class="tag-details-modal">
                    <div class="tag-details-modal-content">
                        <img class="profile-img" src="" alt="Freelancer Profile">
                        <h3 class="freelancer-name"></h3>
                        <p class="tagged-text"></p>
                    </div>
                </div>

                
                <div id="postTemplate" style="display: none;">
                    <div class="card">
                        <div class="card-config">
                            <i class="bx bx-info-circle" id= "postTagDetailsBtn" title="Show post tag details"></i>
                        </div>
                        <div class="top">
                            <div class="userDetails">
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

                
                <div id="multipleImageModal" class="expand-multi-image-modal">
                    <span class="expand-multi-image-modal-close">&times;</span>
                    <div class="content">
                        <a class="prev">&#10094;</a>
                        <div id="modalContent" class="multi-image-modal-content"></div>
                        <a class="next">&#10095;</a>
                    </div>
                </div>
                
                
                <div id="imageModal" class="expand-image-modal">
                    <span class="expand-image-modal-close" id="expandCloseBtn">&times;</span>
                    <img class="expand-image-modal-content-img" id="fullImage" />
                    <div class="expand-image-modal-content">
                        <i class="bx bxs-trash" id="deleteAlbumImage" title="Delete image from Album"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="freelancer-reviews-ratings">
            <div class="freelancer-rating-container">
                <h4>Ratings and reviews</h4>
                <p>Ratings and reviews are verified and are from people who had a transaction with you.</p>
                <div class="rating-analysis">
                    <div class="rating">
                        <h1 id="overall-rating"></h1>
                        <div class="star" id="star-rating">
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                        </div>
                        <p id="transaction-count">19 transactions</p>
                    </div>
                    <div class="total-ratings" id="rating-distribution">
                        
                    </div>
                </div>
            </div>
            <div class="freelancer-review-container">
                
            </div>
        </div>

        
    <script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="../js/freelancer-dashboard.js"></script>
    <script src="../js/freelancer-service.js"></script>
    <script src="../js/freelancer-schedule.js"></script>
    <script src="../js/freelancer-reviews.js"></script>
    <script src="../js/system-notifications.js"></script>
    <script src="../js/system-user-settings.js"></script>
    <script src="../js/system-sidebar.js"></script>
    <script src="../js/system-booking-request.js"></script>
    <script src="../js/system-check-restriction.js"></script>
    <script src="../js/system-add-comment.js"></script>
    <script src="../js/system-fetch-comment.js"></script>
</body>
</html>