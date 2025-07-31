<?php

namespace Mcdcu\Projects\controllers\employees;

use Mcdcu\Projects\services\department\departmentService;
use Mcdcu\Projects\services\employees\employeesService;
use sigawa\mvccore\Application;
use sigawa\mvccore\Request;
use sigawa\mvccore\Response;
use sigawa\mvccore\Controller;
use sigawa\mvccore\exception\ValidationException;
use sigawa\mvccore\middlewares\AuthMiddleware;
use sigawa\mvccore\middlewares\NavigationMiddleware;

class employeesController extends Controller
{
        protected employeesService $employeeService;
        protected departmentService $departmentService;

    public function __construct()
    {
        $this->employeeService = new employeesService();
        $this->departmentService = new departmentService();
        $this->registerMiddleware(new NavigationMiddleware([
            '/index'
        ],'/login'));
         $this->registerMiddleware(new AuthMiddleware());
    }

    public function index(): string
    {
        $departments = $this->departmentService->getAll();
        $employees = $this->employeeService->getAll();
        $this->setLayout("admin");
        return $this->render('employees', ['employees' => $employees, 
        'departments' => $departments]);
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
    $data['user_id'] = $user->id;
                $item = $this->employeeService->create($data);
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
                $item = $this->employeeService->update((int)$id, $data);
                return $response->json(['message' => 'Employee updated successfully.', 'data' => $item]);
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
            $this->employeeService->delete((int)$id);
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
        $items = $this->employeeService->search($term, ['name', 'description', 'brand', 'serial_number']);
        return $this->render('inventory_items/index', ['items' => $items]);
    }

}