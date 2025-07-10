<?php

namespace Mcdcu\Projects\models\employees;

use sigawa\mvccore\db\DbModel;

class employees extends DbModel
{
    public const RULE_MAX_LENGTH = 'max_length';
    public int $id = 0;
    public string $first_name = "";
    public string $last_name = "";
    public string $email = "";
    public int $department_id = 0;
    public int $user_id = 0;
    public string $phone = "";
    public string $created_at = "";
    // public ?string $deleted_at = null;  // nullable string
    public static function tableName(): string { return strtolower('employees'); }
    public function attributes(): array { return [
        'first_name',
        'last_name',
        'email',
        'department_id',
        'user_id',
        'phone',
    ]; }
    public function labels(): array { return []; }
    public function rules() { return [
        'first_name' => [self::RULE_REQUIRED],
        'last_name' => [self::RULE_REQUIRED],
        'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
        'department_id' => [self::RULE_REQUIRED, self::RULE_INTEGER],
        'user_id' => [self::RULE_REQUIRED, self::RULE_INTEGER],
        'phone' => [self::RULE_REQUIRED, [self::RULE_UNIQUE,'class'=>self::class]]
    ]; }
    public function beforeSave(): void
    {
        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
        }
    }
}