<?php
$routes = [
    '/cars' => ['controller' => 'CarController', 'method' => 'getCars'],
    '/cars/create' => ['controller' => 'CarController', 'method' => 'createCar'],
    '/cars/update' => ['controller' => 'CarController', 'method' => 'updateCar'],
    '/cars/delete' => ['controller' => 'CarController', 'method' => 'deleteCar'],
    '/users' => ['controller' => 'UserController', 'method' => 'getUsers']
];