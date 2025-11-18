<?php
require_once(__DIR__ . "/../services/UserService.php");
require_once(__DIR__ . "/../services/ResponseService.php");

class UserController
{
    private UserService $userService;

    public function __construct(mysqli $connection)
    {
        $this->userService = new UserService($connection);
    }

    //Get all users or a specific user by ID
    public function getUsers()
    {
        $id = $_GET['id'] ?? null;

        $result = $this->userService->getUsers($id);
        echo ResponseService::response($result['status'], $result['data']);
    }
    
    //Get user by ID
    public function getUserById()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo ResponseService::response(400, ['error' => 'User ID is required']);
            return;
        }

        $result = $this->userService->getUserById($id);
        echo ResponseService::response($result['status'], $result['data']);
    }

    // Get user by email and password
    public function getUserByEmail()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['email'], $data['password'])) {
            echo ResponseService::response(400, ['error' => 'Email and password are required']);
            return;
        }

        $result = $this->userService->getUserByEmail($data['email'], $data['password']);
        echo ResponseService::response($result['status'], $result['data']);
    }

    //Create a new user
    public function createUser()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['email'], $data['password'])) {
            echo ResponseService::response(400, ['error' => 'Email and password are required']);
            return;
        }

        $result = $this->userService->createUser($data);
        echo ResponseService::response($result['status'], $result['data']);
    }

    //Update a user
    public function updateUser()
    {
        $id = $_GET['id'] ?? null;
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$id) {
            echo ResponseService::response(400, ['error' => 'User ID is required']);
            return;
        }

        if (!$data) {
            echo ResponseService::response(400, ['error' => 'No data provided']);
            return;
        }

        $result = $this->userService->updateUser($id, $data);
        echo ResponseService::response($result['status'], $result['data']);
    }

    //Delete a user
    public function deleteUser()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo ResponseService::response(400, ['error' => 'User ID is required']);
            return;
        }

        $result = $this->userService->deleteUser($id);
        echo ResponseService::response($result['status'], $result['data']);
    }
}
?>