<?php

use Mcdcu\Projects\controllers\Inventory\InventoryItemController;



$router->get('/goods', [InventoryItemController::class, 'index']);
$router->post('/item/create', [InventoryItemController::class, 'create']);
$router->get('/item/update', [InventoryItemController::class, 'update']);
$router->put('/item/update', [InventoryItemController::class, 'update']);
$router->get('/item/delete', [InventoryItemController::class, 'delete']);
$router->get('/item/search', [InventoryItemController::class, 'search']);
