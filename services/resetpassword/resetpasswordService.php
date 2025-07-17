<?php

namespace Mcdcu\Projects\services\resetpassword;

use Mcdcu\Projects\models\resetpassword\resetpassword;
use Mcdcu\Projects\models\users\users;
use Mcdcu\Projects\constants\TokenGenerator;
use Mcdcu\Projects\services\Helper\PasswordResetHelperService;
use sigawa\mvccore\Application;
use sigawa\mvccore\exception\ValidationException;

class resetpasswordService
{
    private array $columnMap = [
        'token' => 'reset_token',
        'purpose'=> 'user_type',
        'user_id' => 'user_id',
        'expires_at' => 'reset_token_expires_at',
        'created_at' => 'created_at'
    ];

   public function sendResetCode(string $email): void
{
    if (!$email) {
        throw new \Exception('Email is required.');
    }

    $user = users::findOne(['email' => $email]);
    if (!$user) {
        throw new \Exception('User not found.');
    }

    // Generate a 6-digit numeric code
    $otpCode = random_int(100000, 999999);
    $expiresAt = date('Y-m-d H:i:s', time() + 600); // 10 minutes

    // Store the OTP in the password_resets table
    $resetModel = new resetpassword();
    $resetModel->reset_token = $otpCode;
    $resetModel->user_id = $user->id;
    $resetModel->user_type = 'admin';
    $resetModel->reset_token_expires_at = $expiresAt;
    $resetModel->created_at = date('Y-m-d H:i:s');

    if (!$resetModel->save()) {
        throw new \Exception("Failed to store OTP.");
    }

    $helper = new PasswordResetHelperService();
    $sent = $helper->sendRestPasswordEmailOTP($user, $otpCode);

    if (!$sent) {
        throw new \Exception("Failed to send OTP.");
    }
}


    // ... resetPassword method (not shown here for brevity)

    public function resetPassword(string $token, string $newPassword, string $confirmPassword): void
    {
        if (empty($token)) {
            throw new ValidationException(['token' => 'Reset token is required.']);
        }

        if (empty($newPassword) || strlen($newPassword) < 8) {
            throw new ValidationException(['password' => 'Password must be at least 8 characters.']);
        }

        if ($newPassword !== $confirmPassword) {
            throw new ValidationException(['password' => 'Passwords do not match.']);
        }

        $tkngen = new TokenGenerator(
            32,
            1800,
            "password_reset_key",
            Application::$app->db->pdo,
            'password_resets',
            $this->columnMap
        );

        $isValid = $tkngen->verifyToken($token, 'staff');
        if (!$isValid) {
            throw new ValidationException(['token' => 'Invalid or expired token.']);
        }

        $tokenRow = resetpassword::findOne(['reset_token' => $token]);
        if (!$tokenRow) {
            throw new ValidationException(['token' => 'Reset token not found.']);
        }

        $user = users::findOne(['id' => $tokenRow->user_id]);
        if (!$user) {
            throw new ValidationException(['user' => 'User not found.']);
        }

        $user->password = $newPassword;
        $user->password_created_at = date('Y-m-d H:i:s');

        if (!$user->save()) {
            throw new ValidationException(['save' => 'Failed to save new password.']);
        }

        $tokenRow->delete();
    }
}
