<?php

use Mcdcu\Projects\controllers\Inventory\InventoryCategoriesController;

$router->get('/categories', [InventoryCategoriesController::class, 'index']);
$router->post('/category/create', [InventoryCategoriesController::class, 'create']);
$router->get('/category/update', [InventoryCategoriesController::class, 'update']);
$router->post('/category/update', [InventoryCategoriesController::class, 'update']);
$router->post('/category/delete', [InventoryCategoriesController::class, 'delete']);
$router->get('/category/search', [InventoryCategoriesController::class, 'search']);