<?php
/**
 * Authentication Controller - User Registration
 * 
 * Handles user registration form submissions
 * Validates input, checks for existing users, creates new accounts
 * NO HTML OUTPUT - Pure logic and redirection
 */

// Load helper functions
require_once __DIR__ . '/../core/functions.php';

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/signup.php');
}

// Start session for storing messages and user data
start_session_securely();

// Load the User Model
require_once __DIR__ . '/../core/models/UserModel.php';

// Retrieve and sanitize form data
$firstName = trim($_POST['first_name'] ?? '');
$lastName = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// Validation: Check if all fields are filled
if (empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($password) || empty($confirmPassword)) {
    set_error('Please fill in all fields');
    redirect('../views/signup.php');
}

// Validation: Check email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_error('Please enter a valid email address');
    redirect('../views/signup.php');
}

// Validation: Check if passwords match
if ($password !== $confirmPassword) {
    set_error('Passwords do not match');
    redirect('../views/signup.php');
}

// Validation: Check password length (minimum 6 characters)
if (strlen($password) < 6) {
    set_error('Password must be at least 6 characters long');
    redirect('../views/signup.php');
}

// Validation: Check if email is already taken
if (UserModel::isEmailTaken($email)) {
    set_error('This email is already registered');
    redirect('../views/signup.php');
}

// Validation: Check if username is already taken
if (UserModel::isUsernameTaken($username)) {
    set_error('This username is already taken');
    redirect('../views/signup.php');
}

// All validation passed - Create the user
$userCreated = UserModel::createUser($firstName, $lastName, $email, $username, $password);

if (!$userCreated) {
    set_error('An error occurred during registration. Please try again.');
    redirect('../views/signup.php');
}

// User created successfully - Set up session for OTP verification
$_SESSION['user'] = $username;
$_SESSION['first_name'] = $firstName;
$_SESSION['last_name'] = $lastName;
$_SESSION['email'] = $email;

// Generate OTP (6-digit code)
$_SESSION['otp'] = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
$_SESSION['otp_time'] = time();

// Set success message
set_success('Registration successful! Please verify your email with the OTP.');

// Redirect to OTP verification page
redirect('../views/otp.php');
