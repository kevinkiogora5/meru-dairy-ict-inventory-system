<?php

namespace Mcdcu\Projects\controllers\location;

use Mcdcu\Projects\services\location\locationsService;
use sigawa\mvccore\Application;
use sigawa\mvccore\Request;
use sigawa\mvccore\Response;
use sigawa\mvccore\Controller;
use sigawa\mvccore\exception\ValidationException;
use sigawa\mvccore\middlewares\AuthMiddleware;
use sigawa\mvccore\middlewares\NavigationMiddleware;

class locationsController extends Controller
{
   protected locationsService $service;

    public function __construct()
    {
        $this->service = new locationsService();
         $this->registerMiddleware(new NavigationMiddleware([
            '/index'
        ],'/login'));
         $this->registerMiddleware(new AuthMiddleware());
    }

    public function index()
    {
        $locations = $this->service->getAll();
        $this->setLayout("admin");
        return $this->render('location', ['locations' => $locations]);
    }

    public function create(Request $request, Response $response)
    {
        if ($request->isPost()) {
            $data = $request->getBody();
            try {
                $user = Application::$app->user;
                if (!$user) {
                    throw new ValidationException(['user' => 'User must be logged in to create a location.']);
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
        if ($request->isPost()) {
            $data = $request->getBody();
            try {
                $item = $this->service->update((int)$id, $data);
                return $response->json(['message' => 'location updated successfully.', 'data' => $item]);
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
            return $response->json(['message' => 'Location deleted successfully.']);
        } catch (ValidationException $th) {
            return $response->json(['error' => $th->errors], 400);
        }
    }
     return $response->json(['error' => 'Invalid request method.'],400);
    }
     public function list(Request $request, Response $response)
{
    try {
        $locations = $this->service->getAll(); // Or your own filtering logic

        return $response->json($locations);
    } catch (\Exception $e) {
        return $response->json(['error' => 'Failed to fetch locations.'], 500);
    }
}
}