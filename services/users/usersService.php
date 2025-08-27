<?php

namespace Mcdcu\Projects\services\users;


use Mcdcu\Projects\models\users\users;
use sigawa\mvccore\exception\ValidationException;

class usersService
{
    public function createUser(array $data): users
    {
        $user = new users();
        $user->loadData($data);

        if ( $data['password'] !== $data['rpassword']) {
            throw new ValidationException(['password' => 'Passwords do not match.']);
        }
        if (!$user->validate()) {
            throw new ValidationException($user->getErrorMessages());
        }
        $user->save();
        return $user;
    }

    public function updateUser($id,array $data): users
    {
        
        $usewrdata = users::findOne(['id' => $id]);
        if (!$usewrdata) {
            throw new ValidationException(['User not found with the provided ID.']);
        }

        $usewrdata->loadData($data);
        if(!$usewrdata->validate($id)) {
            throw new ValidationException($usewrdata->getErrorMessages());
        }
        $usewrdata->password_created_at = date('Y-m-d H:i:s'); // Update password creation time
        $usewrdata->save();
        return $usewrdata;
    }
    public function deleteUser(array $data): bool
    {
        $user = users::findOne(['email' => $data['email']]);
        if (!$user) {
            throw new ValidationException(['User not found with the provided email.']);
        }
        return $user->delete();
    }
    public function login(array $data): users
    {
        $user = users::findOne(['email'=> $data['email']]);
           if (!$user) {
            throw new ValidationException(['email' => 'Invalid credentials.']);
        }


        if (!password_verify($data['password'], $user->password)) {
            throw new ValidationException(['password' => 'Invalid credentials.']);
        }
        $user->session_token = bin2hex(random_bytes(16)); // Generate a random session token
        $user->last_auth_at = date('Y-m-d H:i:s'); // Update last authentication time
        $user->online_status = 1; // Set online status
        $user->save(); // Save the session token and last authentication time

        return $user;
    }
    public function logout(array $data): bool
    {
        $user = users::findOne(['session_token' => $data['session_token']]);
        if (!$user) {
            throw new ValidationException(['session_token' => 'Invalid session token.']);
        }
        $user->session_token = null; // Clear the session token
        $user->online_status = 0; // Set offline status
        $user->last_auth_at = null; // Clear last authentication time
        return $user->save(); // Save the changes
    }
    public function resetpassword(array $data): bool
    {
        $user = users::findOne(['id' => $data['id']]);
        if (!$user) {
            throw new ValidationException(['id' => 'User not found with the provided ID.']);
        }
        $user->password = $data['new_password'];
        return $user->save();
    }
}
 