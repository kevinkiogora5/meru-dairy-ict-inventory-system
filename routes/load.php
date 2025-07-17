<?php

use sigawa\mvccore\Router;

function loadRoutesFromDirectory($directory, Router $router)
{
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
    foreach ($files as $file) {
        if($file->isFile() && $file->getExtension() ==='php' && $file->getBasename() !== 'load.php') {
            require $file->getPathname();
        }
    }


}
// Expose $router to all roue files -php 
$router = $GLOBALS['app']->router?? throw new \Exception('Route not available');
loadRoutesFromDirectory(__DIR__, $router);
