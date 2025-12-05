<?php
/**
 * Authentication Controller - User Login
 * 
 * Handles user login form submissions
 * Validates credentials, verifies user, manages sessions
 * NO HTML OUTPUT - Pure logic and redirection
 */

// Load helper functions
require_once __DIR__ . '/../core/functions.php';

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/login.php');
}

// Start session for storing messages and user data
start_session_securely();

// Load the User Model
require_once __DIR__ . '/../core/models/UserModel.php';

// Retrieve and sanitize form data
$identifier = trim($_POST['username'] ?? ''); // Can be username or email
$password = $_POST['password'] ?? '';

// Validation: Check if fields are filled
if (empty($identifier) || empty($password)) {
    set_error('Please fill in all fields');
    redirect('../views/login.php');
}

// Attempt to get user by username or email
$user = UserModel::getUserByUsernameOrEmail($identifier);

// Check if user exists
if (!$user) {
    set_error('Invalid username/email or password');
    redirect('../views/login.php');
}

// Verify password
// Note: In mock model, passwords are stored as plain text
// In production, this would use password_verify() with hashed passwords
if ($password !== $user['password']) {
    set_error('Invalid username/email or password');
    redirect('../views/login.php');
}

// Optional: Check if user's email is verified
// Uncomment when OTP verification is fully implemented
// if (!$user['is_verified']) {
//     $_SESSION['error'] = 'Please verify your email before logging in';
//     $_SESSION['email'] = $user['email'];
//     header('Location: ../views/otp.php');
//     exit;
// }

// Login successful - Set session variables
$_SESSION['user'] = $user['username'];
$_SESSION['user_id'] = $user['id'];
$_SESSION['first_name'] = $user['first_name'];
$_SESSION['last_name'] = $user['last_name'];
$_SESSION['email'] = $user['email'];

// Initialize game stats for new session
$_SESSION['hearts'] = $_SESSION['hearts'] ?? 10;
$_SESSION['current_question'] = $_SESSION['current_question'] ?? 1;

// Set success message
set_success('Login successful! Welcome back, ' . $user['first_name'] . '!');

// Redirect to dashboard
redirect('../views/dashboard.php');
