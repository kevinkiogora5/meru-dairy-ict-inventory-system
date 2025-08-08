<?php

use Mcdcu\Projects\controllers\dashboard\dashboardController;

$router->get('/dashboard', [dashboardController::class, 'index']);