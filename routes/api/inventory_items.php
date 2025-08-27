<?php

use Mcdcu\Projects\controllers\Inventory\InventoryItemController;


$router->get('/goods', [InventoryItemController::class, 'index']);
$router->post('/item/create', [InventoryItemController::class, 'create']);
$router->post('/item/update/{id}', [InventoryItemController::class, 'update']);
$router->delete('/item/delete/{id}', [InventoryItemController::class, 'delete']);
$router->get('/item/list', [InventoryItemController::class, 'list']);
$router->get('/inventory/report', [InventoryItemController::class, 'report']);


