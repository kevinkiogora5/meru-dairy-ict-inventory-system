<?php

namespace Mcdcu\Projects\controllers\Inventory;

use Mcdcu\Projects\services\employees\employeesService;
use Mcdcu\Projects\services\Inventory\InventoryAssignmentService;
use Mcdcu\Projects\services\Inventory\InventoryItemService;
use Mcdcu\Projects\services\location\locationsService;
use sigawa\mvccore\Application;
use sigawa\mvccore\Request;
use sigawa\mvccore\Response;
use sigawa\mvccore\Controller;
use sigawa\mvccore\exception\ValidationException;
use sigawa\mvccore\middlewares\AuthMiddleware;
use sigawa\mvccore\middlewares\NavigationMiddleware;

class InventoryAssignmentController extends Controller
{
    protected InventoryAssignmentService $service;
    protected InventoryItemService $itemService;
    protected employeesService $employeeService;
    protected locationsService $locationService;

    public function __construct()
    {
        $this->service = new InventoryAssignmentService();
        $this->itemService = new InventoryItemService();
        $this->employeeService = new employeesService();
        $this->locationService = new locationsService();
         $this->registerMiddleware(new NavigationMiddleware([
            '/index' 
        ],'/login'));
         $this->registerMiddleware(new AuthMiddleware());
    }

    public function index()
    {
        $items = $this->itemService->getAll();
        $employees = $this->employeeService->getAll();
        $locations = $this->locationService->getAll();
        $assignments = $this->service->getAll();
        $this->setLayout("admin");
        return $this->render('assignment', ['assignments' => $assignments,
        'employees'=> $employees,'locations'=> $locations,'items'=>$items]);
    }
public function getUnassignedItems(Request $request, Response $response)
{
    try {
        $items = $this->itemService->getUnassignedItems(); 
        return $response->json($items);
    } catch (\Exception $e) {
        return $response->json(['error' => $e->getMessage()], 500);
    }
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
    $data['issued_by'] = $user->id;

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
        if ($request->isPost()) {
            $data = $request->getBody();
            try {
                $item = $this->service->update((int)$id, $data);
                return $response->json(['message' => 'Assignment updated successfully.', 'data' => $item]);
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

   public function actionSearch(Request $request, Response $response)
{
    try {
        $result = $this->service->searchAndPaginate($request->getBody());

        // Set the layout
        $this->setLayout("admin");

        // Render the card-based search result view
        return $this->render('search', [
            'assignments' => $result['data'],
            'page' => $result['page'],
            'perPage' => $result['perPage'],
            'total' => $result['total'],
            'lastPage' => $result['lastPage']
        ]);
    } catch (ValidationException $th) {
        return $response->json(['error' => $th->errors], 400);
    } catch (\Exception $e) {
        return $response->json(['error' => $e->getMessage()], 500);
    }
}

}