<?php

require_once __DIR__ . '/Model.php';

class Habit extends Model
{
    private int $id;
    private int $user_id;
    private string $name;
    private $target_value;
    private ?string $unit;
    private int $is_predefined; // 0 or 1
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

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->user_id; }
    public function getName(): string { return $this->name; }
    public function getTargetValue() { return $this->target_value; }
    public function getUnit(): ?string { return $this->unit; }
    public function getIsPredefined(): int { return $this->is_predefined; }
    public function getRules(): ?string { return $this->rules; }
    public function isActive(): bool { return $this->is_active; }
    public function getCreatedAt(): string { return $this->created_at; }

    public function setId(int $id): self { $this->id = $id; return $this; }
    public function setUserId(int $user_id): self { $this->user_id = $user_id; return $this; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function setTargetValue($value): self { $this->target_value = $value; return $this; }
    public function setUnit(?string $unit): self { $this->unit = $unit; return $this; }
    public function setIsPredefined(int $is_predefined): self { $this->is_predefined = $is_predefined; return $this; }
    public function setRules(?string $rules): self { $this->rules = $rules; return $this; }
    public function setIsActive(bool $active): self { $this->is_active = $active; return $this; }
    public function setCreatedAt(string $created_at): self { $this->created_at = $created_at; return $this; }

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
}
?>