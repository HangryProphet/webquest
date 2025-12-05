<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webibo Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <button class="mobile-menu-toggle" onclick="toggleMenu()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Top Right Buttons -->
    <div class="top-right-buttons">
        <!-- Lessons Button -->
        <div class="top-btn-wrapper">
            <button class="top-btn lessons-btn">
                <i class="fab fa-html5"></i>
            </button>
            <div class="hover-popup lessons-popup">
                <div class="popup-header">MY COURSES</div>
                <div class="course-list">
                    <div class="course-item">
                        <div class="course-icon">
                            <i class="fab fa-html5"></i>
                        </div>
                        <span class="course-name">HTML</span>
                    </div>
                    <div class="course-item">
                        <div class="course-icon">
                            <i class="fab fa-css3-alt"></i>
                        </div>
                        <span class="course-name">CSS</span>
                    </div>
                    <div class="course-item">
                        <div class="course-icon">
                            <i class="fab fa-js"></i>
                        </div>
                        <span class="course-name">JavaScript</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Streak Button -->
        <div class="top-btn-wrapper">
            <button class="top-btn streak-btn">
                <i class="fas fa-fire"></i>
                <span class="btn-count">3</span>
            </button>
            <div class="hover-popup streak-popup">
                <div class="popup-header">STREAK SOCIETY</div>
                <div class="streak-info">
                    <div class="streak-number">3 day streak</div>
                    <div class="streak-fire-icon">
                        <i class="fas fa-fire"></i>
                    </div>
                </div>
                <div class="streak-timer">3 hours until your streak resets!</div>
                <div class="week-tracker">
                    <div class="day">S</div>
                    <div class="day">M</div>
                    <div class="day">T</div>
                    <div class="day">W</div>
                    <div class="day">T</div>
                    <div class="day active">F</div>
                    <div class="day">S</div>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                        <div class="progress-glow"></div>
                    </div>
                    <div class="progress-icon">
                        <i class="fas fa-fire"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hearts Button -->
        <div class="top-btn-wrapper">
            <button class="top-btn hearts-btn">
                <i class="fas fa-heart"></i>
                <span class="btn-count">10</span>
            </button>
            <div class="hover-popup hearts-popup">
                <div class="popup-title">Hearts</div>
                <div class="hearts-display">
                    <i class="fas fa-heart filled"></i>
                    <i class="fas fa-heart filled"></i>
                    <i class="fas fa-heart filled"></i>
                    <i class="fas fa-heart filled"></i>
                    <i class="fas fa-heart empty"></i>
                </div>
                <div class="next-heart">Next heart in <span class="heart-timer">2 hours</span></div>
                <div class="hearts-message">You still have hearts left! Keep on learning</div>
            </div>
        </div>
    </div>

    <div class="sidebar" id="sidebar">
        <div class="logo">
            <div class="logo-text">Webibo</div>
        </div>
        
        <div class="menu-items">
            <a href="#" class="menu-item active" onclick="closeMenu()">
                <div class="menu-icon">
                    <i class="fas fa-map"></i>
                </div>
                <span class="menu-text">Adventure</span>
            </a>
            
            <a href="#" class="menu-item" onclick="closeMenu()">
                <div class="menu-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <span class="menu-text">Leaderboard</span>
            </a>

            <a href="#" class="menu-item" onclick="closeMenu()">
                <div class="menu-icon">
                    <i class="fas fa-user"></i>
                </div>
                <span class="menu-text">Profile</span>
            </a>

            <!-- More menu with hover popup -->
            <div class="menu-item-wrapper">
                <a href="#" class="menu-item" onclick="closeMenu()">
                    <div class="menu-icon">
                        <i class="fas fa-ellipsis-h"></i>
                    </div>
                    <span class="menu-text">More</span>
                </a>
                <div class="sidebar-hover-popup more-popup">
                    <div class="popup-header">MORE OPTIONS</div>
                    <div class="sidebar-menu-list">
                        <a href="#" class="sidebar-menu-item">
                            <div class="sidebar-menu-icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <span class="sidebar-menu-text">Settings</span>
                        </a>
                        <a href="#" class="sidebar-menu-item">
                            <div class="sidebar-menu-icon">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <span class="sidebar-menu-text">Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="lesson-header">
            <button class="nav-arrow">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <div class="lesson-title-wrapper">
                <div class="lesson-subtitle">SECTION 1, UNIT 1</div>
                <div class="lesson-title">The HTML Forest</div>
            </div>
            
            <button class="nav-arrow">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="roadmap-container">
            <div class="roadmap">
                <!-- Level 1 - Start -->
                <div class="level-node completed" style="left: 0px; top: 280px;">
                    <i class="fas fa-play-circle"></i>
                </div>
                
                <!-- Level 2 - Lecture -->
                <div class="level-node completed" style="left: 180px; top: 150px;">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                
                <!-- Level 3 - Coding -->
                <div class="level-node completed" style="left: 360px; top: 80px;">
                    <i class="fas fa-code"></i>
                </div>
                
                <!-- Level 4 - Designing -->
                <div class="level-node current" style="left: 540px; top: 180px;">
                    <i class="fas fa-palette"></i>
                </div>
                
                <!-- Level 5 - Coding -->
                <div class="level-node locked" style="left: 720px; top: 300px;">
                    <i class="fas fa-code"></i>
                </div>

                <!-- Level 6 - Designing -->
                <div class="level-node locked" style="left: 900px; top: 200px;">
                    <i class="fas fa-paint-brush"></i>
                </div>

                <!-- Level 7 - Finish -->
                <div class="level-node locked" style="left: 1080px; top: 120px;">
                    <i class="fas fa-flag-checkered"></i>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/dashboard.js"></script>
</body>
</html>