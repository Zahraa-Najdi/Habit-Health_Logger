<?php

require_once(__DIR__ . "/../models/Entry.php");
require_once(__DIR__ . "/../models/Habit.php");

class EntryService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    // Create a new entry with free-text, parsed JSON, and structured habits
    public function createEntry(array $data, array $habitValues = []): array
    {
        if (empty($data["user_id"]) || empty($data["entry_date"])) {
            return ["status" => 400, "data" => ["error" => "user_id and entry_date are required"]];
        }

        if (Entry::findByUserAndDate($this->connection, $data["user_id"], $data["entry_date"])) {
            return ["status" => 409, "data" => ["error" => "Entry already exists for this date"]];
        }

        $entryId = Entry::create($this->connection, $data);
        if (!$entryId) {
            return ["status" => 500, "data" => ["error" => "Failed to create entry"]];
        }

        foreach ($habitValues as $habitId => $value) {
            Habit::create($this->connection, [
                "entry_id" => $entryId,
                "habit_id" => $habitId,
                "value" => $value
            ]);
        }

        return ["status" => 201, "data" => ["entry_id" => $entryId]];
    }

    // Fetch entries for a user (optionally by date range)
    public function getEntries(int $userId, ?string $startDate = null, ?string $endDate = null): array
    {
        $entries = Entry::findByUser($this->connection, $userId, $startDate, $endDate);
        foreach ($entries as &$entry) {
            $entry["habits"] = Habit::findByEntry($this->connection, $entry["id"]);
        }
        return ["status" => 200, "data" => $entries];
    }

    // Delete an entry and its habit values
    public function deleteEntry(int $entryId): array
    {
        $entry = Entry::find($this->connection, $entryId);
        if (!$entry) {
            return ["status" => 404, "data" => ["error" => "Entry not found"]];
        }

        Habit::deleteByEntry($this->connection, $entryId);
        Entry::delete($this->connection, $entryId);

        return ["status" => 200, "data" => ["message" => "Entry deleted successfully"]];
    }
}
?>