<?php

use Mcdcu\Projects\controllers\Inventory\InventoryCategoriesController;

$router->get('/categories', [InventoryCategoriesController::class, 'index']);
$router->post('/category/create', [InventoryCategoriesController::class, 'create']);
$router->post('/category/update/{id}', [InventoryCategoriesController::class, 'update']);
$router->delete('/category/delete/{id}', [InventoryCategoriesController::class, 'delete']);
$router->get('/category/search', [InventoryCategoriesController::class, 'search']);
$router->get('/category/list', [InventoryCategoriesController::class, 'list']);