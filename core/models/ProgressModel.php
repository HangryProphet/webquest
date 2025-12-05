<?php

/**
 * ProgressModel - Mock Progress Model (Prototype)
 * 
 * This is a temporary mock model that simulates user learning progress using a static array.
 * Handles roadmap levels, completion status, and positioning data.
 * This will be replaced with actual database operations in the future.
 */
class ProgressModel
{
    /**
     * Mock database - Static array to simulate user progress storage
     * Matches the progress API output structure from the frontend analysis
     */
    private static $userProgress = [
        1 => [ // User ID 1 (demo user)
            [
                "level_id" => 1,
                "level_number" => 1,
                "type" => "practice",
                "status" => "completed",
                "position" => ["left" => 0, "top" => 280]
            ],
            [
                "level_id" => 2,
                "level_number" => 2,
                "type" => "lecture",
                "status" => "completed",
                "position" => ["left" => 180, "top" => 150]
            ],
            [
                "level_id" => 3,
                "level_number" => 3,
                "type" => "practice",
                "status" => "completed",
                "position" => ["left" => 360, "top" => 80]
            ],
            [
                "level_id" => 4,
                "level_number" => 4,
                "type" => "practice",
                "status" => "current",
                "position" => ["left" => 540, "top" => 180]
            ],
            [
                "level_id" => 5,
                "level_number" => 5,
                "type" => "practice",
                "status" => "locked",
                "position" => ["left" => 720, "top" => 300]
            ],
            [
                "level_id" => 6,
                "level_number" => 6,
                "type" => "lecture",
                "status" => "locked",
                "position" => ["left" => 900, "top" => 200]
            ],
            [
                "level_id" => 7,
                "level_number" => 7,
                "type" => "practice",
                "status" => "locked",
                "position" => ["left" => 1080, "top" => 120]
            ]
        ]
    ];

    /**
     * Get user learning progress by user ID
     * 
     * @param int $userId User's ID
     * @return array|false User progress array if found, false otherwise
     */
    public static function getProgressByUserId(int $userId): array|false
    {
        return self::$userProgress[$userId] ?? false;
    }

    /**
     * Update level status (e.g., mark as completed)
     * 
     * @param int $userId User's ID
     * @param int $levelId Level ID to update
     * @param string $status New status: 'locked', 'current', or 'completed'
     * @return bool True on success, false if user or level not found
     */
    public static function updateLevelStatus(int $userId, int $levelId, string $status): bool
    {
        if (!isset(self::$userProgress[$userId])) {
            return false;
        }

        $validStatuses = ['locked', 'current', 'completed'];
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        foreach (self::$userProgress[$userId] as &$level) {
            if ($level['level_id'] === $levelId) {
                $level['status'] = $status;
                return true;
            }
        }

        return false;
    }

    /**
     * Get a specific level by ID
     * 
     * @param int $userId User's ID
     * @param int $levelId Level ID to retrieve
     * @return array|false Level data if found, false otherwise
     */
    public static function getLevelById(int $userId, int $levelId): array|false
    {
        if (!isset(self::$userProgress[$userId])) {
            return false;
        }

        foreach (self::$userProgress[$userId] as $level) {
            if ($level['level_id'] === $levelId) {
                return $level;
            }
        }

        return false;
    }

    /**
     * Get current active level (status = 'current')
     * 
     * @param int $userId User's ID
     * @return array|false Current level data if found, false otherwise
     */
    public static function getCurrentLevel(int $userId): array|false
    {
        if (!isset(self::$userProgress[$userId])) {
            return false;
        }

        foreach (self::$userProgress[$userId] as $level) {
            if ($level['status'] === 'current') {
                return $level;
            }
        }

        return false;
    }

    /**
     * Complete current level and unlock next
     * 
     * @param int $userId User's ID
     * @return bool True on success, false on failure
     */
    public static function completeCurrentLevel(int $userId): bool
    {
        if (!isset(self::$userProgress[$userId])) {
            return false;
        }

        $currentIndex = null;
        foreach (self::$userProgress[$userId] as $index => $level) {
            if ($level['status'] === 'current') {
                $currentIndex = $index;
                break;
            }
        }

        if ($currentIndex === null) {
            return false;
        }

        // Mark current as completed
        self::$userProgress[$userId][$currentIndex]['status'] = 'completed';

        // Unlock next level if it exists
        if (isset(self::$userProgress[$userId][$currentIndex + 1])) {
            self::$userProgress[$userId][$currentIndex + 1]['status'] = 'current';
        }

        return true;
    }

    /**
     * Get all user progress data (for debugging purposes)
     * 
     * @return array All user progress in the mock database
     */
    public static function getAllProgress(): array
    {
        return self::$userProgress;
    }

    /**
     * Reset mock database to initial state (useful for testing)
     * 
     * @return void
     */
    public static function resetDatabase(): void
    {
        self::$userProgress = [
            1 => [
                [
                    "level_id" => 1,
                    "level_number" => 1,
                    "type" => "practice",
                    "status" => "completed",
                    "position" => ["left" => 0, "top" => 280]
                ],
                [
                    "level_id" => 2,
                    "level_number" => 2,
                    "type" => "lecture",
                    "status" => "completed",
                    "position" => ["left" => 180, "top" => 150]
                ],
                [
                    "level_id" => 3,
                    "level_number" => 3,
                    "type" => "practice",
                    "status" => "completed",
                    "position" => ["left" => 360, "top" => 80]
                ],
                [
                    "level_id" => 4,
                    "level_number" => 4,
                    "type" => "practice",
                    "status" => "current",
                    "position" => ["left" => 540, "top" => 180]
                ],
                [
                    "level_id" => 5,
                    "level_number" => 5,
                    "type" => "practice",
                    "status" => "locked",
                    "position" => ["left" => 720, "top" => 300]
                ],
                [
                    "level_id" => 6,
                    "level_number" => 6,
                    "type" => "lecture",
                    "status" => "locked",
                    "position" => ["left" => 900, "top" => 200]
                ],
                [
                    "level_id" => 7,
                    "level_number" => 7,
                    "type" => "practice",
                    "status" => "locked",
                    "position" => ["left" => 1080, "top" => 120]
                ]
            ]
        ];
    }
}
