<link rel="stylesheet" href="../css/signinupmodal.css">

<div class="auth-modal" id="auth-modal">
    <div class="auth-modal-content">
        <div class="auth-container" id="container">
            <div class="form-container sign-up">
                <div class="form">
                    <div class="social-icons">
                        <img src="../images/gighublogo.png" alt="">
                    </div>
                    <span>Select type of Account to sign up.</span>
                    <button id="client-signupBtn">Sign Up as Client</button>
                    <button id="freelancer-signupBtn">Sign Up as Freelancer</button>
                </div>
            </div>
            <div class="form-container sign-in">
                <div class="error-container">
                    <div class="error-box" style="display: none;"></div> 
                </div>
                <form id="loginForm" method="POST">
                    <h1>Sign In</h1><br>
                    <input type="email" placeholder="Email" name="email" id="loginemail" required>
                    <input type="password" placeholder="Password" name="password" id="loginpassword"required>
                    <br>
                    <button type="submit">Sign In</button>
                </form>
            </div>
            <div class="toggle-container">
                <div class="toggle">
                    <span class="close" id="close">&times;</span>
                    <div class="toggle-panel toggle-left">
                        <h1>Welcome Back!</h1>
                        <p>Enter your personal details to use all of site features</p>
                        <button class="hidden" id="login"><i class="bx bx-chevron-left"></i> Sign In</button>
                    </div>
                    <div class="toggle-panel toggle-right">
                        <h1>Join Gighub!</h1>
                        <p>Register with your personal details to use all of site features</p>
                        <button class="hidden" id="register">Sign Up <i class="bx bx-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<?php include("registrationmodal.php"); ?>


