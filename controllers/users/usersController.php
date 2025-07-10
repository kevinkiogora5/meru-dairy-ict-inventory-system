<?php

namespace Mcdcu\Projects\controllers\users;

use Mcdcu\Projects\models\Auth\Auth;
use Mcdcu\Projects\services\users\usersService;
use sigawa\mvccore\Application;
use sigawa\mvccore\AuthProvider;
use sigawa\mvccore\Request;
use sigawa\mvccore\Response;
use sigawa\mvccore\Controller;
use sigawa\mvccore\exception\ValidationException;

class usersController extends Controller
{
    protected usersService $service;

    public function __construct()
    {
        $this->service = new usersService();
    }
     public function index(Request $request, Response $response)
    {

        $this->setLayout('login');

        return $this->render("login", [

        ]);
    }
     public function signup(Request $request, Response $response)
    {

        $this->setLayout('login');

        return $this->render("signup", [

        ]);
    }
 public function createuser(Request $request, Response $response)
{
    if (!$request->isPost()) {
        return $response->json(['error' => 'Invalid request method.'], 400);
    }
    $data = $request->getBody();
    try {
        $user = $this->service->createUser($data);

        return $response->json([
            'success' => true,
            'message' => 'Sign Up successful. Welcome!',
            'data' => $user
        ]);
    } catch (ValidationException $e) {
        return $response->json([
            'success' => false,
            'error' => $e->errors,  // This should be your detailed errors array
        ], 400);
    }
}

    public function updateuser(Request $request, Response $response, $id)
    {
        if (!$request->isPost()) {
            return $response->json(['error' => 'Invalid request method.'], 400);
        }
        $data = $request->getBody();
        try {
            $user = $this->service->updateUser($id, $data);


            return $response->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'data' => $user
            ]);
        } catch (ValidationException $e) {
            return $response->json([
                'success' => false,
                'error' => $e->errors,
            ], 400);
        }
    }
    public function deleteuser(Request $request, Response $response, $id)
    {
        if (!$request->isPost()) {
            return $response->json(['error' => 'Invalid request method.'], 400);
        }
        try {
            $this->service->deleteUser($id);
            return $response->json(['success' => true, 'message' => 'User deleted successfully.']);
        } catch (ValidationException $e) {
            return $response->json(['success' => false, 'error' => $e->errors], 400);
        }
    }
    public function login(Request $request, Response $response)
    {
        if (!$request->isPost()) {
            return $response->json(['error' => 'Invalid request method.'], 400);
        }
        $data = $request->getBody();
        try {
            //attempt login via service
            $user = $this->service->login($data);
            // Set the user in the AuthProvider to manage session
            AuthProvider::setUser($user);

            Application::$app->login($user); // Set the user in the application context

            Application::$app->session->set('user', $user); // Store user in session

            return $response->json(['success' => true, 'message' => 'Login successful.', 'data' => $user]);
        } catch (ValidationException $e) {
            return $response->json(['success' => false, 'error' => $e->errors], 400);
        }
    }
    public function logout(Request $request, Response $response,)
    {
        if (!$request->isPost()) {
            return $response->json(['error' => 'Invalid request method.'], 400);
        }
        $data = $request->getBody();
        try {
            $this->service->logout($data);
            AuthProvider::logout();
            return $response->json(['success' => true, 'message' => 'Logout successful.']);
        } catch (ValidationException $e) {
            return $response->json(['success' => false, 'error' => $e->errors], 400);
        }
    }
}