# 🎮 Webibo Developer Guide

**Welcome to the team!** This guide will get you up to speed on how our gamified web tutorial project works. Grab some coffee ☕ and let's dive in.

---

## 📋 Table of Contents

- [What is Webibo?](#what-is-webibo)
- [Tech Stack](#tech-stack)
- [Project Architecture](#project-architecture)
- [Directory Structure](#directory-structure)
- [How the MVC Flow Works](#how-the-mvc-flow-works)
- [Key Files & What They Do](#key-files--what-they-do)
- [Working with Models](#working-with-models)
- [Working with Controllers](#working-with-controllers)
- [Working with Views](#working-with-views)
- [Helper Functions](#helper-functions)
- [Session Management](#session-management)
- [Mock Data (Important!)](#mock-data-important)
- [Common Tasks](#common-tasks)
- [Testing the App](#testing-the-app)
- [Migration Path (Future)](#migration-path-future)
- [Troubleshooting](#troubleshooting)

---

## What is Webibo?

Webibo is a **gamified web development tutorial** that teaches HTML, CSS, and JavaScript through an interactive, Duolingo-style learning experience. Users progress through levels, earn hearts, maintain streaks, and complete coding challenges.

Think: **Duolingo meets Codecademy**, but for web development basics.

---

## Tech Stack

### Frontend
- **HTML5** - Pure semantic markup
- **CSS3** - Custom styling (no frameworks)
- **Vanilla JavaScript** - No jQuery, no React, just pure JS
- **Font Awesome 6** - Icons

### Backend
- **PHP 8.x** - Server-side logic
- **Session-based auth** - No JWT (for now)
- **Mock Models** - Hardcoded arrays (no database yet!)

### Development Environment
- **XAMPP** - Apache + PHP local server
- **Git** - Version control

---

## Project Architecture

We're following a **simplified MVC pattern** optimized for rapid prototyping:

```
User Request → Controller → Model → Controller → View → Response
```

**Key Principle:** Separation of concerns
- **Models** = Data operations (currently mock arrays)
- **Controllers** = Business logic (validation, processing, redirects)
- **Views** = HTML templates (pure presentation)

---

## Directory Structure

```
Webibo/
│
├── assets/                  # Frontend static files
│   ├── css/                 # Stylesheets
│   │   ├── auth.css         # Login/Signup styles
│   │   ├── dashboard.css    # Dashboard styles
│   │   ├── otp.css          # OTP verification styles
│   │   └── activity.css     # Quiz/activity styles
│   ├── js/                  # JavaScript files
│   │   ├── main.js          # Global JS (currently empty)
│   │   ├── dashboard.js     # Dashboard interactivity
│   │   ├── activity.js      # Quiz engine
│   │   └── otp.js           # OTP input handling
│   └── img/                 # Images
│       ├── wiza/            # Mascot character images
│       └── enemies/         # Enemy graphics
│
├── config/                  # Configuration files
│   └── database.php         # DB config (not used yet)
│
├── controllers/             # Business logic layer
│   ├── auth_register.php    # Registration handler
│   ├── auth_login.php       # Login handler
│   └── dashboard.php        # Dashboard data preparation
│
├── core/                    # Core application files
│   ├── functions.php        # Helper functions (START HERE!)
│   └── models/              # Data layer (mock models)
│       ├── UserModel.php    # User CRUD operations
│       ├── StatsModel.php   # Hearts, streaks, courses
│       └── ProgressModel.php # Roadmap/level data
│
├── views/                   # HTML templates
│   ├── login.php            # Login page
│   ├── signup.php           # Registration page
│   ├── otp.php              # Email verification
│   ├── dashboard.php        # Main learning dashboard
│   ├── activity.php         # Multiple choice quiz
│   ├── activity1.php        # Fill-in-the-blank
│   └── activity2.php        # Code editor challenge
│
├── vendor/                  # Composer dependencies
├── composer.json            # PHP dependencies
└── index.php                # Entry point (currently empty)
```

---

## How the MVC Flow Works

### Example: User Registration

**1. User fills out signup form** (`views/signup.php`)
```html
<form method="POST" action="../controllers/auth_register.php">
    <input type="text" name="username" />
    <!-- ... -->
</form>
```

**2. Form submits to controller** (`controllers/auth_register.php`)
```php
// Validate input
if (empty($username)) {
    set_error('Username required');
    redirect('../views/signup.php');
}

// Check if username exists (Model)
if (UserModel::isUsernameTaken($username)) {
    set_error('Username taken');
    redirect('../views/signup.php');
}

// Create user (Model)
UserModel::createUser($firstName, $lastName, $email, $username, $password);

// Success - redirect to OTP
set_success('Registration successful!');
redirect('../views/otp.php');
```

**3. Model handles data** (`core/models/UserModel.php`)
```php
public static function createUser($firstName, $lastName, $email, $username, $password): bool
{
    // Add to mock database array
    self::$users[] = [
        'id' => $newId,
        'username' => $username,
        // ...
    ];
    return true;
}
```

**4. View displays result** (`views/otp.php`)
```php
<?php
$success = get_success(); // Helper function
if ($success): ?>
    <div class="success"><?php echo $success; ?></div>
<?php endif; ?>
```

---

## Key Files & What They Do

### 🔧 `/core/functions.php` - **READ THIS FIRST**

This is your Swiss Army knife. It contains all the helper functions used throughout the app.

**Most Important Functions:**
```php
start_session_securely()      // Always call this before using $_SESSION
is_logged_in()                // Returns true if user is authenticated
require_login()               // Redirects to login if not authenticated
redirect($path)               // Clean redirect helper
set_error($msg)               // Store error message in session
get_error()                   // Retrieve & clear error message
set_success($msg)             // Store success message
get_success()                 // Retrieve & clear success message
get_current_user_id()         // Get logged-in user's ID
formatTimeRemaining($seconds) // "2 hours" instead of "7200"
getLevelIcon($type)           // Map level type to Font Awesome icon
```

**Usage Example:**
```php
require_once '../core/functions.php';
start_session_securely();

if (!is_logged_in()) {
    redirect('../views/login.php');
}
```

---

## Working with Models

### Current Models (All Mock Data!)

#### 1. `UserModel.php` - User Authentication
```php
// Check if email exists
UserModel::isEmailTaken('test@example.com'); // Returns bool

// Check if username exists
UserModel::isUsernameTaken('john'); // Returns bool

// Create new user
UserModel::createUser($firstName, $lastName, $email, $username, $password); // Returns bool

// Get user by username or email
$user = UserModel::getUserByUsernameOrEmail('john'); // Returns array or false

// Verify email
UserModel::verifyUserEmail('test@example.com'); // Returns bool
```

**Demo User (Always Available):**
- Username: `user1`
- Password: `123`
- Email: `user1@example.com`

---

#### 2. `StatsModel.php` - Game Statistics
```php
// Get all stats for a user
$stats = StatsModel::getStatsByUserId(1);

// Returns:
[
    "hearts" => [
        "current" => 10,
        "max" => 10,
        "next_heart_in_seconds" => 7200
    ],
    "streak" => [
        "current_days" => 3,
        "reset_in_seconds" => 10800,
        "weekly_progress" => [true, true, true, false, false, false, false],
        "target_days" => 30
    ],
    "courses" => [
        ["id" => 1, "name" => "HTML Basics", "icon" => "fa-html5", "enrolled" => true],
        // ...
    ]
]

// Update hearts
StatsModel::updateHearts(1, 8); // Set to 8 hearts
StatsModel::decreaseHearts(1);  // Reduce by 1
```

---

#### 3. `ProgressModel.php` - Learning Roadmap
```php
// Get user's level progress
$progress = ProgressModel::getProgressByUserId(1);

// Returns array of levels:
[
    [
        "level_id" => 1,
        "level_number" => 1,
        "type" => "practice",
        "status" => "completed",
        "position" => ["left" => 0, "top" => 280]
    ],
    // ... 9 more levels
]

// Update level status
ProgressModel::updateLevelStatus(1, 3, 'completed'); // User 1, Level 3, mark completed

// Get current active level
$currentLevel = ProgressModel::getCurrentLevel(1);

// Complete current and unlock next
ProgressModel::completeCurrentLevel(1);
```

**Level Types:**
- `practice` - Coding exercise (icon: `fa-code`)
- `lecture` - Tutorial content (icon: `fa-chalkboard-teacher`)
- `boss` - Major challenge (icon: `fa-dragon`)
- `treasure` - Bonus content (icon: `fa-treasure-chest`)

**Level Statuses:**
- `locked` - Not accessible yet (gray)
- `current` - Currently available (glowing)
- `completed` - Finished (green checkmark)

---

## Working with Controllers

### Controller Pattern

**All controllers follow this structure:**

```php
<?php
// 1. Load dependencies
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/../core/models/SomeModel.php';

// 2. Authentication check (if needed)
require_login(); // Or check manually for POST handlers

// 3. Process data
$data = SomeModel::getData();

// 4. Prepare variables for view
$username = get_current_username();
$stats = StatsModel::getStatsByUserId($userId);

// 5. View includes this controller and uses the variables
```

### Two Controller Types

#### Type 1: POST Handlers (Form Processors)
**Examples:** `auth_register.php`, `auth_login.php`

**Purpose:** Process form submissions, validate, redirect

**Pattern:**
```php
// Only run on POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/form_page.php');
}

// Validate input
if (empty($field)) {
    set_error('Field required');
    redirect('../views/form_page.php');
}

// Process with model
Model::doSomething($data);

// Success
set_success('Success!');
redirect('../views/success_page.php');
```

**Important:** These controllers produce **ZERO HTML**. They only redirect.

---

#### Type 2: Data Preparers (View Controllers)
**Example:** `dashboard.php`

**Purpose:** Fetch data from models and prepare variables for view

**Pattern:**
```php
require_once __DIR__ . '/../core/functions.php';
require_login(); // Ensure user is logged in

require_once __DIR__ . '/../core/models/StatsModel.php';

$userId = get_current_user_id();
$userStats = StatsModel::getStatsByUserId($userId);
$userName = get_current_username();

// View file requires this controller at top:
// <?php require_once '../controllers/dashboard.php'; ?>
```

**Important:** These controllers prepare data, then the view includes them with `require_once`.

---

## Working with Views

### View Rules

✅ **DO:**
- Display data using PHP variables
- Use `<?php echo htmlspecialchars($var); ?>` for safety
- Include controller at the top: `<?php require_once '../controllers/controller_name.php'; ?>`
- Use helper functions like `get_error()` and `get_success()`

❌ **DON'T:**
- Process POST data
- Call models directly
- Perform validation logic
- Redirect users
- Contain business logic

### View Pattern

```php
<?php require_once '../controllers/page_controller.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php if ($error = get_error()): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
    
    <!-- Dynamic content -->
    <?php foreach ($items as $item): ?>
        <div class="item"><?php echo htmlspecialchars($item['name']); ?></div>
    <?php endforeach; ?>
</body>
</html>
```

---

## Helper Functions

### Authentication Helpers

```php
// Start session safely (always call first)
start_session_securely();

// Check if user is logged in
if (is_logged_in()) {
    // User is authenticated
}

// Require login (redirect if not logged in)
require_login(); // Default: redirects to ../views/login.php
require_login('/custom/login/path.php'); // Custom login page

// Get current user info
$userId = get_current_user_id();       // Returns int or null
$username = get_current_username();     // Returns string or null
```

### Session Message Helpers

```php
// Set messages
set_error('Something went wrong!');
set_success('Operation completed!');

// Get messages (auto-clears after retrieval)
$error = get_error();       // Returns string (empty if no error)
$success = get_success();   // Returns string (empty if no success)
```

### Utility Helpers

```php
// Redirect and exit
redirect('../views/dashboard.php');

// Format seconds to human-readable
formatTimeRemaining(7200);  // "2 hours"
formatTimeRemaining(300);   // "5 minutes"
formatTimeRemaining(30);    // "less than a minute"

// Get icon class for level type
getLevelIcon('practice');   // "fa-code"
getLevelIcon('boss');       // "fa-dragon"
getLevelIcon('lecture');    // "fa-chalkboard-teacher"

// Sanitize output (alias for htmlspecialchars)
sanitize_output($userInput);
```

---

## Session Management

### Session Variables Currently Used

```php
$_SESSION['user_id']          // User's ID (int)
$_SESSION['user']             // Username (string)
$_SESSION['first_name']       // User's first name
$_SESSION['last_name']        // User's last name
$_SESSION['email']            // User's email

// OTP Verification
$_SESSION['otp']              // 6-digit OTP code
$_SESSION['otp_time']         // Timestamp when OTP was generated
$_SESSION['verified']         // Email verification status (bool)

// Game State
$_SESSION['hearts']           // Current hearts (0-10)
$_SESSION['current_question'] // Activity progress (1-10)

// Flash Messages
$_SESSION['error']            // Error message (use set_error/get_error)
$_SESSION['success']          // Success message (use set_success/get_success)
```

### Session Best Practices

```php
// ✅ GOOD - Use helper functions
start_session_securely();
$userId = get_current_user_id();
set_error('Invalid input');

// ❌ BAD - Direct session manipulation
session_start();
$userId = $_SESSION['user_id'] ?? null;
$_SESSION['error'] = 'Invalid input';
```

---

## Mock Data (Important!)

### 🚨 Current State: NO DATABASE

**Everything is hardcoded in static arrays inside models.**

**This means:**
- Data resets when PHP script ends
- New registrations exist only for that request
- Multiple users won't persist between page loads
- **This is intentional** - we're prototyping!

### Demo User (Always Works)

```php
// These credentials are hardcoded in UserModel::$users
Username: user1
Password: 123
Email: user1@example.com
```

### Mock Data Structure

**UserModel:**
```php
private static $users = [
    [
        'id' => 1,
        'username' => 'user1',
        'password' => '123',  // Plain text (mock only!)
        'email' => 'user1@example.com',
        'first_name' => 'Demo',
        'last_name' => 'User',
        'is_verified' => true
    ]
];
```

**StatsModel:**
```php
private static $userStats = [
    1 => [  // User ID 1
        "hearts" => ["current" => 10, "max" => 10],
        "streak" => ["current_days" => 3, ...],
        "courses" => [...]
    ]
];
```

**ProgressModel:**
```php
private static $userProgress = [
    1 => [  // User ID 1
        ["level_id" => 1, "status" => "completed", ...],
        ["level_id" => 2, "status" => "completed", ...],
        ["level_id" => 3, "status" => "current", ...],
        // ... 7 more levels
    ]
];
```

---

## Common Tasks

### Task 1: Add a New Page

**1. Create the view** (`/views/new_page.php`)
```php
<?php require_once '../controllers/new_page.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>New Page</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1><?php echo $pageTitle; ?></h1>
</body>
</html>
```

**2. Create the controller** (`/controllers/new_page.php`)
```php
<?php
require_once __DIR__ . '/../core/functions.php';
require_login();

require_once __DIR__ . '/../core/models/SomeModel.php';

$userId = get_current_user_id();
$data = SomeModel::getData($userId);
$pageTitle = "My New Page";
```

**3. Create CSS if needed** (`/assets/css/new_page.css`)

**4. Link from somewhere** (e.g., dashboard sidebar)
```html
<a href="new_page.php" class="menu-item">New Page</a>
```

---

### Task 2: Add Form Processing

**1. Create the form view** (`/views/form.php`)
```php
<?php
require_once '../core/functions.php';
start_session_securely();
$error = get_error();
?>
<!DOCTYPE html>
<html>
<body>
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="../controllers/process_form.php">
        <input type="text" name="field" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
```

**2. Create the processor** (`/controllers/process_form.php`)
```php
<?php
require_once __DIR__ . '/../core/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/form.php');
}

start_session_securely();
require_once __DIR__ . '/../core/models/SomeModel.php';

$field = trim($_POST['field'] ?? '');

if (empty($field)) {
    set_error('Field is required');
    redirect('../views/form.php');
}

// Process with model
$result = SomeModel::doSomething($field);

if (!$result) {
    set_error('Processing failed');
    redirect('../views/form.php');
}

set_success('Success!');
redirect('../views/success.php');
```

---

### Task 3: Add a New Model Function

**Example: Add `getUserCount()` to UserModel**

```php
// In /core/models/UserModel.php

/**
 * Get total number of users
 * 
 * @return int Total user count
 */
public static function getUserCount(): int
{
    return count(self::$users);
}
```

**Usage in controller:**
```php
require_once __DIR__ . '/../core/models/UserModel.php';
$totalUsers = UserModel::getUserCount();
```

---

### Task 4: Modify Mock Data

**To add a new demo user:**

```php
// In /core/models/UserModel.php
private static $users = [
    [
        'id' => 1,
        'username' => 'user1',
        // ... existing user
    ],
    // Add new user
    [
        'id' => 2,
        'username' => 'alice',
        'email' => 'alice@example.com',
        'password' => 'test123',
        'first_name' => 'Alice',
        'last_name' => 'Developer',
        'is_verified' => true,
        'created_at' => '2025-12-06 10:00:00'
    ]
];
```

**To modify stats for user 1:**

```php
// In /core/models/StatsModel.php
private static $userStats = [
    1 => [
        "hearts" => [
            "current" => 5,  // Changed from 10
            "max" => 10,
            "next_heart_in_seconds" => 3600  // Changed from 7200
        ],
        // ... rest of stats
    ]
];
```

---

## Testing the App

### Local Testing with XAMPP

**1. Start XAMPP**
- Open XAMPP Control Panel
- Start **Apache**

**2. Access the app**
```
http://localhost/Webibo/views/login.php
```

**3. Login with demo account**
```
Username: user1
Password: 123
```

---

### Manual Testing Checklist

**Authentication Flow:**
- [ ] Can register a new user
- [ ] OTP code is generated (check PHP session)
- [ ] Can verify OTP
- [ ] Can login with demo user (user1/123)
- [ ] Can't access dashboard without login
- [ ] Session persists across page loads

**Dashboard:**
- [ ] Hearts count displays correctly (10)
- [ ] Streak shows 3 days
- [ ] Weekly progress tracker shows correct days
- [ ] Courses popup shows HTML & CSS (enrolled only)
- [ ] Roadmap displays all 10 levels
- [ ] Levels show correct status (2 completed, 1 current, 7 locked)
- [ ] Level positions render correctly

**Error Handling:**
- [ ] Empty form fields show error message
- [ ] Duplicate username/email shows error
- [ ] Invalid login shows error
- [ ] Success messages appear and clear

---

### Debugging Tips

**Check session data:**
```php
// Add to any controller temporarily
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
die();
```

**Check model data:**
```php
// Add to controller
echo '<pre>';
print_r(UserModel::getAllUsers());
echo '</pre>';
die();
```

**Check if function exists:**
```php
if (function_exists('start_session_securely')) {
    echo "Function exists!";
} else {
    echo "Function not loaded - check require_once path";
}
```

**Common issues:**
- **"Headers already sent"** → You echoed output before `redirect()`. Check for whitespace before `<?php`
- **"Function not found"** → Forgot to include `functions.php`
- **"Session not found"** → Forgot to call `start_session_securely()`
- **Data not persisting** → Remember, mock data resets on page reload!

---

## Migration Path (Future)

### When We Add a Real Database

**Phase 1: Database Setup**
1. Create MySQL database
2. Design schema based on mock data structure
3. Run migrations to create tables

**Phase 2: Model Refactoring**
```php
// OLD (Mock):
private static $users = [...];

public static function getUserById($id) {
    foreach (self::$users as $user) {
        if ($user['id'] === $id) return $user;
    }
    return false;
}

// NEW (Database):
public static function getUserById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
```

**Phase 3: Migration Steps**
1. Update `UserModel.php` - switch to PDO queries
2. Update `StatsModel.php` - switch to PDO queries
3. Update `ProgressModel.php` - switch to PDO queries
4. **Controllers and views remain unchanged!** ✨

**This is the power of MVC - we can swap out the entire data layer without touching business logic or presentation.**

---

## Troubleshooting

### "Call to undefined function start_session_securely()"

**Solution:** Add this to the top of your file:
```php
require_once __DIR__ . '/../core/functions.php';
```

---

### "Cannot modify header information - headers already sent"

**Cause:** You're calling `redirect()` after HTML output.

**Solutions:**
1. Check for whitespace/newlines before `<?php`
2. Remove any `echo` statements before redirects
3. Make sure no HTML is output in controllers

**Example of problem:**
```php
<?php
echo "Processing..."; // ❌ This causes the error
redirect('../views/page.php');
```

---

### "Undefined index: user_id in session"

**Cause:** User is not logged in, but you're accessing session without checking.

**Solution:** Use helper functions:
```php
// ❌ BAD
$userId = $_SESSION['user_id'];

// ✅ GOOD
$userId = get_current_user_id();
if ($userId === null) {
    redirect('../views/login.php');
}

// ✅ EVEN BETTER
require_login(); // Automatically redirects if not logged in
$userId = get_current_user_id();
```

---

### Data Doesn't Persist Between Requests

**Remember:** We're using **mock models** with static arrays. Data resets on every page load.

**This is expected behavior for now.**

To "persist" data temporarily:
1. Store in `$_SESSION` (for current user only)
2. Or wait until we implement the database

---

### Dashboard Shows No Levels

**Check:**
1. User is logged in? (`$_SESSION['user_id']` exists?)
2. User ID exists in `ProgressModel::$userProgress`?
3. Controller is loading models correctly?

**Debug:**
```php
// In dashboard controller
echo '<pre>';
echo "User ID: " . $userId . "\n";
echo "Progress Data:\n";
print_r($userProgress);
echo '</pre>';
die();
```

---

## Quick Reference

### File Inclusion Patterns

```php
// In controllers
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/../core/models/UserModel.php';

// In views
require_once '../core/functions.php';
require_once '../controllers/dashboard.php';
```

### Standard Controller Template

```php
<?php
require_once __DIR__ . '/../core/functions.php';
require_login();

require_once __DIR__ . '/../core/models/SomeModel.php';

$userId = get_current_user_id();
$data = SomeModel::getData($userId);
```

### Standard View Template

```php
<?php require_once '../controllers/page_controller.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page Title</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php if ($error = get_error()): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <!-- Content here -->
</body>
</html>
```

---

## Need Help?

**Common Questions:**
- "Where do I add validation?" → **Controllers**
- "Where do I add database queries?" → **Models**
- "Where do I add HTML?" → **Views**
- "Where do I add helper functions?" → **`/core/functions.php`**

**When in doubt:**
1. Check existing files for patterns
2. Follow the MVC separation
3. Use helper functions instead of reinventing the wheel

---

## Final Notes

### What Makes This Project Unique

✅ **Pure PHP** - No frameworks, easy to understand  
✅ **Mock-first approach** - Frontend and backend developed in parallel  
✅ **Clear separation** - Easy to find and fix bugs  
✅ **Helper-driven** - DRY principles throughout  
✅ **Migration-ready** - Swap mock data for database without touching controllers/views

### Development Philosophy

> "Make it work, make it right, make it fast - in that order."

We're currently in the **"make it work"** phase with mock data. This lets us:
- Test the full user flow
- Validate UX/UI decisions
- Identify missing features
- Build confidence before DB complexity

---

**Happy coding! 🚀**

*Last updated: December 6, 2025*
