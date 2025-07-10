<?php 

use Mcdcu\Projects\controllers\employees\employeesController;

$router->get('/employees', [employeesController::class, 'index']);
$router->post('/employee/create', [employeesController::class, 'create']);
$router->get('/employee/update', [employeesController::class, 'update']);
$router->post('/employee/update', [employeesController::class, 'update']);
$router->post('/employee/delete', [employeesController::class, 'delete']);
$router->get('/employee/search', [employeesController::class, 'search']);
