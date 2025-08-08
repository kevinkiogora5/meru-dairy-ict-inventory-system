<?php

use Mcdcu\Projects\controllers\users\usersController;

$router->get('/', [usersController::class, 'index']);
$router->get('/login', [usersController::class, 'index']);
$router->post('/postlogin', [usersController::class, 'login']);
$router->get('/signup', [usersController::class, 'signup']);
$router->post('/create', [usersController::class, 'createuser']);
$router->post('/logout', [usersController::class, 'logout']);