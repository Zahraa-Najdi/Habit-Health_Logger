<?php
require_once(__DIR__ . "/../services/UserService.php");
require_once(__DIR__ . "/../services/ResponseService.php");
require_once(__DIR__ . "/../Models/User.php");
require_once(__DIR__ . "/../connection/connection.php");

class UserController
{
    private UserService $userService;

    public function __construct(mysqli $connection)
    {
        $this->userService = new UserService($connection);
    }


    public function getUsers()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        $result = $this->userService->getUsers($id);
        echo ResponseService::response($result['status'], $result['data']);
    }

 
    public function getUserById()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo ResponseService::response(400, ['error' => 'User ID is required']);
            return;
        }

        $id = (int)$id;
        $result = $this->userService->getUserById($id);
        echo ResponseService::response($result['status'], $result['data']);
    }


    public function getUserByEmail()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['email'], $data['password'])) {
            echo ResponseService::response(400, ['error' => 'Email and/or password is missing']);
            return;
        }

        $result = $this->userService->getUserByEmail($data['email'], $data['password']);
        echo ResponseService::response($result['status'], $result['data']);
    }


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

        $id = (int)$id;
        $result = $this->userService->updateUser($id, $data);
        echo ResponseService::response($result['status'], $result['data']);
    }

 
    public function deleteUser()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo ResponseService::response(400, ['error' => 'User ID is required']);
            return;
        }

        $id = (int)$id;
        $result = $this->userService->deleteUser($id);
        echo ResponseService::response($result['status'], $result['data']);
    }
}
?>
