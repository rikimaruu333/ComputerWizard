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
    <link rel="stylesheet" href="../css/landingpage.css">
</head>
<body>
    <div class="screencontainer">
        <?php include("signinupmodal.php"); ?>
        <div class="daddycontainer">
            <div class="bgvideocontainer">
                <video autoplay loop muted plays-inline class="bgvideo">
                    <source src="../images/video1.mp4" type ="video/mp4">
                </video>
            </div>
            <?php include("header.php"); ?>
            <div class="pagecontainer">
                <div class="textcontainer">
                    <div class="hellotxt"><font class="hello">HELLO</font> <font color="#140ca5" class="user">USERS</font><br></div>
                    <div class="welcome">
                        Welcome to Bogo City GigHub <br>
                        Hire the best freelancers for any job! 
                        <br><br>
                        Your one-stop platform to connect with talented freelancers and businesses. <br>
                        Whether you're a <font color="#0051ff" class="user"><b>freelancer</b></font> looking for exciting new projects or a <font color="#0051ff" class="user"><b>client</b></font> in search <br>
                        of top-notch talent, you've come to the right place!
                    </div>
                </div>
            </div>
            <div class="sliderscontainer">
                <h1 class="reveal1">
                    TOP FREELANCERS
                    <span><img class="crown" src="../images/crown.png" alt=""></span>
                </h1>
                <div class="slider" style="
                    --width: 100px;
                    --height: 65px;
                    --quantity: 9;
                    ">
                    <div class="list">
                        <div class="item" style="--position: 1"><img src="../images/language_1.png" alt=""></div>
                        <div class="item" style="--position: 2"><img src="../images/language_2.png" alt=""></div>
                        <div class="item" style="--position: 3"><img src="../images/language_3.png" alt=""></div>
                        <div class="item" style="--position: 4"><img src="../images/language_4.png" alt=""></div>
                        <div class="item" style="--position: 5"><img src="../images/language_5.png" alt=""></div>
                        <div class="item" style="--position: 6"><img src="../images/language_6.png" alt=""></div>
                        <div class="item" style="--position: 7"><img src="../images/language_7.png" alt=""></div>
                        <div class="item" style="--position: 8"><img src="../images/language_8.png" alt=""></div>
                        <div class="item" style="--position: 9"><img src="../images/language_9.png" alt=""></div>
                    </div>
                </div>
                <div class="slider" reverse="true" style="
                    --width: 200px;
                    --height: 220px;
                    --quantity: 8;
                    ">
                    <div class="list">
                        <div class="item1" style="--position: 1">
                            <div class="itemcontent">
                                <h4>Derrick Richardson</h4>
                                <p>Total jobs finished: 55</p>
                                <br>
                                <img src="../images/5star.png" alt="">
                            </div>
                            <img src="../images/cartoon1.jpeg" alt="">
                        </div>
                        <div class="item1" style="--position: 2">
                            <div class="itemcontent">
                                <h4>Harry Styles</h4>
                                <p>Total jobs finished: 53</p>
                                <br>
                                <img src="../images/5star.png" alt="">
                            </div>
                            <img src="../images/cartoon2.jpeg" alt="">
                        </div>
                        <div class="item1" style="--position: 3">
                            <div class="itemcontent">
                                <h4>Albert Einstein</h4>
                                <p>Total jobs finished: 49</p>
                                <br>
                                <img src="../images/5star.png" alt="">
                            </div>
                            <img src="../images/cartoon3.jpeg" alt="">
                        </div>
                        <div class="item1" style="--position: 4">
                            <div class="itemcontent">
                                <h4>Old Ossan</h4>
                                <p>Total jobs finished: 45</p>
                                <br>
                                <img src="../images/5star.png" alt="">
                            </div>
                            <img src="../images/cartoon4.jpeg" alt="">
                        </div>
                        <div class="item1" style="--position: 5">
                            <div class="itemcontent">
                                <h4>Patrick Star</h4>
                                <p>Total jobs finished: 42</p>
                                <br>
                                <img src="../images/5star.png" alt="">
                            </div>
                            <img src="../images/cartoon5.jpeg" alt="">
                        </div>
                        <div class="item1" style="--position: 6">
                            <div class="itemcontent">
                                <h4>Mr. Professor</h4>
                                <p>Total jobs finished: 38</p>
                                <br>
                                <img src="../images/5star.png" alt="">
                            </div>
                            <img src="../images/cartoon6.jpeg" alt="">
                        </div>
                        <div class="item1" style="--position: 7">
                            <div class="itemcontent">
                                <h4>Dick Ramsey</h4>
                                <p>Total jobs finished: 31</p>
                                <br>
                                <img src="../images/5star.png" alt="">
                            </div>
                            <img src="../images/cartoon7.jpeg" alt="">
                        </div>
                        <div class="item1" style="--position: 8">
                            <div class="itemcontent">
                                <h4>Gorgon Eyes</h4>
                                <p>Total jobs finished: 24</p>
                                <br>
                                <img src="../images/5star.png" alt="">
                            </div>
                            <img src="../images/cartoon8.jpeg" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="compwiz reveal">
                <p>
                We at 
                <font class="computerwizard"><u>GigHub</u></font> understand the importance of a well-functioning computer in today's digital age.<br>Whether you're facing a pesky software issue, need a new computer built from scratch, or simply require<br>some expert troubleshooting, we're here to help you with all your home service computer needs.
                </p>
            </div>
            <div class="community">
                <div class="wrapper reveal1">
                    <div class="img-area">
                        <div class="inner-area">
                            <img src="../images/cartoon3.jpeg" alt="">
                        </div>
                    </div>
                    <div class="name">Albert Einstein</div>
                    <div class="about">Freelancer</div>
                    <img src="../images/5star.png" alt="">
                    <div class="social-icons">
                        <a href="" class="facebook"><i class="fab fa-facebook"></i></a>
                        <a href="" class="twitter"><i class="fab fa-twitter"></i></a>
                        <a href="" class="instagram"><i class="fab fa-instagram"></i></a>
                        <a href="" class="youtube"><i class="fab fa-youtube"></i></a>
                    </div>
                    <div class="buttons">
                        <button class="trigger-auth">Message</button>
                        <button class="trigger-auth">Book</button>
                    </div>
                </div>
                <div class="communitytxt reveal2">
                    <h1>CHOOSE YOUR FREELANCER!</h1>
                    <p>
                    Our community of skilled and experienced freelancers is dedicated to providing top-notch computer services right at your doorstep. When you choose 
                    <font class="computerwizard"><u>GigHub</u></font>, you're not just getting a service; you're gaining a partner in ensuring your computer runs smoothly.
                    </p>
                </div>
            </div>


            <div class="whycontainer">
                <div class="why reveal1">WHY CHOOSE US?</div>
                <div class="threetxt">
                    <div class="expertise reveal1">
                        <div class="expertiselabel">Expertise</div>
                        <div class="expertisetxt">
                            Our freelancers are passionate<br> 
                            about computers and possess the <br>
                            knowledge to tackle any issue or <br>
                            project, big or small.</div>
                    </div>
                    <div class="convenience reveal">
                        <div class="conveniencelabel">Convenience</div>
                        <div class="conveniencetxt">
                            Say goodbye to the hassle of lugging <br>
                            your computer to a repair shop. Our <br>
                            freelancers come to you, providing <br>
                            hassle-free solutions in the comfort <br>
                            of your home.</div>
                    </div>
                    <div class="trustworthy reveal2">
                        <div class="trustlabel">Trustworthy</div>
                        <div class="trusttxt">
                            We prioritize your security and peace of <br>
                            mind. All our freelancers are vetted and <br>
                            rated by previous customers, ensuring you <br>
                            receive reliable and trustworthy service.</div>
                    </div>
                </div>
                <div class="twotxt">
                    <div class="custom reveal1">
                        <div class="customlabel">Custom Solutions</div>
                        <div class="customtxt">
                            We don't believe in one-size-fits-all <br>
                            solutions. Every job is unique, and our <br>
                            freelancers tailor their services to meet <br>
                            your specific needs.</div>
                    </div>
                    <div class="satisfaction reveal2">
                        <div class="satisfactionlabel">Satisfaction Guaranteed</div>
                        <div class="satisfactiontxt">
                            We stand by the quality of our work. Your <br>
                            satisfaction is our ultimate goal, and we <br>
                            won't rest until your computer is running <br>
                            at its best.</div>
                    </div>
                </div>
            </div>

            <section>
                <h1>JOIN THE COMMUNITY</h1>

                <div class="scroll text1">
                    <div>
                        JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> 
                    </div>
                    <div>
                        JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> 
                    </div>
                </div>
                <div class="scroll text2">
                    <div>
                        JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> 
                    </div>
                    <div>
                        JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> 
                    </div>
                </div>
                <div class="scroll text3">
                    <div>
                        JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> 
                    </div>
                    <div>
                        JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> 
                    </div>
                </div>
                <div class="scroll text4">
                    <div>
                        JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> 
                    </div>
                    <div>
                        JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> JOIN <span>GIGHUB </span>NOW! <span>-</span> 
                    </div>
                </div>
            </section>





            <div class="footer">
                <div class="box1">
                    <img src="../images/gighub.png" alt="" class = "footerlogo">
                    <div class="getstarted">Get started now, and let's lessen your problems in life!</div>
                </div>
                <div class="icons">
                    <a href="https://www.facebook.com/ricky.monsales.12"  target="_blank" class="facebook">
                        <img src="../images/facebook.png" alt="" class="facebook">
                    </a>
                    <a href="https://twitter.com/rikimaruxx3"  target="_blank" class="twitterx">
                        <img src="../images/twitter.png" alt="" class="twitterx">
                    </a>
                    <a href="https://www.instagram.com/areeyeseekeywhy/"  target="_blank" class="instagram">
                        <img src="../images/instagram.png" alt="" class="instagram">
                    </a>
                    <a href="https://discord.gg/xJQSDS4U"  target="_blank" class="discord">
                        <img src="../images/discord.png" alt="" class="discord">
                    </a>
                </div>
                <div class="joinus">
                    At GigHub, we're here to support your freelancing career every step of the way.<br> 
                    Join our community of talented freelancers today and start making a difference with your <br>
                    computer expertise in Bogo City and beyond!
                </div>
                <div class="contactus">
                    Contact Us,<br>
                    The GigHub Team &nbsp&nbsp&nbsp
                    <font color = "#a7a7a7" class="contactemails">
                        crmchs.monsales.ricky@gmail.com &nbsp&nbsp&nbsp
                        chiinpedrozaxx@gmail.com &nbsp&nbsp&nbsp
                        andreitan911@gmail.com &nbsp&nbsp&nbsp
                        batucand14@gmail.com &nbsp&nbsp&nbsp
                        jemberrosellosa@gmail.com
                    </font>
                <hr class="line">
                </div>
            </div>
        </div>
    </div>



    <script type="text/javascript">
        window.addEventListener('scroll', reveal);

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
    <script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
        
        (function(){
            emailjs.init({
                publicKey: "XnzWkndgRn2h6N10o",
            });
        })();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/landingpage.js"></script>
    <script src="../js/signinupmodal.js"></script>
    <script src="../js/registrationmodal.js"></script>
</body>
</html>