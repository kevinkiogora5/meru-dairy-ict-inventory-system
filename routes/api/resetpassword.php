<?php

use Mcdcu\Projects\controllers\resetpassword\resetpasswordController;

$router->get('/forgotpassword', [resetpasswordController::class, 'forgotPasswordForm']);
$router->post('/change_password_request', [resetpasswordController::class, 'handleForgotPassword']);