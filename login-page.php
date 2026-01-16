<?php

if (isset($_GET['query'])) {
  $query = htmlspecialchars($_GET['query']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="./css/login.css?v=1.0">
    <link rel="icon" type="image/png" href="assets/PPClogo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>Pampamilyang PC</title>
    <style></style>
</head>

<body>

    <div class="Signup-Section">
        <div class="container" id="container">
            <div class="form-container sign-up">
                <form method="POST" action="register.php">
                    <h1>Create Account</h1>
                    <span>or use your email for registeration</span>
                    <input type="text" name="Fname" placeholder="First Name" required>
                    <input type="text" name="Lname" placeholder="Last Name" required>
                    <input type="text" name="PNumber" placeholder="Enter 11-digit phone number" pattern="^09[0-9]{9}$" maxlength="11" title="Phone number must start with 09 and be exactly 11 digits" required>
                    <input type="email" name="Email" placeholder="Enter Gmail address" pattern="[a-zA-Z0-9._%+-]+@gmail\.com" required >
                    <div class="password-wrapper">
                        <input type="password" name="Password" id="signupPassword" placeholder="Password" required>
                        <i class="fa-solid fa-eye" onclick="togglePassword('signupPassword', this)"></i>
                    </div>
                    <div class="checkbox-container">
                        <input type="checkbox" id="terms" disabled>
                        <label for="terms" id="terms-label">I agree to the <span id="show-tos" style="color:blue;cursor:pointer;text-decoration:underline;">Terms of Service</span></label>
                    </div>
                    <button name="Sign_up">Sign Up</button>
                </form>
            </div>
            <div class="form-container sign-in">
                <form method="POST" action="register.php">
                    <a href="./index.php">Back to home page</a>
                    <h1>Pampamilyang PC</h1>
                    <h1>Sign In</h1>
                    <span>or use your email password</span>
                    <input type="email" name="Email" placeholder="Email" required>
                    <div class="password-wrapper">
                        <input type="password" name="Password" id="signinPassword" placeholder="Password" required>
                        <i class="fa-solid fa-eye" onclick="togglePassword('signinPassword', this)"></i>
                    </div>
                    <a class="ForgotPassBTN" href="#">Forget Your Password?</a>
                    <button name="Sign_in">Sign In</button>
                </form>
            </div>
            <div class="toggle-container">
                <div class="toggle">
                    <div class="toggle-panel toggle-left">
                        <h1>Welcome Back!</h1>
                        <p>Enter your personal details to use all of site features</p>
                        <button class="hidden" id="login">Sign In</button>
                    </div>
                    <div class="toggle-panel toggle-right">
                        <h1>Hello, Friend!</h1>
                        <p>Register with your personal details to use all of site features</p>
                        <button class="hidden" id="register">Sign Up</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <script>
            function togglePassword(inputId, icon) {
                const input = document.getElementById(inputId);

                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    input.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            }
        </script>

    <!-- Terms of Service Modal -->
    <div id="tos-modal" class="modal">
        <div class="modal-content">
            <h2>Terms of Service</h2>
            <div class="modal-body" id="tos-body">
                <p>These Terms and Conditions determine the use of the system for buying brand-new and second-hand PC parts. 
                By means of access or usage of the system, the user agrees to all the rules written here. The store may update these 
                reserves the right to change these terms and conditions at any time without prior notice, and continued use of the 
                system means acceptance of the updated terms.<br><br></p>
                <p style="margin-bottom:5px;">This system displays product information, including prices, descriptions, and stock availability, based on
                information given by the administrator. Though the system ensures a lot of accuracy in all the information, the store
                may subject to change product details or product availability at any time. All product information should be carefully 
                reviewed prior to ordering a product. <br><br></p>
                <p>Users must provide complete and correct information during registration and checkout. Any incorrect details that cause 
                issues such as failed orders or delays are the user’s responsibility. All orders confirmed in the system are considered 
                final unless the product has not yet been processed or shipped. <br><br></p>
                <p style="margin-bottom:5px;">  The second-hand products will be sold according to their condition as described. It is up to the user to verify 
                compatibility and specifications. The store shall not be responsible for any damages resulting from misuse, improper 
                installation, or incompatibility with the devices of the users. <br><br></p>
                <p style="margin-bottom: 20px;">In case the store detects any fraudulent activity, misuse, or disruption of the system, user access can be suspended 
                or terminated. User's agreement to the system implies reading, understanding, and acceptance of these Terms and Conditions.</p>
            </div>
            <div class="modal-buttons">
                <button id="close-tos">Close</button>
                <button id="accept-tos" disabled>Accept</button>
            </div>
        </div>
    </div>

    <!-- Verification Modal -->

    <script>
        const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');

        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });

        // --- Terms of Service Modal Logic ---
        const termsCheckbox = document.getElementById('terms');
        const showTos = document.getElementById('show-tos');
        const tosModal = document.getElementById('tos-modal');
        const tosBody = document.getElementById('tos-body');
        const acceptTosBtn = document.getElementById('accept-tos');
        const closeTosBtn = document.getElementById('close-tos');
        const signUpBtn = document.querySelector('button[name="Sign_up"]');

        // Initially disable Sign Up button
        signUpBtn.disabled = true;

        // Only show modal when user clicks TOS link
        showTos.addEventListener('click', () => {
            tosModal.style.display = 'flex';
            acceptTosBtn.disabled = true;
            acceptTosBtn.classList.remove('active');
            tosBody.scrollTop = 0; // reset scroll
        });

        // Close modal button
        closeTosBtn.addEventListener('click', () => {
            tosModal.style.display = 'none';
        });

        // Enable Accept button only when scrolled to bottom
        tosBody.addEventListener('scroll', () => {
            if (tosBody.scrollTop + tosBody.clientHeight >= tosBody.scrollHeight) {
                acceptTosBtn.disabled = false;
                acceptTosBtn.classList.add('active'); // turns green
            }
        });

        // Accept button click
        acceptTosBtn.addEventListener('click', () => {
            tosModal.style.display = 'none';
            termsCheckbox.checked = true;
            termsCheckbox.disabled = false; // enable checkbox
            signUpBtn.disabled = false; // enable Sign Up immediately
        });

        // Close modal if clicking outside content
        window.addEventListener('click', (e) => {
            if (e.target === tosModal) {
                tosModal.style.display = 'none';
            }
        });

        const signUpForm = document.querySelector('.form-container.sign-up form');
        signUpForm.addEventListener('submit', (e) => {
        if (!termsCheckbox.checked) {
        e.preventDefault(); // block form submission
        alert("You must accept the Terms of Service before signing up.");
    }
});

    </script>

    <!-- Forget Password Modal -->

 <div id="forgotPasswordModal" class="modal-forgot">
    <div class="modal-content-forgot">

        <h2>Reset Your Password</h2>
                <!-- STEP 1: EMAIL -->
                <div id="stepEmail" class="step active">
                    <form action="api/otp.php" method="POST">
                        <label for="resetIdentifier">Enter Your Email</label>

                        <div class="input-group">
                            <input type="email" name="email" id="resetIdentifier" placeholder="Email" required />
                        </div>

                        <div class="button-row">
                            <button type="button" class="btn-cancel" id="cancelEmail">Cancel</button>
                            <button type="submit" name="send_otp" id="sendOTPBTN">Send OTP</button>
                        </div>
                    </form>
                </div>

                <!-- STEP 2: OTP -->
                <div id="stepOTP" class="step">
                    <form action="api/resetpass.php" method="POST">
                        <label>Enter Verification Code</label>

                        <div class="otp-boxes">
                            <input maxlength="1" class="otp-input" name="otp[]" />
                            <input maxlength="1" class="otp-input" name="otp[]" />
                            <input maxlength="1" class="otp-input" name="otp[]" />
                            <input maxlength="1" class="otp-input" name="otp[]" />
                            <input maxlength="1" class="otp-input" name="otp[]" />
                            <input maxlength="1" class="otp-input" name="otp[]" />
                        </div>

                        <div id="otpTimer" style="margin-top:10px; color:#555; font-size:14px;">05:00</div>

                        <div class="button-row">
                            <button type="button" class="btn-cancel" id="cancelOTP">Cancel</button>
                            <button type="submit" name="verify_otp" class="btn-confirm" id="verifyOTPBTN">Confirm</button>
                        </div>
                    </form>
                </div>

                <!-- STEP 3: NEW PASSWORD -->
                <div id="stepNewPass" class="step">
                    <form action="api/resetpass.php" method="POST">
                        <label for="newPassword">Create New Password</label>

                        <div class="password-container">
                            <input type="password" name="new_password" id="newPassword" placeholder="Enter new password" required />
                        </div>

                        <div class="button-row">
                            <button type="button" class="btn-cancel" id="cancelPass">Cancel</button>
                            <button type="submit" name="reset_password" class="btn-confirm">Confirm</button>
                        </div>
                    </form>
                </div>

    </div>
