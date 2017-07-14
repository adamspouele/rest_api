<?php

require 'autoload.php';

$db = \App\Database\Database::getInstance();
$db->connect('localhost', 'root', 'root', '');
$db->init();

$router = new App\Router\Router($_GET['word']);

$router->get('/', "Index#show");

$router->get('/api/v1/user/:id', "User#getOne");

$router->post('/api/v1/user/add', "User#add");

$router->get('/api/v1/users', "User#getAll");

$router->post('/api/v1/user/delete/:id', "User#delete");

$router->get('/api/v1/user/:userId/tasks', "Task#getTasksFromUser");

$router->post('/api/v1/task/add', "Task#add");

$router->get('/api/v1/task/:id', "Task#getOne");

$router->post('/api/v1/task/delete/:id', "Task#delete");

$router->run();

