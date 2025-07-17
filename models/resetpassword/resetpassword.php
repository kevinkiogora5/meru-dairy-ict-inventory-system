<?php

namespace Mcdcu\Projects\models\resetpassword;

use sigawa\mvccore\db\DbModel;

class resetpassword extends DbModel
{
    public int $id = 0;
    public int $user_id = 0;
    public string $user_type = 'admin';
    public ?string $reset_token = null;
    public ?string $reset_token_expires_at = null;
    public int $failed_attempts = 0;
    public ?string $locked_until = null;
    public ?string $last_attempt_at = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public static function tableName(): string
    {
        return 'password_resets';
    }

    public function attributes(): array
    {
        return [
            'user_id',
            'user_type',
            'reset_token',
            'reset_token_expires_at',
            'failed_attempts',
            'locked_until',
            'last_attempt_at',
            'created_at',
            'updated_at'
        ];
    }

    public function rules(): array
    {
        return [
            'user_id' => [self::RULE_REQUIRED],
            'user_type' => [self::RULE_REQUIRED],
        ];
    }
}
