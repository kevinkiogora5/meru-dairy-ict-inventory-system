<?php 

use Mcdcu\Projects\controllers\employees\employeesController;

$router->get('/employees', [employeesController::class, 'index']);
$router->post('/employee/create', [employeesController::class, 'create']);
$router->post('/employee/update/{id}', [employeesController::class, 'update']);
$router->delete('/employee/delete/{id}', [employeesController::class, 'delete']);
$router->get('/employee/search', [employeesController::class, 'search']);