</div>

<script>
    const modal = document.getElementById("forgotPasswordModal");
    const forgotPasswordLink = document.querySelector(".ForgotPassBTN");

    // Steps
    const stepEmail = document.getElementById("stepEmail");
    const stepOTP = document.getElementById("stepOTP");
    const stepNewPass = document.getElementById("stepNewPass");

    // Cancel buttons
    const cancelEmail = document.getElementById("cancelEmail");
    const cancelOTP = document.getElementById("cancelOTP");
    const cancelPass = document.getElementById("cancelPass");

    // OTP inputs
    const otpInputs = document.querySelectorAll(".otp-input");

    // Open modal
    forgotPasswordLink.onclick = (e) => {
        e.preventDefault();
        modal.style.display = "flex";
        showStep(stepEmail);
    };

    // Cancel buttons → close modal and reset all inputs
    [cancelEmail, cancelOTP, cancelPass].forEach(btn => {
        btn.onclick = () => {
            modal.style.display = "none";
            resetModal();
        };
    });

    // Disable closing modal by clicking outside
    window.addEventListener("click", () => {});

    // Show step function
    function showStep(step) {
        document.querySelectorAll(".step").forEach(s => s.classList.remove("active"));
        step.classList.add("active");
    }

    // Reset modal function
    function resetModal() {
        // Clear email
        document.getElementById("resetIdentifier").value = "";
        // Clear OTP
        otpInputs.forEach(input => input.value = "");
        // Clear new password
        document.getElementById("newPassword").value = "";
        // Go back to Step 1
        showStep(stepEmail);
    }

    // Step 1 → Step 2 (email validation)
    document.getElementById("sendOTPBTN").addEventListener("click", (e) => {
        const emailInput = document.getElementById("resetIdentifier");
        if (emailInput.value.trim() === "") {
            e.preventDefault();
            alert("Please enter your email.");
            return;
        }
        setTimeout(() => showStep(stepOTP), 500);
    });

    // Step 2 → Step 3 (OTP validation)
    // document.getElementById("verifyOTPBTN").addEventListener("click", (e) => {
    //     const otpFilled = Array.from(otpInputs).every(input => input.value.trim() !== "");
    //     if (!otpFilled) {
    //         e.preventDefault();
    //         alert("Please enter all 6 digits of the OTP.");
    //         return;
    //     }
    //     setTimeout(() => showStep(stepNewPass), 500);
    // });
        document.getElementById("verifyOTPBTN").addEventListener("click", function(e) {
                    e.preventDefault();
        
                    const otpValues = Array.from(otpInputs).map(i => i.value.trim()).join('');
                    if (otpValues.length !== 6) {
                        alert("Please enter all 6 digits of the OTP.");
                        return;
                    }
        
                    fetch("api/resetpass.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "verify_otp=1&otp[]=" + otpValues.split('').join('&otp[]=')
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            showStep(stepNewPass);

                        } else {
                            alert(data.message);
                            showStep(stepOTP);  

                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Error verifying OTP.");
                    });
                });
    // OTP auto-jump & auto-backspace
    otpInputs.forEach((input, index) => {
        input.addEventListener("input", () => {
            if (input.value && index < otpInputs.length - 1) otpInputs[index + 1].focus();
        });
        input.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" && input.value === "" && index > 0) {
                otpInputs[index - 1].focus();
                otpInputs[index - 1].value = "";
            }
        });
    });

let timerInterval;
function startOTPTimer(duration) {
    const timerDisplay = document.getElementById("otpTimer");
    let timeRemaining = duration;

    clearInterval(timerInterval); // Clear previous timer if any

    timerInterval = setInterval(() => {
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2,'0')}`;

        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            alert("OTP expired! Please request a new one.");
            showStep(stepEmail); // go back to email step
            resetModal();        // clear all fields
        }

        timeRemaining--;
    }, 1000);
}

// Start timer when step 2 is shown
document.getElementById("sendOTPBTN").addEventListener("click", () => {
    const emailInput = document.getElementById("resetIdentifier");
    if (emailInput.value.trim() !== "") {
        setTimeout(() => {
            showStep(stepOTP);
            startOTPTimer(300); // 5 minutes in seconds
        }, 500);
    }
});

    </script>


</body>

</html>