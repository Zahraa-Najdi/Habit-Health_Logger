<?php

require_once(__DIR__ . "/../models/User.php");
require_once(__DIR__ . "/../models/Habit.php");
require_once(__DIR__ . "/../models/Entry.php");

class AdminService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    // Fetch all users (active or all)
    public function getUsers(bool $onlyActive = true): array
    {
        $conditions = [];
        if ($onlyActive) {
            $conditions["is_active"] = 1;
        }

        $users = User::findAllByConditions($this->connection, $conditions);
        return ["status" => 200, "data" => array_map(fn($user) => $user->toArray(), $users)];
    }

    // Deactivate a user account
    public function deactivateUser(int $userId): array
    {
        $user = User::find($this->connection, $userId);
        if (!$user) {
            return ["status" => 404, "data" => ["error" => "User not found"]];
        }

        $updated = User::update($this->connection, $userId, ["is_active" => 0]);
        if (!$updated) {
            return ["status" => 500, "data" => ["error" => "Failed to deactivate user"]];
        }

        return ["status" => 200, "data" => ["message" => "User deactivated successfully"]];
    }

    // Delete a user account
    public function deleteUser(int $userId): array
    {
        $user = User::find($this->connection, $userId);
        if (!$user) {
            return ["status" => 404, "data" => ["error" => "User not found"]];
        }

        $deleted = User::deleteByID($this->connection, $userId);
        if (!$deleted) {
            return ["status" => 500, "data" => ["error" => "Failed to delete user"]];
        }

        return ["status" => 200, "data" => ["message" => "User deleted successfully"]];
    }

    // Fetch statistics (e.g., total users, active users, habits tracked)
    public function getStatistics(): array
    {
        $totalUsers = $this->connection->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()["count"];
        $activeUsers = $this->connection->query("SELECT COUNT(*) as count FROM users WHERE is_active = 1")->fetch_assoc()["count"];
        $totalHabits = $this->connection->query("SELECT COUNT(*) as count FROM habits")->fetch_assoc()["count"];
        $totalEntries = $this->connection->query("SELECT COUNT(*) as count FROM entries")->fetch_assoc()["count"];

        return [
            "status" => 200,
            "data" => [
                "total_users" => $totalUsers,
                "active_users" => $activeUsers,
                "total_habits" => $totalHabits,
                "total_entries" => $totalEntries
            ]
        ];
    }

    // Fetch trends (e.g., most tracked habits)
    public function getTrends(): array
    {
        $query = "
            SELECT name, COUNT(entry_habits.habit_id) as count
            FROM habits
            JOIN entry_habits ON habits.id = entry_habits.habit_id
            GROUP BY entry_habits.habit_id
            ORDER BY count DESC
            LIMIT 5
        ";

        $result = $this->connection->query($query);
        $trends = [];
        while ($row = $result->fetch_assoc()) {
            $trends[] = $row;
        }

        return ["status" => 200, "data" => $trends];
    }
}
?>