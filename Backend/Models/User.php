<?php

require_once __DIR__ . '/Model.php';

class User extends Model
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private string $role;
    private ?string $rules;
    private bool $is_active;
    private string $created_at;

    protected static string $table = "users";

    public function __construct(array $data = [])
    {
        $this->id = $data["id"] ?? 0;
        $this->name = $data["name"] ?? "";
        $this->email = $data["email"] ?? "";
        $this->password = $data["password"] ?? "";
        $this->role = $data["role"] ?? "user";
        $this->rules = $data["rules"] ?? null;
        $this->is_active = isset($data["is_active"]) ? (bool)$data["is_active"] : true;
        $this->created_at = $data["created_at"] ?? "";
    }

    public function getID(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getRole(): string { return $this->role; }
    public function getRules(): ?string { return $this->rules; }
    public function isActive(): bool { return $this->is_active; }
    public function getCreatedAt(): string { return $this->created_at; }

    public function setID(int $id): self { $this->id = $id; return $this; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function setPassword(string $password): self { $this->password = $password; return $this; }
    public function setRole(string $role): self { $this->role = $role; return $this; }
    public function setRules(?string $rules): self { $this->rules = $rules; return $this; }
    public function setIsActive(bool $is_active): self { $this->is_active = $is_active; return $this; }
    public function setCreatedAt(string $created_at): self { $this->created_at = $created_at; return $this; }

    public function __toString()
    {
        return $this->id . " | " . $this->name . " | " . $this->email;
    }

    public function toArray()
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "password" => $this->password,
            "role" => $this->role,
            "rules" => $this->rules,
            "is_active" => $this->is_active,
            "created_at" => $this->created_at
        ];
    }
}
?>