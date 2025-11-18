<?php
require_once __DIR__ . '/Model.php';

class Entry extends Model
{
    private int $id;
    private int $user_id;
    private ?string $entry_date;
    private ?string $free_text;
    private ?string $parsed_json;
    private ?string $rules;
    private ?string $created_at;

    protected static string $table = "entries";

    public function __construct(array $data = [])
    {
        $this->id = $data["id"] ?? 0;
        $this->user_id = $data["user_id"] ?? 0;
        $this->entry_date = $data["entry_date"] ?? null;
        $this->free_text = $data["free_text"] ?? null;
        $this->parsed_json = $data["parsed_json"] ?? null;
        $this->rules = $data["rules"] ?? null;
        $this->created_at = $data["created_at"] ?? null;
    }

    
    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->user_id; }
    public function getEntryDate(): ?string { return $this->entry_date; }
    public function getFreeText(): ?string { return $this->free_text; }
    public function getParsedJson(): ?string { return $this->parsed_json; }
    public function getRules(): ?string { return $this->rules; }
    public function getCreatedAt(): ?string { return $this->created_at; }

    
    public function setId(int $id): self { $this->id = $id; return $this; }
    public function setUserId(int $user_id): self { $this->user_id = $user_id; return $this; }
    public function setEntryDate(string $entry_date): self { $this->entry_date = $entry_date; return $this; }
    public function setFreeText(?string $free_text): self { $this->free_text = $free_text; return $this; }
    public function setParsedJson(?string $parsed_json): self { $this->parsed_json = $parsed_json; return $this; }
    public function setRules(?string $rules): self { $this->rules = $rules; return $this; }
    public function setCreatedAt(?string $created_at): self { $this->created_at = $created_at; return $this; }

   
    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "entry_date" => $this->entry_date,
            "free_text" => $this->free_text,
            "parsed_json" => $this->parsed_json,
            "rules" => $this->rules,
            "created_at" => $this->created_at
        ];
    }

    public function __toString()
    {
        return $this->id . " | User: " . $this->user_id . " | Date: " . $this->entry_date;
    }
}
?>
