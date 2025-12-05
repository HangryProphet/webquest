<?php

/**
 * StatsModel - Mock Stats Model (Prototype)
 * 
 * This is a temporary mock model that simulates user statistics using a static array.
 * Handles hearts, streak, and course enrollment data.
 * This will be replaced with actual database operations in the future.
 */
class StatsModel
{
    /**
     * Mock database - Static array to simulate user stats storage
     * Matches the stats API output structure from the frontend analysis
     */
    private static $userStats = [
        1 => [ // User ID 1 (demo user)
            "hearts" => [
                "current" => 10,
                "max" => 10,
                "next_heart_in_seconds" => 7200 // 2 hours
            ],
            "streak" => [
                "current_days" => 3,
                "reset_in_seconds" => 10800, // 3 hours
                "weekly_progress" => [true, true, true, false, false, false, false],
                "target_days" => 30
            ],
            "courses" => [
                [
                    "id" => 1,
                    "name" => "HTML Basics",
                    "icon" => "fa-html5",
                    "enrolled" => true
                ],
                [
                    "id" => 2,
                    "name" => "CSS Styling",
                    "icon" => "fa-css3-alt",
                    "enrolled" => true
                ],
                [
                    "id" => 3,
                    "name" => "JavaScript Fundamentals",
                    "icon" => "fa-js",
                    "enrolled" => false
                ]
            ]
        ]
    ];

    /**
     * Get user statistics by user ID
     * 
     * @param int $userId User's ID
     * @return array|false User stats array if found, false otherwise
     */
    public static function getStatsByUserId(int $userId): array|false
    {
        return self::$userStats[$userId] ?? false;
    }

    /**
     * Update user hearts count
     * 
     * @param int $userId User's ID
     * @param int $hearts New hearts count
     * @return bool True on success, false if user not found
     */
    public static function updateHearts(int $userId, int $hearts): bool
    {
        if (!isset(self::$userStats[$userId])) {
            return false;
        }

        $hearts = max(0, min($hearts, self::$userStats[$userId]['hearts']['max']));
        self::$userStats[$userId]['hearts']['current'] = $hearts;

        return true;
    }

    /**
     * Decrease user hearts by one (for wrong answers/skips)
     * 
     * @param int $userId User's ID
     * @return bool True on success, false if user not found or no hearts left
     */
    public static function decreaseHearts(int $userId): bool
    {
        if (!isset(self::$userStats[$userId])) {
            return false;
        }

        if (self::$userStats[$userId]['hearts']['current'] <= 0) {
            return false;
        }

        self::$userStats[$userId]['hearts']['current']--;

        return true;
    }

    /**
     * Update user streak
     * 
     * @param int $userId User's ID
     * @param int $days New streak days count
     * @return bool True on success, false if user not found
     */
    public static function updateStreak(int $userId, int $days): bool
    {
        if (!isset(self::$userStats[$userId])) {
            return false;
        }

        self::$userStats[$userId]['streak']['current_days'] = max(0, $days);

        return true;
    }

    /**
     * Update weekly progress for streak tracking
     * 
     * @param int $userId User's ID
     * @param array $weeklyProgress Array of 7 booleans representing each day
     * @return bool True on success, false if user not found
     */
    public static function updateWeeklyProgress(int $userId, array $weeklyProgress): bool
    {
        if (!isset(self::$userStats[$userId]) || count($weeklyProgress) !== 7) {
            return false;
        }

        self::$userStats[$userId]['streak']['weekly_progress'] = $weeklyProgress;

        return true;
    }

    /**
     * Get all user stats (for debugging purposes)
     * 
     * @return array All user stats in the mock database
     */
    public static function getAllStats(): array
    {
        return self::$userStats;
    }

    /**
     * Reset mock database to initial state (useful for testing)
     * 
     * @return void
     */
    public static function resetDatabase(): void
    {
        self::$userStats = [
            1 => [
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
                    [
                        "id" => 1,
                        "name" => "HTML Basics",
                        "icon" => "fa-html5",
                        "enrolled" => true
                    ],
                    [
                        "id" => 2,
                        "name" => "CSS Styling",
                        "icon" => "fa-css3-alt",
                        "enrolled" => true
                    ],
                    [
                        "id" => 3,
                        "name" => "JavaScript Fundamentals",
                        "icon" => "fa-js",
                        "enrolled" => false
                    ]
                ]
            ]
        ];
    }
}
