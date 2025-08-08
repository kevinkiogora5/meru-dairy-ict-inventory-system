<?php

namespace Mcdcu\Projects\controllers\returns;

use Mcdcu\Projects\services\employees\employeesService;
use Mcdcu\Projects\services\Inventory\InventoryAssignmentService;
use Mcdcu\Projects\services\Inventory\InventoryItemService;
use Mcdcu\Projects\services\returns\returnService;
use sigawa\mvccore\Application;
use sigawa\mvccore\Request;
use sigawa\mvccore\Response;
use sigawa\mvccore\Controller;
use sigawa\mvccore\exception\ValidationException;
use sigawa\mvccore\middlewares\AuthMiddleware;
use sigawa\mvccore\middlewares\NavigationMiddleware;

class returnController extends Controller
{
    protected returnService $service;
    protected InventoryAssignmentService $inventoryService;
    protected InventoryItemService $itemService;
    protected employeesService $employeeService;

    public function __construct()
    {
        $this->service = new returnService();
        $this->itemService = new InventoryItemService();
        $this->employeeService = new employeesService();
        $this->inventoryService = new InventoryAssignmentService();
         $this->registerMiddleware(new NavigationMiddleware([
            '/index'
        ],'/login'));
         $this->registerMiddleware(new AuthMiddleware());
    }

    public function index()
    {  
        $items = $this->itemService->getAll();
        $employees = $this->employeeService->getEmployeesWithAssignments();
        $returns = $this->service->getAll();
        $this->setLayout("admin");
        return $this->render('returns', ['returns' => $returns, 
         'employees' => $employees, 'items' => $items]);
    }
   public function getAssignmentsByEmployee(Request $request, Response $response)
{
    $employeeId = (int) $request->getParam('employee_id');

    if (!$employeeId) {
        return $response->json([]);
    }

    $assignments = $this->inventoryService->getAssignmentsByEmployee($employeeId);

    return $response->json($assignments);
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
    $data['received_by'] = $user->id;
    $employeeId = $data['employee_id'] ?? null;
$assignmentId = $data['inventory_assignment_id'] ?? null;

if (!$employeeId || !$assignmentId) {
    throw new ValidationException(['employee_id or inventory_assignment_id missing']);
}

// Find the assignment that matches both
$assignment = $this->inventoryService->findAssignment($employeeId, $assignmentId);
if (!$assignment) {
    throw new ValidationException(['No assignment found for this employee and item.']);
}

$data['inventory_assignment_id'] = $assignment->id;
                $item = $this->service->create($data);
                return $response->json(['message' =>'Item ruturned successfully.','data' => $item]);
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
                return $response->json(['message' => 'return updated successfully.', 'data' => $item]);
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
        $returns = $this->service->getAll(); // Or your own filtering logic

        return $response->json($returns);
    } catch (\Exception $e) {
        return $response->json(['error' => 'Failed to fetch returns.'], 500);
    }
}
}