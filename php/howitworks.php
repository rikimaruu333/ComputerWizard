<?php
require "formfunctions.php";

$response = array();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $response = userlogin($_POST);

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
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
    <link rel="stylesheet" href="../css/globalstyle.css">
    <link rel="stylesheet" href="../css/howitworks.css">
</head>
<body>  
    <?php include("signinupmodal.php"); ?>
    <div class="daddycontainer">
        <?php include("header.php"); ?>
        <div class="stepscontainer reveal1" id="client-guide">
            <div class="guidecontainer">
                <div class="timeline">
                    <div class="container left-container">
                        <img class="img" src="../images/step1.png" alt="" title="Create an Account">
                        <div class="text-box">
                            <h2>Create an Account</h2>
                            <p>- Sign up for a client account on GigHub.</p>
                            <p>- Fill in your profile details, including your location (e.g., Bogo City), to help freelancers find you.</p>
                            <span class="left-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container right-container">
                        <img class="img" src="../images/step2.png" alt="" title="Post a Job">
                        <div class="text-box">
                            <h2>Post a Job</h2>
                            <p>- Click on the "Post a Job" button in the panel.</p>
                            <p>- Select the type of job you want to post.</p>
                            <p>- Provide a detailed description of your computer issue or project requirements.</p>
                            <span class="right-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container left-container">
                        <img class="img" src="../images/step3.png" alt="" title="Receive Proposals">
                        <div class="text-box">
                            <h2>Receive Proposals</h2>
                            <p>- Once your job is posted, qualified freelancers will message you a proposal.</p>
                            <p>- Review each proposal, including the freelancer's profile, skills, ratings, and past work.</p>
                            <p>- Feel free to ask questions or request additional information from freelancers.</p>
                            <span class="left-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container right-container">
                        <img class="img" src="../images/step4.png" alt="" title="Communicate with Freelancers">
                        <div class="text-box">
                            <h2>Communicate with Freelancers</h2>
                            <p>- Use our built-in messaging system to chat with freelancers who have submitted proposals.</p>
                            <p>- Discuss project details, clarify any doubts, and ensure you're on the same page.</p>
                            <span class="right-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container left-container">
                        <img class="img" src="../images/step5.png" alt="" title="Hire your preferred Freelancer">
                        <div class="text-box">
                            <h2>Hire your preferred Freelancer</h2>
                            <p>- After assessing the proposals and conducting interviews if necessary, select the freelancer who best fits your project.</p>
                            <span class="left-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container right-container">
                        <img class="img" src="../images/step6.png" alt="" title="Review and Approve">
                        <div class="text-box">
                            <h2>Review and Approve</h2>
                            <p>- Once the freelancer completes the project, carefully assess the work done and ensure they meet your expectations and requirements.</p>
                            <span class="right-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container left-container">
                        <img class="img" src="../images/step7.png" alt="" title="Release Payment">
                        <div class="text-box">
                            <h2>Release Payment</h2>
                            <p>- If you're satisfied with the freelancer's work, it's time to pay your agreed price.</p>
                            <p>- If adjustments are needed, work with the freelancer to resolve any outstanding issues.</p>
                            <span class="left-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container right-container">
                        <img class="img" src="../images/step8.png" alt="" title="Leave Feedback">
                        <div class="text-box">
                            <h2>Leave Feedback</h2>
                            <p>- After the project concludes, share your honest feedback and rating about the freelancer's performance, located on the freelancer's profile.</p>
                            <p>- This helps build a strong community and assists other clients in making informed decisions.</p>
                            <span class="right-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container left-container">
                        <img class="img" src="../images/step9.png" alt="" title="Browse Freelancers">
                        <div class="text-box">
                            <h2>Browse Freelancers</h2>
                            <p>- You can also browse for freelancers to get started with your project immediately instead of waiting for proposals and book the freelancer you want!</p>
                            <p>- After finding a suitable freelancer, you can book the freelancer and just wait for the confirmation to accept.</p>
                            <span class="left-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container right-container">
                        <img class="img" src="../images/step10.png" alt="" title="Build long-term Relationships">
                        <div class="text-box">
                            <h2>Build long-term Relationships</h2>
                            <p>- Consider establishing ongoing relationships with freelancers whose work you admire for future projects.</p>
                            <span class="right-container-arrow"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="freelancer-btn">FREELANCER GUIDE<i class='bx bx-chevron-right'></i></div>
        </div>

        
        <div class="stepscontainer reveal2" id="freelancer-guide" style="display:none">
            <div class="guidecontainer">
                <div class="timeline">
                    <div class="container left-container">
                        <img class="img" src="../images/step1.png" alt="" title="Create an Account">
                        <div class="text-box">
                            <h2>Create an Account</h2>
                            <p>- Sign up for a freelancer account on GigHub.</p>
                            <p>- Fill up the requirements for your profile and provide your valid ID.</p>
                            <p>- Add a high-quality profile picture to build trust with potential clients.</p>
                            <span class="left-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container right-container">
                        <img class="img" src="../images/step2.png" alt="" title="Services and Schedules">
                        <div class="text-box">
                            <h2>Services and Schedules</h2>
                            <p>- Set up your work services on "Service Listing" in the sidebar.</p>
                            <p>- Choose category and specify your specific service and rate.</p>
                            <p>- Set up your working days and working hours on "Set Schedule" in the sidebar.</p>
                            <p>- Let clients know your availability.</p>
                            <span class="right-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container left-container">
                        <img class="img" src="../images/step3.png" alt="" title="Browse and Search for Jobs">
                        <div class="text-box">
                            <h2>Browse and Search for Jobs</h2>
                            <p>- Explore the job searching feature to see posted projects by clients in GigHub.</p>
                            <p>- Use our search and filter options to find projects that match your skills and interests.</p>
                            <p>- Keep an eye out for new job postings regularly.</p>
                            <span class="left-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container right-container">
                        <img class="img" src="../images/step4.png" alt="" title="Submit Proposals">
                        <div class="text-box">
                            <h2>Submit Proposals</h2>
                            <p>- When you find a job that aligns with your expertise, message that client immediately and submit a customized proposal.</p>
                            <p>- Clearly outline your pricing, estimated completion time, and any additional information to stand out.</p>
                            <span class="right-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container left-container">
                        <img class="img" src="../images/step5.png" alt="" title="Communicate with Clients">
                        <div class="text-box">
                            <h2>Communicate with Clients</h2>
                            <p>- Maintain open and transparent communication with potential clients through our messaging system.</p>
                            <p>- Address any questions or concerns they might have and showcase your professionalism.</p>
                            <span class="left-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container right-container">
                        <img class="img" src="../images/step6.png" alt="" title="Secure the Job">
                        <div class="text-box">
                            <h2>Secure the Job</h2>
                            <p>- Review the job details and requirements one more time to ensure you're fully prepared.</p>
                            <span class="right-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container left-container">
                        <img class="img" src="../images/step7.png" alt="" title="Deliver Outstanding Work">
                        <div class="text-box">
                            <h2>Deliver Outstanding Work</h2>
                            <p>- Complete the job to the best of your abilities and within the agreed-upon timeframe.</p>
                            <p>- Keep the client updated on your progress and seek their input when necessary.</p>
                            <p>- Strive for excellence in every project you undertake.</p>
                            <span class="left-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container right-container">
                        <img class="img" src="../images/step8.png" alt="" title="Get Paid">
                        <div class="text-box">
                            <h2>Get Paid</h2>
                            <p>- After successfully delivering the project, the client will pay you through your agreed payment method.</p>
                            <span class="right-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container left-container">
                        <img class="img" src="../images/step9.png" alt="" title="Build Your Reputation">
                        <div class="text-box">
                            <h2>Build Your Reputation</h2>
                            <p>- Consistently provide high-quality service to clients to earn positive reviews and ratings.</p>
                            <p>- A strong reputation can lead to more job opportunities and better earnings.</p>
                            <span class="left-container-arrow"></span>
                        </div>
                    </div>
                    <div class="container right-container">
                        <img class="img" src="../images/step10.png" alt="" title="Continue Learning and Growing">
                        <div class="text-box">
                            <h2>Continue Learning and Growing</h2>
                            <p>- Stay updated with the latest trends and technologies in home computer services.</p>
                            <p>- Consider expanding your skill set to offer a wider range of services.</p>
                            <span class="right-container-arrow"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="client-btn"><i class='bx bx-chevron-left'></i>CLIENT GUIDE</div>
        </div>
    </div>

    <script type="text/javascript">
        window.addEventListener('load', reveal);

        function reveal() {
            var revealElements = document.querySelectorAll('.reveal, .reveal1, .reveal2');

            revealElements.forEach(function(element) {
                var windowHeight = window.innerHeight;
                var elementTop = element.getBoundingClientRect().top;
                var revealPoint = 100;

                if (elementTop < windowHeight - revealPoint) {
                    element.classList.add('active');
                } else {
                    element.classList.remove('active');
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
        
        (function(){
            emailjs.init({
                publicKey: "XnzWkndgRn2h6N10o",
            });
        })();
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/howitworks.js"></script>
    <script src="../js/signinupmodal.js"></script>
    <script src="../js/registrationmodal.js"></script>
</body>
</html>