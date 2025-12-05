<?php
session_start();

// Demo credentials
define('DEMO_USERNAME', 'user1');
define('DEMO_PASSWORD', '123');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields';
    } elseif ($username === DEMO_USERNAME && $password === DEMO_PASSWORD) {
        $_SESSION['user'] = $username;
        $success = 'Login successful! Welcome back!';
        // Redirect after 1.5 seconds using meta refresh
        header("refresh:1.5;url=dashboard.php");
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in - WebQuest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <a href="../index.php" class="close-btn">✕</a>
    <a href="signup.php" class="signup-btn">SIGN UP</a>

    <div class="login-container">
        <h1>Log in</h1>

        <form method="POST" action="">
            <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input 
                    type="text" 
                    name="username" 
                    id="username" 
                    placeholder="Email or username" 
                    autocomplete="username"
                    class="<?php echo ($error && empty($_POST['username'])) ? 'error' : ''; ?>"
                    value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                >
            </div>

            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    placeholder="Password" 
                    autocomplete="current-password"
                    class="<?php echo ($error && empty($_POST['password'])) ? 'error' : ''; ?>"
                >
                <a href="#" class="forgot-link">FORGOT?</a>
            </div>

            <?php if ($error): ?>
                <div class="feedback-message error">
                    <span class="feedback-icon">⚠</span>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="feedback-message success">
                    <span class="feedback-icon">✓</span>
                    <span><?php echo htmlspecialchars($success); ?></span>
                </div>
            <?php endif; ?>

            <button type="submit" class="login-btn">LOG IN</button>
        </form>

        <div class="footer-text">
            By signing in to Webibo, you agree to our <a href="#">Terms</a> and <a href="#">Privacy Policy</a>.<br><br>
            This site is protected by reCAPTCHA Enterprise and the<br>
            Google <a href="#">Privacy Policy</a> and <a href="#">Terms of Service</a> apply.
        </div>
    </div>
</body>
</html>

