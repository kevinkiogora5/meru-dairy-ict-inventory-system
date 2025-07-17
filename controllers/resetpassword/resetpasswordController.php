<?php

namespace Mcdcu\Projects\controllers\resetpassword;

use Mcdcu\Projects\services\resetpassword\resetpasswordService;
use sigawa\mvccore\Request;
use sigawa\mvccore\Response;
use sigawa\mvccore\Controller;
use sigawa\mvccore\exception\ValidationException;

class resetpasswordController extends Controller
{
    protected resetpasswordService $passwordService;

    public function __construct()
    {
        $this->passwordService = new resetpasswordService();
    }

    public function forgotPasswordForm()
    {
        $this->setLayout("login"); // e.g. login layout wrapper
        return $this->render('forgotpassword'); // view: views/forgotpassword.php
    }


  public function handleForgotPassword(Request $request, Response $response)
{
    if (!$request->isPost()) {
        return $response->json([
            'success' => false,
            'message' => "Invalid request method"
        ], 405);
    }

    $email = $request->getBody()['email'] ?? null;

    try {
        $this->passwordService->sendResetLink($email);

        return $response->json([
            'success' => true,
            'message' => "Password reset link has been sent to your email."
        ], 200);

    } catch (\Exception $e) {
        return $response->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 400);
    }
}

    public function resetPasswordForm()
    {
        $this->setLayout("login");
        return $this->render('resetpassword'); // view: views/resetpassword.php
    }

    public function handleResetPassword(Request $request, Response $response)
    {
        if (!$request->isPost()) {
            return $response->json([
                'success' => false,
                'message' => 'Invalid request method'
            ], 405);
        }

        try {
            $data = $request->getBody();
            $token = $data['token'] ?? '';
            $password = $data['password'] ?? '';
            $confirmPassword = $data['confirmPassword'] ?? '';

            $this->passwordService->resetPassword($token, $password, $confirmPassword);

            return $response->json([
                'success' => true,
                'message' => 'Password reset successful. You can now log in.'
            ], 200);
        } catch (ValidationException $e) {
            return $response->json(['error' => $e->errors], 400);
        } catch (\Exception $e) {
            return $response->json(['error' => 'Something went wrong'], 500);
        }
    }
}
