<?php

require_once __DIR__ . '/Model.php';

class Habit extends Model
{
    private int $id;
    private int $user_id;
    private string $name;
    private $target_value;
    private ?string $unit;
    private int $is_predefined; 
    private ?string $rules;
    private bool $is_active;
    private string $created_at;

    protected static string $table = "habits";

    public function __construct(array $data = [])
    {
        $this->id = $data["id"];
        $this->user_id = $data["user_id"];
        $this->name = $data["name"];
        $this->target_value = $data["target_value"];
        $this->unit = $data["unit"];
        $this->is_predefined = isset($data["is_predefined"]) ? (int)$data["is_predefined"] : 0;
        $this->rules = $data["rules"] ?? null;
        $this->is_active = (bool)$data["is_active"];
        $this->created_at = $data["created_at"];
    }

    public function __toString()
    {
        return $this->id . " | " . $this->name . " | " . $this->user_id;
    }

    public function toArray()
    {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "name" => $this->name,
            "target_value" => $this->target_value,
            "unit" => $this->unit,
            "is_predefined" => $this->is_predefined,
            "rules" => $this->rules,
            "is_active" => $this->is_active,
            "created_at" => $this->created_at
        ];
    }

    public static function findByEntry(mysqli $connection, int $entryId): array
    {
        $stmt = $connection->prepare("SELECT * FROM habit_entries WHERE entry_id = ?");
        $stmt->bind_param("i", $entryId);
        $stmt->execute();
        $result = $stmt->get_result();
        $habits = [];
        while ($row = $result->fetch_assoc()) {
            $habits[] = $row;
        }
        $stmt->close();
        return $habits;
    }

    public static function updateOrCreate(mysqli $connection, int $entryId, int $habitId, $value, int $userId): void
    {
        $habitName = "Habit $habitId";

        $stmt = $connection->prepare("SELECT id FROM habit_entries WHERE entry_id = ? AND habit_name = ?");
        $stmt->bind_param("is", $entryId, $habitName);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
            $stmtUpdate = $connection->prepare("UPDATE habit_entries SET value = ?, user_id = ? WHERE id = ?");
            $stmtUpdate->bind_param("dii", $value, $userId, $row['id']);
            $stmtUpdate->execute();
            $stmtUpdate->close();
        } else {
            $stmtInsert = $connection->prepare("INSERT INTO habit_entries (entry_id, habit_name, value, user_id) VALUES (?, ?, ?, ?)");
            $stmtInsert->bind_param("isdi", $entryId, $habitName, $value, $userId);
            $stmtInsert->execute();
            $stmtInsert->close();
        }
    }
}
?>
