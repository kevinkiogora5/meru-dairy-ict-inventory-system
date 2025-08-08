<?php

use Mcdcu\Projects\controllers\department\departmentController;

$router->get('/department', [departmentController::class, 'index']);
$router->post('/department/create', [departmentController::class, 'create']);
$router->post('/department/update/{id}', [departmentController::class, 'update']);
$router->delete('/department/delete/{id}', [departmentController::class, 'delete']);
$router->get('/department/list', [departmentController::class, 'list']);
