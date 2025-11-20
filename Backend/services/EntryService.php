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

    public function createEntry(array $data, array $habitValues = []): array
    {
    if (empty($data["user_id"]) || empty($data["entry_date"])) {
        return ["status" => 400, "data" => ["error" => "user_id and entry_date are required"]];
    }

    if (Entry::findByUserAndDate($this->connection, $data["user_id"], $data["entry_date"])) {
        return ["status" => 409, "data" => ["error" => "Entry already exists for this date"]];
    }

    if (isset($data['parsed_json']) && is_array($data['parsed_json'])) {
        $data['parsed_json'] = json_encode($data['parsed_json'], JSON_UNESCAPED_UNICODE);
    }

    $entryId = Entry::create($this->connection, $data);
    if (!$entryId) {
        return ["status" => 500, "data" => ["error" => "Failed to create entry"]];
    }

    foreach ($habitValues as $habitId => $value) {
        Habit::updateOrCreate(
            $this->connection,
            $entryId,
            $habitId,
            $value,
            $data["user_id"]
        );
    }

    return ["status" => 201, "data" => ["entry_id" => $entryId]];
    }


    public function getEntries(?int $userId = null, ?string $startDate = null, ?string $endDate = null): array
    {
        $entries = $userId 
            ? Entry::findByUser($this->connection, $userId, $startDate, $endDate)
            : Entry::findAll($this->connection, $startDate, $endDate);

        $entriesArray = array_map(function($entry) {
            $arr = $entry->toArray();
            $arr["habits"] = Habit::findByEntry($this->connection, $entry->getId());
            return $arr;
        }, $entries);

        return ["status" => 200, "data" => $entriesArray];
    }

    public function getEntryById(int $entryId): array
    {
        $entry = Entry::find($this->connection, $entryId);
        if (!$entry) {
            return ["status" => 404, "data" => ["error" => "Entry not found"]];
        }

        $entryArray = $entry->toArray();
        $entryArray["habits"] = Habit::findByEntry($this->connection, $entry->getId());

        return ["status" => 200, "data" => $entryArray];
    }

    public function updateEntry(int $entryId, array $data): array
    {
        $entry = Entry::find($this->connection, $entryId);
        if (!$entry) {
            return ["status" => 404, "data" => ["error" => "Entry not found"]];
        }

        if (isset($data['parsed_json']) && is_array($data['parsed_json'])) {
            $data['parsed_json'] = json_encode($data['parsed_json']);
        }

        $habits = $data['habits'] ?? [];
        unset($data['habits']);

        Entry::update($this->connection, $entryId, $data);

        $userId = $entry->getUserId(); 
        foreach ($habits as $habitId => $value) {
            Habit::updateOrCreate($this->connection, $entryId, $habitId, $value, $userId);
        }

        return ["status" => 200, "data" => ["message" => "Entry updated successfully"]];
    }

    public function deleteEntry(int $entryId): array
    {
        $entry = Entry::find($this->connection, $entryId);
        if (!$entry) {
            return ["status" => 404, "data" => ["error" => "Entry not found"]];
        }

        Entry::delete($this->connection, $entryId);

        return ["status" => 200, "data" => ["message" => "Entry deleted successfully"]];
    }

}
?>
