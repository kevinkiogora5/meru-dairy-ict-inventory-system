<?php

use Mcdcu\Projects\controllers\location\locationsController;

$router->get('/location', [locationsController::class, 'index']);
$router->post('/location/create', [locationsController::class, 'create']);
$router->post('/location/update/{id}', [locationsController::class, 'update']);
$router->delete('/location/delete/{id}', [locationsController::class, 'delete']);
$router->get('/location/search', [locationsController::class, 'search']);

