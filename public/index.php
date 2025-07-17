<?php

use Dotenv\Dotenv;
use Mcdcu\Projects\models\users\users;
use sigawa\mvccore\Application;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Method: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


require_once __DIR__.'/../vendor/autoload.php';


$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config= [
    'userClass' => users::class,
    'guestClass'=> users::class,
    'db'=> [
        'dsn'=> $_ENV['DB_DSN'],
        'user'=> $_ENV['DB_USER'],
        'password'=> $_ENV['DB_PASSWORD'],
    ],
];

try {
    //init the app.
    $app = new Application(dirname(__DIR__), $config);
    // include routes/load.php
    require_once __DIR__.'/../routes/load.php';
    
    $app->run();
} catch (\Exception $th) {
    return json_encode(['error'=> $th->getMessage()]);
}