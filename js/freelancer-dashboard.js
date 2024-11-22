document.addEventListener("DOMContentLoaded", function () {
    
    const toggleAlbum = document.querySelector('.toggle-album');
    const toggleTagged = document.querySelector('.toggle-tagged');
    const albumImageContainer = document.querySelector('.album-container');
    const postsContainer = document.querySelector('.posts-container');

    // Function to update UI based on the current toggle state
    function updateUI() {
        const activeToggle = localStorage.getItem('activeToggle');

        if (activeToggle === 'tagged') {
            toggleTagged.classList.add('active');
            toggleAlbum.classList.remove('active');
            postsContainer.style.display = 'block';
            albumImageContainer.style.display = 'none';
        } else {
            toggleAlbum.classList.add('active');
            toggleTagged.classList.remove('active');
            albumImageContainer.style.display = 'block';
            postsContainer.style.display = 'none';
        }
    }

    // Set initial visibility based on local storage
    updateUI();

    // Add event listeners for toggles
    toggleAlbum.addEventListener('click', () => {
        // Update classes for toggles
        toggleAlbum.classList.add('active');
        toggleTagged.classList.remove('active');

        // Show album container and hide posts container
        albumImageContainer.style.display = 'block';
        postsContainer.style.display = 'none';

        // Save the state to local storage
        localStorage.setItem('activeToggle', 'album');
    });

    toggleTagged.addEventListener('click', () => {
        // Update classes for toggles
        toggleTagged.classList.add('active');
        toggleAlbum.classList.remove('active');

        // Show posts container and hide album container
        postsContainer.style.display = 'block';
        albumImageContainer.style.display = 'none';

        // Save the state to local storage
        localStorage.setItem('activeToggle', 'tagged');
    });
    // If you want to reset the state back to default at some point (e.g., a logout action), you can clear the localStorage by calling:

    // localStorage.removeItem('activeToggle');







        
    $(document).ready(function() {
        fetchPosts();
        // Single AJAX request to fetch posts
    });
    function fetchPosts() {
        $.ajax({
            url: 'freelancer-fetch-tagged-posts.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && !response.error) {
                    if (Array.isArray(response) && response.length > 0) {
                        // Populate cards if posts are found
                        populateCard(response);
                    } else {
                        // Display fallback message if no posts
                        displayNoTaggedPostsMessage();
                    }
                } else {
                    console.error('Error fetching posts:', response.error);
                    displayNoTaggedPostsMessage();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching posts:', error);
                displayNoTaggedPostsMessage();
            }
        });
    }
    
    function displayNoTaggedPostsMessage() {
        $('.posts-container').html(`
            <div class="no-posts">
                <p>No tagged posts found.</p>
            </div>
        `);
    }
    
    function populateCard(posts) {
        var cardWithImageContainer = $('.posts-with-image');
        var cardWithoutImageContainer = $('.posts-without-image');
        var postTemplate = $('#postTemplate').html();

        // Clear existing posts
        cardWithImageContainer.empty();
        cardWithoutImageContainer.empty();

        posts.forEach(function(post) {
            var card = $(postTemplate).clone();

            // Populate user details
            $('#userTotalPosts').html(`<i class="bx bxs-book-content"></i> ${post.total_jobposts} posts`);

            var infoButton = `<i class="bx bx-info-circle" data-post-id="${post.post_id}" id="postTagDetailsBtn" title="Show post tag details"></i>`;

            
            if (post.isTagged) {
                // Post is tagged, append the info button instead of the tag button
                card.find('.right').append(infoButton);
            } 
                    
            card.find('#postOwnerId').val(post.owner_id);
            card.find('.profileImg img').attr('src', post.profile);
            card.find('.userNickName').text(post.firstname + " " + post.lastname);
            card.find('.userRealName').text(post.usertype);

            // Populate post message
            card.find('.message').text(post.caption);

            // Handle images
            var imgBg = card.find('.imgBg');
            imgBg.empty();

            // Append images
            if (post.images.length > 0) {
                imgBg.removeClass().addClass('imgBg');

                // Adjust image classes based on the number of images
                if (post.images.length === 2) {
                    imgBg.addClass('two-images');
                } else if (post.images.length === 3) {
                    imgBg.addClass('three-images');
                } else if (post.images.length === 4) {
                    imgBg.addClass('four-images');
                } else if (post.images.length > 4) {
                    imgBg.addClass('four-images-more');
                }

                post.images.forEach(function(image, index) {
                    if (index < 4) {
                        imgBg.append($('<img>').attr('src', image).on('click', function() {
                            openModal(post.images, index); // Call modal function
                        }));
                    }

                    if (index === 3 && post.images.length > 4) {
                        var additionalImagesCount = post.images.length - 4;
                        var viewMoreDiv = $('<div class="content2">+' + additionalImagesCount + '</div>');
                        imgBg.append(viewMoreDiv);
                    }
                });

                // Show expand button
                imgBg.append($('<div class="content1" id="expandBtn"><i class="bx bx-fullscreen"></i>EXPAND</div>').on('click', function() {
                    openModal(post.images, 0); // Open modal from the first image
                }));
            } else {
                imgBg.hide(); // Hide image container if no images
            }
            card.find('#viewCommentsBtn').attr('data-post-id', post.post_id);
            card.find('#postTagDetailsBtn').attr('data-post-id', post.post_id);
            card.find('.userImg img').attr('src', post.currentUserProfile);
            card.find('#post_id').val(post.post_id);
            card.find('#user_id').val(post.currentUserID);
            card.find('#commentCount').text(post.total_comments + ' comments');

            
            var formattedTime = moment(post.post_created).format('MMMM D, YYYY [at] h:mm A');
            card.find('.postTime').text(formattedTime); // Set the formatted time

            // Hide message if no caption
            if (!post.caption) {
                card.find('.message').hide();
            }

            // Append the card to the appropriate container
            if (post.images.length > 0) {
                cardWithImageContainer.append(card);
            } else {
                cardWithoutImageContainer.append(card);
            }

            card.removeClass('postTemplate').show();

            // Close modal when the close button or outside the modal is clicked
            $('#tagDetailsModal .close, #tagDetailsModal').on('click', function(event) {
                if ($(event.target).is('#tagDetailsModal, .close')) {
                    $('#tagDetailsModal').fadeOut();
                }
            });



        });
    }
    
    // Attach click event listener to the userDetails
    $(document).on('click', '.userDetails', function() {
        const clientId = $(this).closest('.card').find('#postOwnerId').val();
        window.location.href = `system-view-client-profile.php?client_id=${clientId}`;
    });

    

    $(document).on('click', '#postTagDetailsBtn', function () {
        var postId = $(this).data('post-id');
        console.log(postId);

        $.ajax({
            url: 'system-get-tagged-freelancer.php', // Adjust to the file path of your PHP script
            method: 'POST',
            data: { post_id: postId },
            success: function (response) {
                console.log("Raw response:", response);
                try {
                    var responseData = JSON.parse(response);
        
                    if (responseData.success) {
                        var taggedUser = responseData.data.taggedUser;
        
                        if (taggedUser) {
                            // Populate modal with user details
                            $('#tagDetailsModal .profile-img').attr('src', taggedUser.profile);
                            $('#tagDetailsModal .freelancer-name').text(taggedUser.firstname + ' ' + taggedUser.lastname);
                            $('#tagDetailsModal .tagged-text').text('Tagged freelancer');
        
                            // Add freelancer_id to the "View Profile" button as a data attribute
                            $('#tagDetailsModal .modal-action-btn').data('freelancer-id', taggedUser.id);
        
                            // Show the modal
                            $('#tagDetailsModal').show();
                        } else {
                            Swal.fire('Error', 'No tagged user found for this post.', 'info');
                        }
                    } else {
                        Swal.fire('Error', responseData.message, 'error');
                    }
                } catch (e) {
                    console.error("Parsing error:", e, response);
                    Swal.fire('Error', 'An unexpected error occurred.', 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Unable to fetch tagged freelancer details.', 'error');
            }
        });
    });


    var currentIndex; // to keep track of the current image index

    function openModal(images, index) {
        currentIndex = index; // Set current index
        $('#modalContent').empty(); // Clear previous content
        images.forEach(function(image) {
            $('#modalContent').append($('<img>').attr('src', image));
        });
    
        showImage(currentIndex); // Show the first image
        $('.prev').toggle(images.length > 1); // Show/hide prev button based on the number of images
        $('.next').toggle(images.length > 1); // Show/hide next button based on the number of images
        $('#multipleImageModal').css('display', 'block'); // Show modal
    }
    
    function showImage(index) {
        var images = $('#modalContent img'); // Get all images in modal
        if (index < 0) {
            currentIndex = images.length - 1; // Wrap around to last image
        } else if (index >= images.length) {
            currentIndex = 0; // Wrap around to first image
        } else {
            currentIndex = index; // Set current index
        }
        images.hide(); // Hide all images
        $(images[currentIndex]).show(); // Show the current image
    }
    
    // Close modal
    $('.expand-multi-image-modal-close').on('click', function() {
        $('#multipleImageModal').css('display', 'none');
    });
    
    // Navigate images
    $('.prev').on('click', function() {
        showImage(currentIndex - 1);
    });
    
    $('.next').on('click', function() {
        showImage(currentIndex + 1);
    });
    
    // Keyboard navigation
    $(document).keydown(function(e) {
        if ($('#multipleImageModal').is(':visible')) {
            if (e.key === 'ArrowLeft') {
                showImage(currentIndex - 1);
            } else if (e.key === 'ArrowRight') {
                showImage(currentIndex + 1);
            } else if (e.key === 'Escape') {
                $('#multipleImageModal').css('display', 'none');
            }
        }
    });







    

    fetchAlbumImages();


    const openAlbumFileUploadModalBtn = document.getElementById("openAlbumFileUploadModal");
    const albumFileUploadModal = document.getElementById("albumFileUploadModal");
    const closeAlbumFileUploadModalBtn = document.getElementById("closeAlbumFileUploadModal");

    openAlbumFileUploadModalBtn.onclick = function () {
        albumFileUploadModal.style.display = "block";
    };

    closeAlbumFileUploadModalBtn.onclick = function () {
        albumFileUploadModal.style.display = "none";
    };


    const files = document.querySelector(".file-upload-wrapper .files"),
    fileInput = document.querySelector(".file-upload-input"),
    progressArea = document.querySelector(".progress-area"),
    uploadedArea = document.querySelector(".uploaded-area"),
    fileUploadForm = document.getElementById('file-upload-form'),
    uploadedImage = document.getElementById('uploadedImage'),
    addAlbumBtn = document.getElementById('addAlbum');

    let uploadedImageName = ''; 

    files.addEventListener("click", () => {
        fileInput.click();  
    });

    fileInput.onchange = ({ target }) => {
        let file = target.files[0];
        if (file) {
            let fileName = file.name;
            if (fileName.length >= 12) {
                let splitName = fileName.split('.');
                fileName = splitName[0].substring(0, 10) + "... ." + splitName[1];
            }
            uploadFile(file, fileName);  
        }
    };

    function uploadFile(file, name) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../php/freelancer-album-file-upload.php");
        
        xhr.upload.addEventListener("progress", ({ loaded, total }) => {
            let fileLoaded = Math.floor((loaded / total) * 100);
            let progressHTML = `<li class="row">
                                <i class="bx bxs-file"></i>
                                <div class="content">
                                    <div class="details">
                                    <span class="name">${name} • Uploading</span>
                                    <span class="percent">${fileLoaded}%</span>
                                    </div>
                                    <div class="progress-bar">
                                    <div class="progress" style="width: ${fileLoaded}%"></div>
                                    </div>
                                </div>
                                </li>`;
            uploadedArea.classList.add("onprogress");
            progressArea.innerHTML = progressHTML;
        });

        xhr.onload = function () {
            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    uploadedImageName = response.fileName;  
                    progressArea.innerHTML = "";
                    let uploadedHTML = `<li class="row">
                                        <div class="content upload">
                                            <i class="bx bxs-file"></i>
                                            <div class="details">
                                            <span class="name">${name} • Uploaded</span>
                                            </div>
                                        </div>
                                        <i class="bx bx-x" id="upload-cancel" data-filename="${uploadedImageName}"></i>
                                        </li>`;
                    uploadedArea.classList.remove("onprogress");
                    uploadedArea.insertAdjacentHTML("afterbegin", uploadedHTML);

                    let reader = new FileReader();
                    reader.onload = function (e) {
                        fileUploadForm.style.display = 'none';
                        uploadedImage.src = e.target.result;
                        uploadedImage.style.display = 'block';
                    };
                    reader.readAsDataURL(file);

                    document.querySelector("#upload-cancel").addEventListener("click", function () {
                        let fileToDelete = this.getAttribute("data-filename");
                        cancelFileUpload(fileToDelete);
                        this.closest("li").remove();
                
                        uploadedImage.style.display = 'none';
                        uploadedImage.src = '';
                        fileUploadForm.style.display = 'flex'; 
                    });
                } else {
                    console.log(response.message);
                }
            }
        };

        let data = new FormData();
        data.append("file", file);
        xhr.send(data);
    }


    addAlbumBtn.addEventListener('click', function () {
        const freelancerId = $('#freelancerId').val();
        const fileInput = document.querySelector('.file-upload-input');
        const file = fileInput.files[0];  

        if (file && freelancerId) {
            let formData = new FormData();
            formData.append('freelancer_id', freelancerId); 
            formData.append('file', file); 

            $.ajax({
                type: 'POST',
                url: 'freelancer-album-add.php',
                data: formData,
                contentType: false, 
                processData: false, 
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        localStorage.setItem('albumImageUploaded', 'true');
                        albumFileUploadModal.style.display = "none";
                        fetchAlbumImages();

                        fileInput.value = ''; // Clear the file input
                        $('#freelancerId').val(''); // Clear the freelancer ID input
                        uploadedArea.innerHTML = ''; // Clear uploaded images display
                        progressArea.innerHTML = ''; // Clear progress area
                        uploadedImage.style.display = 'none'; // Hide uploaded image
                        fileUploadForm.style.display = 'flex'; // Show file upload form again

                        
                        toastr.success('Image uploaded successfully!');
                    } else {
                        toastr.error(response.message); 
                    }
                },
                error: function () {
                    toastr.error('An error occurred while adding the image to the database.');
                }
            });
        } else {
            toastr.warning('No image uploaded or freelancer ID missing.');
        }
    });


    function cancelFileUpload(fileName) {
        let cancelXHR = new XMLHttpRequest();
        cancelXHR.open("POST", "../php/freelancer-album-file-upload-delete.php", true);
        cancelXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        cancelXHR.send("fileName=" + encodeURIComponent(fileName));

        cancelXHR.onload = function () {
            if (cancelXHR.status === 200) {
                let response = JSON.parse(cancelXHR.responseText);
                console.log(response.message);
            } else {
                console.log("Error deleting the file.");
            }
        };
    }


    function fetchFreelancerData() {
        $.ajax({
            url: 'system-get-user-data.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    updateFreelancerInfo(response.data);
                    console.log(response.data);
                } else {
                    alert(response.message); 
                }
            },
            error: function () {
                alert('Error fetching freelancer data');
            }
        });
    }
    fetchFreelancerData();
    function updateFreelancerInfo(data) {
        document.querySelector(".freelancer-img #profile").src = data.profile ? data.profile : '../images/user.jpg';
        document.querySelector(".freelancer-details #fullName").innerText = data.firstname + ' ' + data.lastname;
        document.querySelector(".freelancer-details #email").innerText = data.email;
        // Update recommendation count
        const recommendationCount = data.recommendation_count || 0;
        document.querySelector(".freelancer-details .recommendation-count").innerText = `${recommendationCount} recommendations`;    
    }

    const albumContainer = document.querySelector('.album-images-container');
    const albumScrollArrow = document.getElementById("albumScrollArrow");

    // Function to check if the album is scrollable and update the arrow's visibility
    function checkScrollability() {
        const isScrollable = albumContainer.scrollHeight > albumContainer.clientHeight;
        const isAtBottom = albumContainer.scrollHeight - albumContainer.scrollTop === albumContainer.clientHeight;

        albumScrollArrow.style.display = isScrollable ? 'block' : 'none'; // Show or hide the arrow
        albumScrollArrow.classList.toggle("up-arrow", isAtBottom); // Add class for up arrow if at bottom
    }

    // Event listener for scroll arrow click
    albumScrollArrow.addEventListener("click", function () {
        if (this.classList.contains("up-arrow")) {
            // Scroll to the top smoothly
            albumContainer.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        } else {
            // Scroll to the bottom smoothly
            albumContainer.scrollTo({
                top: albumContainer.scrollHeight,
                behavior: 'smooth'
            });
        }
        checkScrollability(); // Update scrollability after the action
    });

    // Update scrollability when the user scrolls manually
    albumContainer.addEventListener('scroll', function () {
        checkScrollability(); // Check scrollability on manual scroll
    });

    // Call this function to check scrollability initially
    checkScrollability();
    function fetchAlbumImages() {
        $.ajax({
            url: 'freelancer-get-album.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const albumImages = response.data;
                    updateAlbumImages(albumImages);
                    checkScrollability();
                } else {
                    console.error(response.message);
                }
            },
            error: function() {
                console.error('Error fetching album images.');
            }
        });
    }

    // Update album images in the UI
    function updateAlbumImages(images) {
        const albumContainer = document.querySelector('.album-images-container');
        albumContainer.innerHTML = '';

        const defaultImageSrc = "../images/default-image.jpg";
        const imagesPerRow = 4;
        const totalImages = images.length;
        const totalRows = Math.ceil(totalImages / imagesPerRow);
        const maxImagesToShow = totalRows * imagesPerRow;

        const template = document.getElementById('imageTemplate');

        for (let i = 0; i < maxImagesToShow; i++) {
            const clone = document.importNode(template.content, true);
            const imgElement = clone.querySelector('img');
            const expandBtn = clone.getElementById('expandBtn');
            const albumIdInput = clone.querySelector('input[name="albumId"]');

            if (i < totalImages) {
                imgElement.src = "../album/" + images[i].album_img;
                albumIdInput.value = images[i].album_id; // Set the album ID
                expandBtn.style.visibility = "visible"; // Show the expand button
            } else {
                imgElement.src = defaultImageSrc; 
                albumIdInput.value = ""; 
                expandBtn.style.visibility = "hidden"; // Hide the expand button for default images
            }

            albumContainer.appendChild(clone);
        }
    }

    // Modal and delete functionality
    const modal = document.getElementById("imageModal");
    const fullImage = document.getElementById("fullImage");
    const closeBtn = document.getElementById("expandCloseBtn");
    const deleteBtn = document.getElementById("deleteAlbumImage");

    document.querySelector('.album-images-container').addEventListener('click', function(event) {
        if (event.target.closest('#expandBtn')) {
            const imgElement = event.target.closest('.album-imgBg').querySelector('img.thumbnail');
            const imgSrc = imgElement.src;
            const albumId = event.target.closest('.album-imgBg').querySelector('input[name="albumId"]').value;

            modal.style.display = "block";
            fullImage.src = imgSrc; 

            deleteBtn.dataset.albumId = albumId;
        }
    });

    closeBtn.addEventListener("click", function() {
        modal.style.display = "none";
    });

    window.addEventListener("click", function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    deleteBtn.addEventListener("click", function () {
        const albumId = this.dataset.albumId;
    
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete this image from your album?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'small-swal-popup',
                icon: 'small-swal-icon',
                confirmButton: 'custom-confirm-button',
                cancelButton: 'custom-cancel-button',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'freelancer-album-delete.php',
                    type: 'POST',
                    data: { album_id: albumId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success',
                                customClass: {
                                    confirmButton: 'custom-confirm-button'
                                }
                            });
                            modal.style.display = "none";
                            fetchAlbumImages();
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'custom-confirm-button'
                                }
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while deleting the image.',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'custom-confirm-button'
                            }
                        });
                    }
                });
            }
        });
    });
    
});
