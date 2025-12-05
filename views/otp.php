<?php
session_start();

// Check if user came from signup
if (!isset($_SESSION['user']) || !isset($_SESSION['email'])) {
    header("Location: signup.php");
    exit();
}

// Generate OTP if not already set
if (!isset($_SESSION['otp'])) {
    $_SESSION['otp'] = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $_SESSION['otp_time'] = time();
}

$error = '';
$success = '';
$otp_sent = false;

// Handle OTP verification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verify_otp'])) {
        $entered_otp = trim($_POST['otp'] ?? '');
        
        // Check if OTP is expired (5 minutes)
        if (time() - $_SESSION['otp_time'] > 300) {
            $error = 'OTP has expired. Please request a new code';
        } elseif (empty($entered_otp)) {
            $error = 'Please enter the OTP code';
        } elseif ($entered_otp !== $_SESSION['otp']) {
            $error = 'Invalid OTP code. Please try again';
        } else {
            // OTP verified successfully
            $_SESSION['verified'] = true;
            $success = 'Account verified successfully! Redirecting...';
            // Clear OTP from session
            unset($_SESSION['otp']);
            unset($_SESSION['otp_time']);
            // Redirect after 2 seconds
            header("refresh:2;url=dashboard.php");
        }
    } elseif (isset($_POST['resend_otp'])) {
        // Generate new OTP
        $_SESSION['otp'] = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $_SESSION['otp_time'] = time();
        $otp_sent = true;
    }
}

// Calculate remaining time
$time_remaining = 300 - (time() - $_SESSION['otp_time']);
$time_remaining = max(0, $time_remaining);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - WebVenture</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/otp.css">
</head>
<body>
    <div class="otp-container">
        <div class="logo-section">
            <div class="logo">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1>Verify Your Email</h1>
            <p class="subtitle">
                We've sent a verification code to
            </p>
            <p class="email-display"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
        </div>

        <div class="demo-otp">
            <h3><i class="fas fa-key"></i> Your OTP Code (Demo)</h3>
            <div class="demo-otp-code"><?php echo $_SESSION['otp']; ?></div>
        </div>

        <form method="POST" action="" id="otpForm">
            <div class="otp-inputs">
                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="otp1">
                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="otp2">
                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="otp3">
                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="otp4">
                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="otp5">
                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="otp6">
            </div>
            
            <input type="hidden" name="otp" id="otpValue">
            
            <div class="timer" id="timer">
                Time remaining: <span class="timer-value" id="timerValue"><?php echo gmdate("i:s", $time_remaining); ?></span>
            </div>

            <?php if ($error): ?>
                <div class="feedback-message error">
                    <span class="feedback-icon"><i class="fas fa-exclamation-triangle"></i></span>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="feedback-message success">
                    <span class="feedback-icon"><i class="fas fa-check-circle"></i></span>
                    <span><?php echo htmlspecialchars($success); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($otp_sent): ?>
                <div class="feedback-message info">
                    <span class="feedback-icon"><i class="fas fa-envelope"></i></span>
                    <span>New OTP code has been sent!</span>
                </div>
            <?php endif; ?>

            <button type="submit" name="verify_otp" class="verify-btn" id="verifyBtn">VERIFY</button>
        </form>

        <form method="POST" action="" style="margin: 0;">
            <div class="resend-section">
                Didn't receive the code? 
                <button type="submit" name="resend_otp" class="resend-btn" id="resendBtn">Resend OTP</button>
            </div>
        </form>

        <div class="back-link">
            <a href="signup.php"><i class="fas fa-arrow-left"></i> Back to Sign Up</a>
        </div>
    </div>

    <script src="../assets/js/otp.js" data-time-remaining="<?php echo $time_remaining; ?>" data-has-error="<?php echo ($error && !empty($_POST['otp'])) ? 'true' : 'false'; ?>"></script>
</body>
</html>

