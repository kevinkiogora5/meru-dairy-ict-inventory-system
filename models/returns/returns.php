<?php

namespace Mcdcu\Projects\models\returns;

use sigawa\mvccore\db\DbModel;

class returns extends DbModel
{
    public int $id = 0;
    public int $inventory_assignment_id = 0;
    public int $received_by = 0;
    public string $returned_condition = "";
    public string $return_date = "";
    public string $comments = "";
    public string $updated_at = "";
    public ?string $deleted_at = null;  // nullable string
    public static function tableName(): string { return strtolower('returns'); }
    public function attributes(): array { return [
        'inventory_assignment_id',
        'received_by',
        'returned_condition',
        'comments',
        'deleted_at'
    ]; }
    public function rules() { return [

        'inventory_assignment_id' => [self::RULE_REQUIRED, self::RULE_INTEGER],
        'received_by' => [self::RULE_REQUIRED, self::RULE_INTEGER],
        'returned_condition' => [self::RULE_REQUIRED, self::RULE_STRING],
        'comments' => [self::RULE_REQUIRED]
    ]; }

    public function beforeSave(): void
    {
        if ($this->isNewRecord) {
            $this->return_date = date('Y-m-d H:i:s');
        }
        $this->updated_at = date('Y-m-d H:i:s');
    }
}