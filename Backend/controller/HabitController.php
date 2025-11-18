<?php
require_once __DIR__ . '/../services/HabitService.php';
require_once __DIR__ . '/../services/ResponseService.php';

class HabitController
{
    private HabitService $habitService;

    public function __construct(mysqli $connection)
    {
        $this->habitService = new HabitService($connection);
    }



    //Get all habits for a user
    public function getByUser()
    {
        $userID = $_GET['user_id'] ?? null;

        if (!$userID) {
            echo ResponseService::response(400, ['error' => 'User ID is required']);
            return;
        }

        $result = $this->habitService->getHabits($userID);
        echo ResponseService::response($result['status'], $result['data']);
    }

    //Get habit by ID
    public function getById()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo ResponseService::response(400, ['error' => 'Habit ID is required']);
            return;
        }

        $result = $this->habitService->getHabitById($id);
        echo ResponseService::response($result['status'], $result['data']);
    }

    //Create a new habit
    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['user_id'], $data['name'])) {
            echo ResponseService::response(400, ['error' => 'Missing required fields']);
            return;
        }

        $result = $this->habitService->createHabit($data);
        echo ResponseService::response($result['status'], $result['data']);
    }

    //Update a habit
    public function update()
    {
        $id = $_GET['id'] ?? null;
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$id) {
            echo ResponseService::response(400, ['error' => 'Habit ID is required']);
            return;
        }

        if (!$data) {
            echo ResponseService::response(400, ['error' => 'No data provided']);
            return;
        }

        $result = $this->habitService->updateHabit($id, $data);
        echo ResponseService::response($result['status'], $result['data']);
    }

    //Delete a habit
    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo ResponseService::response(400, ['error' => 'Habit ID is required']);
            return;
        }

        $result = $this->habitService->deleteHabit($id);
        echo ResponseService::response($result['status'], $result['data']);
    }
}
?>