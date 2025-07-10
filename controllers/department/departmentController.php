<?php

namespace Mcdcu\Projects\controllers\department;

use Mcdcu\Projects\services\department\departmentService;
use sigawa\mvccore\Application;
use sigawa\mvccore\Request;
use sigawa\mvccore\Response;
use sigawa\mvccore\Controller;
use sigawa\mvccore\exception\ValidationException;

class departmentController extends Controller
{
    protected departmentService $departmentService;

    public function __construct()
    {
        $this->departmentService = new departmentService();
    }
    public function index(Request $request, Response $response) {
        $this->setLayout("admin");
        return $this->render('department',[
            'departments' => $this->departmentService->getAll(),
        ]);
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
                $item = $this->departmentService->create($data);
                return $response->json(['message' =>'Item created successfully.','data' => $item]);
            } catch (ValidationException $th) {
                //throw $th;
                return $response->json(['error' => $th->errors], 400);
            }
           
        }

        return $response->json(['error' => 'Invalid request method.'],400);
    }

    public function update(Request $request, Response $response)
    {
        if ($request->isPut()) {
            $data = $request->getBody();
            try {
                $id = $request->getParam('id');
                $item = $this->departmentService->update((int)$id, $data);
                return $response->json(['message' => 'Item updated successfully.', 'data' => $item]);
            } catch (ValidationException $th) {
                return $response->json(['error' => $th->errors], 400);
            }
        }
          return $response->json(['error' => 'Invalid request method.'],400);
    }

    public function delete(Request $request, Response $response)
    {
    if ($request->isDelete()) {
        try {
            $id = $request->getParam('id');
            $this->departmentService->delete((int)$id);
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
        $items = $this->departmentService->search($term, ['name', 'description', 'brand', 'serial_number']);
        return $this->render('inventory_items/index', ['items' => $items]);
    }


}