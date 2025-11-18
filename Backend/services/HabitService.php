<?php

require_once(__DIR__ . "/../models/Habit.php");

class HabitService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    // Fetch all habits for a user (active or all)
    public function getHabits(int $userId, bool $onlyActive = true): array
    {
        $conditions = ["user_id" => $userId];
        if ($onlyActive) {
            $conditions["is_active"] = 1;
        }

        $habits = Habit::findAllByConditions($this->connection, $conditions);
        return ["status" => 200, "data" => array_map(fn($habit) => $habit->toArray(), $habits)];
    }

    // Fetch a single habit by ID
    public function getHabitById(int $habitId): array
    {
        $habit = Habit::find($this->connection, $habitId);
        if (!$habit) {
            return ["status" => 404, "data" => ["error" => "Habit not found"]];
        }
        return ["status" => 200, "data" => $habit->toArray()];
    }

    // Create a new habit
    public function createHabit(array $data): array
    {
        // Validate required fields
        if (empty($data["user_id"]) || empty($data["name"])) {
            return ["status" => 400, "data" => ["error" => "user_id and name are required"]];
        }

        $habitId = Habit::create($this->connection, $data);
        if (!$habitId) {
            return ["status" => 500, "data" => ["error" => "Failed to create habit"]];
        }

        return ["status" => 201, "data" => ["habit_id" => $habitId]];
    }

    // Update an existing habit
    public function updateHabit(int $habitId, array $data): array
    {
        $habit = Habit::find($this->connection, $habitId);
        if (!$habit) {
            return ["status" => 404, "data" => ["error" => "Habit not found"]];
        }

        $updated = Habit::update($this->connection, $habitId, $data);
        if (!$updated) {
            return ["status" => 500, "data" => ["error" => "Failed to update habit"]];
        }

        return ["status" => 200, "data" => ["message" => "Habit updated successfully"]];
    }

    // Delete a habit
    public function deleteHabit(int $habitId): array
    {
        $habit = Habit::find($this->connection, $habitId);
        if (!$habit) {
            return ["status" => 404, "data" => ["error" => "Habit not found"]];
        }

        $deleted = Habit::deleteByID($this->connection, $habitId);
        if (!$deleted) {
            return ["status" => 500, "data" => ["error" => "Failed to delete habit"]];
        }

        return ["status" => 200, "data" => ["message" => "Habit deleted successfully"]];
    }

    // Activate or deactivate a habit
    public function toggleHabit(int $habitId, bool $isActive): array
    {
        $habit = Habit::find($this->connection, $habitId);
        if (!$habit) {
            return ["status" => 404, "data" => ["error" => "Habit not found"]];
        }

        $updated = Habit::update($this->connection, $habitId, ["is_active" => $isActive]);
        if (!$updated) {
            return ["status" => 500, "data" => ["error" => "Failed to update habit status"]];
        }

        $status = $isActive ? "activated" : "deactivated";
        return ["status" => 200, "data" => ["message" => "Habit successfully $status"]];
    }
}
?>