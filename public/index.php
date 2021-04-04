<?php

/**
 * Composer
 */
require '../vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('admin', ['controller' => 'Admin', 'action' => 'index']);

$router->add('get_tasks', ['controller' => 'Task', 'action' => 'get']);
$router->add('add_task', ['controller' => 'Task', 'action' => 'add']);
$router->add('update_task', ['controller' => 'Task', 'action' => 'update']);

$router->dispatch($_SERVER['QUERY_STRING']);
