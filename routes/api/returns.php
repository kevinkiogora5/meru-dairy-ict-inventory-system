<?php
use Mcdcu\Projects\controllers\returns\ReturnController   ;

$router->get('/returns', [ReturnController::class, 'index']);
$router->post('/return/create', [ReturnController::class, 'create']);
$router->get('/return/update/{id}', [ReturnController::class, 'update']);
$router->post('/return/update/{id}', [ReturnController::class, 'update']);
$router->delete('/return/delete/{id}', [ReturnController::class, 'delete']);
$router->get('/return/search', [ReturnController::class, 'search']);
