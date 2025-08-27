<?php

namespace Mcdcu\Projects\models\users;

use sigawa\mvccore\UserModel;

class users extends UserModel
{
    public int $id = 0;
    public string $first_name = '';
    public string $last_name = '';
    public ?string $email = '';
    public string $password = '';
    public string $phone = '';
    public string $password_created_at = '';
    public ?int $online_status = 0;
    public ?string $session_token = null;
    public string $created_at = '';
    public string $updated_at = '';
    public ?string $deleted_at = null;  // nullable string
    public ?string $last_auth_at = null;  // nullable string
    public ?string $role = 'user'; // default role is 'user'

    public static function tableName(): string
    {
        return strtolower('User');
    }
    public function attributes(): array
    {
        return [
            'first_name',
            'last_name',
            'email',
            'phone',
            'password',
            'role',
            'password_created_at',
            'online_status',
            'session_token',
            'created_at',
            'updated_at',
            'deleted_at',
            'last_auth_at',
        ];
    }

    // get permisson method

    public function getPermissions(): array
    {
        return [];
    }
    public function getDisplayName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    public function rules()
    {
        return [
            'first_name' => [self::RULE_REQUIRED],
            'last_name' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED,[self::RULE_UNIQUE, 'class' => self::class]],
            'phone' => [self::RULE_REQUIRED,[self::RULE_UNIQUE, 'class' => self::class]],
            'password' => [self::RULE_PASSWORD],
            'role' => [self::RULE_REQUIRED],
        ];
    }
    public function beforeSave(): void
    {
        if($this->isNewRecord){
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            $this->created_at = date('Y-m-d H:i:s');
        } else {
            $this->updated_at = date('Y-m-d H:i:s');
        }
    }
  
}
