<?php

require_once(__DIR__ . "/../models/User.php");

class UserService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getUsers($id = null): array
    {
        if ($id) {
            $user = User::find($this->connection, $id);
            if (!$user) {
                return ["status" => 404, "data" => ["error" => "User not found"]];
            }
            return ["status" => 200, "data" => $user->toArray()];
        }

        $users = User::findAll($this->connection);
        return ["status" => 200, "data" => array_map(fn($user) => $user->toArray(), $users)];
    }

    public function getUserById(int $id): array
    {
        return $this->getUsers($id);
    }

    public function getUserByEmail(string $email, string $password): array
    {
        $user = User::findByEmail($this->connection, $email);
        if (!$user || !password_verify($password, $user->getPassword())) {
            return ["status" => 401, "data" => ["error" => "Invalid email or password"]];
        }
        return ["status" => 200, "data" => $user->toArray()];
    }

    public function createUser(array $data): array
    {
        if (empty($data["email"]) || empty($data["password"])) {
            return ["status" => 400, "data" => ["error" => "Email and password are required"]];
        }

        $existingUser = User::findByEmail($this->connection, $data["email"]);
        if ($existingUser) {
            return ["status" => 409, "data" => ["error" => "Email already exists"]];
        }

        $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
        $userId = User::create($this->connection, $data);

        if (!$userId) {
            return ["status" => 500, "data" => ["error" => "Failed to create user"]];
        }

        return ["status" => 201, "data" => User::find($this->connection, $userId)->toArray()];
    }


    public function updateUser(int $id, array $data): array
    {
        $user = User::find($this->connection, $id);
        if (!$user) {
            return ["status" => 404, "data" => ["error" => "User not found"]];
        }

  
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $success = User::update($this->connection, $id, $data);
        if (!$success) {
            return ["status" => 500, "data" => ["error" => "Failed to update user"]];
        }

        $updatedUser = User::find($this->connection, $id);
        return ["status" => 200, "data" => $updatedUser->toArray()];
    }

  
    public function deleteUser(int $id): array
    {
        $user = User::find($this->connection, $id);
        if (!$user) {
            return ["status" => 404, "data" => ["error" => "User not found"]];
        }

        $success = User::deleteByID($this->connection, $id);
        if (!$success) {
            return ["status" => 500, "data" => ["error" => "Failed to delete user"]];
        }

        return ["status" => 200, "data" => ["message" => "User deleted successfully"]];
    }
}
?>
