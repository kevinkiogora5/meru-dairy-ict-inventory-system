<?php

namespace Mcdcu\Projects\controllers\Inventory;

use Mcdcu\Projects\services\Inventory\InventoryCategoriesService;
use Mcdcu\Projects\services\Inventory\InventoryItemService;
use sigawa\mvccore\Application;
use sigawa\mvccore\Request;
use sigawa\mvccore\Response;
use sigawa\mvccore\Controller;
use sigawa\mvccore\exception\ValidationException;
use sigawa\mvccore\middlewares\AuthMiddleware;
use sigawa\mvccore\middlewares\NavigationMiddleware;

class InventoryItemController extends Controller
{
       protected InventoryItemService $service;
       protected InventoryCategoriesService $services;

    public function __construct()
    {
        $this->service = new InventoryItemService();
        $this->services = new InventoryCategoriesService();
         $this->registerMiddleware(new NavigationMiddleware([
            '/index'
        ],'/login'));
         $this->registerMiddleware(new AuthMiddleware());
    }

    public function index()
    {
        $categories = $this->services->getAll();
        $items = $this->service->getAll();
        $this->setLayout("admin");
        return $this->render('goods', ['categories' => $categories, 'items' => $items]);
    }
    public function create(Request $request, Response $response)
    {
        if ($request->isPost()) {
            $data = $request->getBody();
            try {
                $user = Application::$app->user;
                if (!$user) {
                    throw new ValidationException(['user' => 'User must be logged in to create an inventory item.']);
                }
                $data['created_by'] = $user->id;

                $item = $this->service->create($data);
                return $response->json(['message' =>'Item created successfully.','data' => $item]);
            } catch (ValidationException $th) {
                //throw $th;
                return $response->json(['error' => $th->errors], 400);
            }
           
        }

        return $response->json(['error' => 'Invalid request method.'],400);
    }

    public function update(Request $request, Response $response, $id)
    {
        if ($request->isPut()|| $request->isPost()) {
            $data = $request->getBody();
            try {
                $item = $this->service->update((int)$id, $data);
                return $response->json(['message' => 'Item updated successfully.', 'data' => $item]);
            } catch (ValidationException $th) {
                return $response->json(['error' => $th->errors], 400);
            }
        }
          return $response->json(['error' => 'Invalid request method.'],400);
    }

    public function delete(Request $request, Response $response, $id)
    {
    if ($request->isDelete()) {
        try {
            $this->service->delete((int)$id);
            return $response->json(['message' => 'Item deleted successfully.']);
        } catch (ValidationException $th) {
            return $response->json(['error' => $th->errors], 400);
        }
    }
     return $response->json(['error' => 'Invalid request method.'],400);
    }

    public function search(Request $request)
    {
        $term = $request->getParam('term');
        $items = $this->service->search($term, ['name', 'description', 'brand', 'serial_number']);
        return $this->render('inventory_items/index', ['items' => $items]);
    }

}