<?php

use Mcdcu\Projects\controllers\location\locationsController;

$router->get('/location', [locationsController::class, 'index']);
$router->post('/location/create', [locationsController::class, 'create']);
$router->get('/location/update', [locationsController::class, 'update']);
$router->post('/location/update', [locationsController::class, 'update']);
$router->post('/location/delete', [locationsController::class, 'delete']);
$router->get('/location/search', [locationsController::class, 'search']);

