<?php
require_once __DIR__ . '/Model.php';

class User extends Model
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private string $gender;
    private string $weight;
    private string $height;
    private string $role;

    protected static string $table = "users";

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->role = $data["role"];
        $this->name = $data["name"];
        $this->email = $data["email"];
        $this->gender = $data["gender"];
        $this->weight = $data["weight"];
        $this->height = $data["height"];
        $this->password = $data["password"];
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getWeight(): string
    {
        return $this->weight;
    }

    public function getHeight(): string
    {
        return $this->height;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    // Setters
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function setWeight(string $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    public function setHeight(string $height): self
    {
        $this->height = $height;
        return $this;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    




    public function __toString()
    {
        return $this->id . " | " . $this->name . " | " . $this->email . " | " . $this->gender . " | " . $this->height . " | " . $this->weight;
    }

    public function toArray()
    {
        return ["id" => $this->id, "name" => $this->name, "email" => $this->email, "password" => $this->password, "weight" => $this->weight, "height" => $this->height, "gender" => $this->gender, "role" => $this->role];
    }

}


?>