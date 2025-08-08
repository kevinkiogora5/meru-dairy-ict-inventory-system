<?php

namespace Mcdcu\Projects\controllers\Inventory;

use Mcdcu\Projects\services\Inventory\InventoryCategoriesService;
use sigawa\mvccore\Application;
use sigawa\mvccore\Request;
use sigawa\mvccore\Response;
use sigawa\mvccore\Controller;
use sigawa\mvccore\exception\ValidationException;
use sigawa\mvccore\middlewares\AuthMiddleware;
use sigawa\mvccore\middlewares\NavigationMiddleware;

class InventoryCategoriesController extends Controller
{
    protected InventoryCategoriesService $service;

    public function __construct()
    {
        $this->service = new InventoryCategoriesService();
         $this->registerMiddleware(new NavigationMiddleware([
            '/index'
        ],'/login'));
         $this->registerMiddleware(new AuthMiddleware());
    }

    public function index()
    {
        $categories = $this->service->getAll();
        $this->setLayout("admin");
        return $this->render('categories', ['categories' => $categories]);
    }
    public function create(Request $request, Response $response)
    {
        if ($request->isPost()) {
            $data = $request->getBody();
            try { 
                $user = Application::$app->user;
    if (!$user) {
        throw new ValidationException(['user' => 'User must be logged in to create a department.']);
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
        if ($request->isPut() || $request->isPost()) {
            $data = $request->getBody();
            try {
                $item = $this->service->update((int)$id, $data);
                return $response->json(['message' => 'Category updated successfully.', 'data' => $item]);
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
    public function list(Request $request, Response $response)
{
    try {
        $categories = $this->service->getAll(); // Or your own filtering logic

        return $response->json($categories);
    } catch (\Exception $e) {
        return $response->json(['error' => 'Failed to fetch categories.'], 500);
    }
}

}