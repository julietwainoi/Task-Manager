<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\TaskController;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Task routes without group prefix
$router->get('/tasks', 'App\Http\Controllers\TaskController@index');           // Get all tasks
$router->get('/tasks/{id}', 'App\Http\Controllers\TaskController@show');       // Get a specific task
$router->post('/tasks', 'App\Http\Controllers\TaskController@store');          // Create a task
$router->put('/tasks/{id}', 'App\Http\Controllers\TaskController@update');     // Update a task
$router->delete('/tasks/{id}', 'App\Http\Controllers\TaskController@destroy'); // Delete a task
