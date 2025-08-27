<?php

use Mcdcu\Projects\controllers\Inventory\InventoryAssignmentController;

$router->get('/assignment', [InventoryAssignmentController::class, 'index']);
$router->post('/assignment/create', [InventoryAssignmentController::class, 'create']);
$router->post('/assignment/update/{id}', [InventoryAssignmentController::class, 'update']);
$router->delete('/assignment/delete/{id}', [InventoryAssignmentController::class, 'delete']);
$router->get('/inventory/unassigned-items', [InventoryAssignmentController::class, 'getUnassignedItems']);
$router->get('/assignment/report', [InventoryAssignmentController::class, 'report']);
$router->get('/assignment/list', [InventoryAssignmentController::class, 'list']);

