<?php
session_start();

// Demo user database (simulated)
$existing_users = [
    'user1' => [
        'email' => 'user1@example.com',
        'first_name' => 'Demo',
        'last_name' => 'User'
    ]
];

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Check if email already exists
    $email_exists = false;
    foreach ($existing_users as $user) {
        if ($user['email'] === $email) {
            $email_exists = true;
            break;
        }
    }
    
    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif (array_key_exists($username, $existing_users)) {
        $error = 'Username already exists. Please choose a different username';
    } elseif ($email_exists) {
        $error = 'Email address is already registered';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        // Success - In a real app, you would save to database here
        $_SESSION['user'] = $username;
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['email'] = $email;
        $success = 'Proceed to OTP.';
        // Redirect after 1.5 seconds
        header("refresh:1.5;url=otp.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up - Webibo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <a href="../index.php" class="close-btn">✕</a>
    <a href="login.php" class="login-btn-header">LOG IN</a>

    <div class="signup-container">
        <h1>Sign up</h1>

        <form method="POST" action="">
            <div class="name-row">
                <div class="input-group">
                    <i class="fas fa-user input-icon"></i>
                    <input 
                        type="text" 
                        name="first_name" 
                        placeholder="First name"
                        class="<?php echo ($error && empty($_POST['first_name'])) ? 'error' : ''; ?>"
                        value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>"
                    >
                </div>
                <div class="input-group">
                    <i class="fas fa-user input-icon"></i>
                    <input 
                        type="text" 
                        name="last_name" 
                        placeholder="Last name"
                        class="<?php echo ($error && empty($_POST['last_name'])) ? 'error' : ''; ?>"
                        value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>"
                    >
                </div>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope input-icon"></i>
                <input 
                    type="email" 
                    name="email" 
                    placeholder="Email"
                    class="<?php echo ($error && (empty($_POST['email']) || !filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL))) ? 'error' : ''; ?>"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                >
            </div>

            <div class="input-group">
                <i class="fas fa-user-circle input-icon"></i>
                <input 
                    type="text" 
                    name="username" 
                    placeholder="Username"
                    class="<?php echo ($error && (empty($_POST['username']) || array_key_exists($_POST['username'] ?? '', $existing_users))) ? 'error' : ''; ?>"
                    value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                >
            </div>

            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Password"
                    class="<?php echo ($error && (empty($_POST['password']) || strlen($_POST['password'] ?? '') < 6)) ? 'error' : ''; ?>"
                >
                <button type="button" class="password-toggle" onclick="togglePassword('password', this)">SHOW</button>
            </div>

            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input 
                    type="password" 
                    id="confirmPassword" 
                    name="confirm_password" 
                    placeholder="Confirm password"
                    class="<?php echo ($error && (empty($_POST['confirm_password']) || $_POST['password'] !== $_POST['confirm_password'])) ? 'error' : ''; ?>"
                >
                <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword', this)">SHOW</button>
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

            <button type="submit" class="signup-btn">SIGN UP</button>
        </form>

        <div class="age-notice">
            You must be 13 years or older to create an account.
        </div>

        <div class="footer-text">
            By signing up to Webibo, you agree to our <a href="#">Terms</a> and <a href="#">Privacy Policy</a>.<br><br>
            This site is protected by reCAPTCHA Enterprise and the<br>
            Google <a href="#">Privacy Policy</a> and <a href="#">Terms of Service</a> apply.
        </div>
    </div>

    <script src="../assets/js/password_toggle.js"></script>
</body>
</html>

