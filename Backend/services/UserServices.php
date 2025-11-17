<?php
require_once( __DIR__ . "/../models/User.php");

class UserService
{

    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }
    public function getUsers($id)
    {
        if ($id) {
            $user = User::find($this->connection, $id);
            if ($user) {
                return ['status' => 200, 'data' => $user->toArray()];
            }
            return ['status' => 404, 'data' => ['error' => 'user not found']];
        }

        $users = User::findAll($this->connection);
        $data = [];
        foreach ($users as $user) {
            $data[] = $user->toArray();
        }
        return ['status' => 200, 'data' => $data];
    }
    public function getUserByEmail($email,$password){
        if ($email && $password) {
            $user = User::getUserByEmail($this->connection, $email, $password);
            if ($user) {
                return ['status' => 200, 'data' => $user];
            }
            return ['status' => 404, 'data' => ['error' => 'user not found']];
        }
    }

    public function createuser(array $data): array
    {
        $requiredFields = ['name', 'email', 'password','height','weight','gender'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                return [
                    'status' => 400,
                    'data' => ['error' => "Missing or empty required field: {$field}"]
                ];
            }
        }
        $hashed_password = password_hash($data["password"], PASSWORD_DEFAULT);
        $data["password"] = $hashed_password;

        $userId = User::create($this->connection, $data);
        if($userId == "Duplicate"){
            return [
                'status' => 500,
                'data' => [
                    'message' => 'Duplicated Email'
                ]
            ];
        }
        if ($userId) {
            return [
                'status' => 201,
                'data' => [
                    'message' => 'user created successfully',
                    'id' => $userId
                ]
            ];
        }

        return ['status' => 500, 'data' => ['error' => 'Failed to create user']];
    }

    public function updateuser(int $id, array $data)
    {
        //if no user return
        $user = User::find($this->connection, $id,"id");
        if (!$user) return ['status' => 404, 'data' => ['error' => 'user not found']];
        
        //if no data return
        if (empty($data)) return ['status' => 400, 'data' => ['error' => 'No data provided for update']];
        

        //duplicate email return
        $result = User::update($this->connection, $id, $data,"id");
        if($result=="Duplicate") return ['status' => 500, 'data' => ['message' => 'Email already in use']];
        
        //good data return
        if ($result)  return ['status' => 200, 'data' => ['message' => 'user updated successfully']];
        

        return ['status' => 500, 'data' => ['error' => 'Failed to update user']];
    }

    public function deleteuser(int $id)
    {
        $user = User::find($this->connection, $id,"id");
        if (!$user) {
            return ['status' => 404, 'data' => ['error' => 'user not found']];
        }

        $result = User::deleteById($id, $this->connection,"id");
        if ($result) {
            return ['status' => 200, 'data' => ['message' => 'user deleted successfully']];
        }

        return ['status' => 500, 'data' => ['error' => 'Failed to delete user']];
    }

}
?>