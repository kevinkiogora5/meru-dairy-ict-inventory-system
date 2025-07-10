<?php

use Mcdcu\Projects\controllers\department\departmentController;

$router->get('/department', [departmentController::class, 'index']);
$router->post('/department/create', [departmentController::class, 'create']);
$router->post('/department/update{id}', [departmentController::class, 'update']);
$router->post('/department/update', [DepartmentController::class, 'update']);
$router->delete('/department/delete', [DepartmentController::class, 'delete']);
$router->get('/department/search', [DepartmentController::class, 'search']);
