<?php
session_start();
require_once __DIR__ . '/api/connect.php';
date_default_timezone_set('Asia/Manila');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/api/phpmailer/vendor/autoload.php';

function sendOTPEmail($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pampamilyangpc@gmail.com';
        $mail->Password = 'jnsmjyvjumjmuzdn';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('pampamilyangpc@gmail.com', 'Pampamilyang Support');
        $mail->addAddress($email);
        $mail->Subject = "Your Account Verification OTP";
        $mail->Body = "Your OTP for account verification is: $otp\nThis code expires in 5 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: ".$mail->ErrorInfo);
        return false;
    }
}

if (!isset($_SESSION['customer_id'])) {
    echo "<script>alert('No pending verification.'); window.location.href='login-page.php';</script>";
    exit();
}

$customer_id = $_SESSION['customer_id'];

// ✅ Fetch customer email once
$stmt = $connect->prepare("SELECT email FROM customer WHERE customer_id = ? LIMIT 1");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

if (!$row || empty($row['email'])) {
    echo "<script>alert('No email found for this account.'); window.location.href='login-page.php';</script>";
    exit();
}
$email = $row['email']; // now defined

// --- Step 1: Auto-generate and send OTP when page loads ---
$stmt = $connect->prepare("SELECT * FROM otp 
                           WHERE customer_id = ? 
                           AND purpose = 'signup_verification' 
                           AND expiration_time >= NOW() 
                           AND is_verified = 0 
                           ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    // Reuse existing OTP
    $row = $res->fetch_assoc();
    $otp = $row['otp_code'];
    // ❌ Do not send email again here
} else {
    // No active OTP, generate new one
    $otp = random_int(100000, 999999);
    $expiry = date("Y-m-d H:i:s", time() + 300);
    $purpose = "signup_verification";

    $stmt = $connect->prepare("INSERT INTO otp (customer_id, otp_code, purpose, expiration_time, is_verified, created_at)
                               VALUES (?, ?, ?, ?, 0, NOW())");
    $stmt->bind_param("isss", $customer_id, $otp, $purpose, $expiry);
    $stmt->execute();

    // ✅ Only send email when new OTP is created
    sendOTPEmail($email, $otp);
}

// --- Step 2: Handle OTP submission ---
if (isset($_POST['verify_otp'])) {
    $enteredOtp = implode('', $_POST['otp']); // from 6 input boxes

    $stmt = $connect->prepare("SELECT * FROM otp 
                               WHERE customer_id = ? AND otp_code = ? 
                               AND purpose = 'signup_verification' 
                               AND expiration_time >= NOW() AND is_verified = 0 
                               ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("ii", $customer_id, $enteredOtp); // bind as integers
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        echo "<script>alert('Invalid or expired OTP. Please Try Again.'); window.history.back();</script>";
        exit();
    }

    // Mark OTP as verified
    $row = $res->fetch_assoc();
    $stmt = $connect->prepare("UPDATE otp SET is_verified = 1 WHERE otp_id = ?");
    $stmt->bind_param("i", $row['otp_id']);
    $stmt->execute();

    // Update customer verified flag
    $stmt = $connect->prepare("UPDATE customer SET verified = 1 WHERE customer_id = ?");
    $stmt->bind_param("i", $customer_id);
    if ($stmt->execute()) {
        echo "<script>alert('Account verified successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to update verification status.'); window.history.back();</script>";
    }
    exit();
}

// --- Step 3: Handle resend OTP ---
if (isset($_POST['resend_otp'])) {
    // Check if there is an active OTP that hasn't expired yet
    $stmt = $connect->prepare("SELECT * FROM otp 
                               WHERE customer_id = ? 
                               AND purpose = 'signup_verification' 
                               AND expiration_time >= NOW() 
                               AND is_verified = 0 
                               ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        // Already has an active OTP
        $row = $res->fetch_assoc();
        $expiryTs = strtotime($row['expiration_time']);
        $remainingSeconds = $expiryTs - time();
        if ($remainingSeconds < 0) $remainingSeconds = 0;
        $mins = floor($remainingSeconds / 60);
        $secs = $remainingSeconds % 60;
        echo "<script>alert('You already have an active OTP. Please wait {$mins}m {$secs}s until it expires.');</script>";
    } else {
        // No active OTP → generate and send a new one
        $otp = random_int(100000, 999999);
        $expiry = date("Y-m-d H:i:s", time() + 300);
        $purpose = "signup_verification";

        // Optional cleanup: mark previous unverified OTPs as expired
        $connect->query("UPDATE otp SET is_verified = 2 WHERE customer_id = ".(int)$customer_id." AND is_verified = 0");

        $stmt = $connect->prepare("INSERT INTO otp (customer_id, otp_code, purpose, expiration_time, is_verified, created_at)
                                   VALUES (?, ?, ?, ?, 0, NOW())");
        $stmt->bind_param("isss", $customer_id, $otp, $purpose, $expiry);
        $stmt->execute();

        sendOTPEmail($email, $otp);
        echo "<script>alert('A new OTP has been sent to your email.');</script>";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Account</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: #f0f2f5;
      width: 100%;
      height: 100vh;
      display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-verification{
        width: 500px;
        height: 350px;
        background: #f0f2f5;
        padding: 20px;
        border-radius: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .verification-card {
      padding: 30px;
      border-radius: 12px;
      text-align: center;
      width: 350px;
    }

    .verification-card h2 {
      margin-bottom: 15px;
      color: #333;
    }

    .otp-boxes {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin: 20px 0;
    }

    .otp-input {
      width: 45px;
      height: 55px;
      text-align: center;
      font-size: 22px;
      border: 2px solid #ccc;
      border-radius: 8px;
      transition: border-color 0.3s;
    }

    .otp-input:focus {
      border-color: #007bff;
      outline: none;
    }

    #otpTimer {
      margin-top: 10px;
      color: #666;
      font-size: 14px;
    }

    .button-row {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }

    .btn-cancel, .btn-confirm {
      flex: 1;
      padding: 10px;
      margin: 0 5px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .btn-cancel {
      background: #ddd;
      color: #333;
    }

    .btn-cancel:hover {
      background: #bbb;
    }

    .btn-confirm {
      background: #474747ff;
      color: #fff;
    }

    .btn-confirm:hover {
      background: #000000ff;
    }

    a{
        cursor: pointer;
        color: #070707ff;
        text-decoration: underline;
        font-size: 14px;
    }

    a:hover{
        color: blue;
    }

    .otp-resend{
        margin-top: 10px;
        text-align: center;
    }

    p{
        margin-top: 10px;
        margin-bottom: 10px;
        font-size: 14px;
        color: #444;
    }

    .resend-link {
  background: none;       /* no button background */
  border: none;           /* no border */
  padding: 0;             /* remove padding */
  margin: 0;
  font-size: 14px;
  color: #007bff;         /* blue text */
  text-decoration: underline; /* underline like a link */
  cursor: pointer;        /* pointer cursor */
}

.resend-link:hover {
  color: #0056b3;         /* darker blue on hover */
}
  </style>
</head>
<body>
    
<div id="verificationModal" class="modal-verification">
    <div class="modal-content-verification">
        <h2>Account Verification</h2>
        <div class="description">
            <p>Please enter the One-Time Password (OTP) sent to your email address to verify your account.</p>
            <p>If you didn't recieved your code, check your spam folder or try again later</p>
        </div>
                <div id="stepOTP" class="step">
                    <form action="#" method="POST">
                        <label>Enter Verification Code</label>

                        <div class="otp-boxes">
                            <input maxlength="1" class="otp-input" name="otp[]" />
                            <input maxlength="1" class="otp-input" name="otp[]" />
                            <input maxlength="1" class="otp-input" name="otp[]" />
                            <input maxlength="1" class="otp-input" name="otp[]" />
                            <input maxlength="1" class="otp-input" name="otp[]" />
                            <input maxlength="1" class="otp-input" name="otp[]" />
                        </div>
                        <div class="otp-resend">
                                <button type="submit" name="resend_otp" class="resend-link">
                                    Didn't receive your OTP? Resend here
                                </button>
                        </div>
                        <div class="button-row">
                            <button type="button" class="btn-cancel" id="cancelOTP"  onclick="location.href='dashboard.php'">Cancel</button>
                            <button type="submit" name="verify_otp" class="btn-confirm" id="verifyOTPBTN">Confirm</button>
                        </div>
                    </form>
                </div>
    </div>
</div>

<script>
  // Grab all OTP inputs
  const otpInputs = document.querySelectorAll(".otp-input");

  otpInputs.forEach((input, index) => {
    // Auto jump forward
    input.addEventListener("input", () => {
      if (input.value.length === 1 && index < otpInputs.length - 1) {
        otpInputs[index + 1].focus();
      }
    });

    // Auto backspace
    input.addEventListener("keydown", (e) => {
      if (e.key === "Backspace" && input.value === "" && index > 0) {
        otpInputs[index - 1].focus();
        otpInputs[index - 1].value = ""; // clear previous box
      }
    });
  });
</script>

</body>
</html>