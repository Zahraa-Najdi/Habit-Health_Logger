<?php

require_once(__DIR__ . "/../connection/connection.php");
require_once(__DIR__ . "/../services/ResponseService.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$routes = [
    "/users"        => ["controller" => "UserController", "method" => "getUsers"],
    "/users/show"   => ["controller" => "UserController", "method" => "getUserById"],
    "/users/find"   => ["controller" => "UserController", "method" => "getUserByEmail"],
    "/users/create" => ["controller" => "UserController", "method" => "createUser"],
    "/users/update" => ["controller" => "UserController", "method" => "updateUser"],
    "/users/delete" => ["controller" => "UserController", "method" => "deleteUser"],

    "/habits"        => ["controller" => "HabitController", "method" => "getByUser"],
    "/habits/show"   => ["controller" => "HabitController", "method" => "getById"],
    "/habits/create" => ["controller" => "HabitController", "method" => "createHabit"],
    "/habits/update" => ["controller" => "HabitController", "method" => "updateHabit"],
    "/habits/delete" => ["controller" => "HabitController", "method" => "deleteHabit"],

    "/entries"        => ["controller" => "EntriesController", "method" => "getEntries"],
    "/entries/show"   => ["controller" => "EntriesController", "method" => "getEntryById"],
    "/entries/create" => ["controller" => "EntriesController", "method" => "createEntry"],
    "/entries/update" => ["controller" => "EntriesController", "method" => "updateEntry"],
    "/entries/delete" => ["controller" => "EntriesController", "method" => "deleteEntry"],
];


$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = preg_replace('#/+#','/', $path);
$path = preg_replace('#^.*apis\.php#', '', $path);
if ($path === '') $path = '/';

echo "ROUTE MATCHED: $path<br>";


if (isset($routes[$path])) {
    $route = $routes[$path];
    $controllerName = $route["controller"];
    $methodName = $route["method"];

    $controllerFile = __DIR__ . "/../controller/{$controllerName}.php";

    if (!file_exists($controllerFile)) {
        echo ResponseService::response(500, ["error" => "Controller file {$controllerName}.php not found"]);
        exit();
    }

    require_once($controllerFile);

    global $connection;
    $controller = new $controllerName($connection);
    $controller->$methodName();
} else {
    echo ResponseService::response(404, ["error" => "Endpoint not found"]);
}
