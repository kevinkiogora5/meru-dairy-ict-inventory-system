<?php

namespace Mcdcu\Projects\models\Inventory;
use sigawa\mvccore\db\DbModel;

class InventoryAssignment extends DbModel
{
    public int $id = 0;
    public int $inventory_item_id = 0;
    public int $employee_id = 0;
    public int $location_id = 0;
    public int $issued_by = 0;
    public string $issue_date = "";
    public string $notes = "";
    public string $updated_at = "";
    public ?string $deleted_at = null;  // nullable string

    public static function tableName(): string { return strtolower('inventory_assignment'); }
    public function attributes(): array { return [
        'inventory_item_id',
        'employee_id',
        'location_id',
        'issued_by',
        'notes',
        'deleted_at'
    ]; }
    public function rules() { return [
        'inventory_item_id' => [self::RULE_REQUIRED, self::RULE_INTEGER],
        'employee_id' => [self::RULE_REQUIRED, self::RULE_INTEGER],
        'location_id' => [self::RULE_REQUIRED, self::RULE_INTEGER],
        'issued_by' => [self::RULE_REQUIRED, self::RULE_INTEGER],
         'notes' => [self::RULE_REQUIRED]
    ]; 
}
      
    public function beforeSave(): void
    {
        if ($this->isNewRecord) {
            $this->issue_date = date('Y-m-d H:i:s');
        }
        $this->updated_at = date('Y-m-d H:i:s');
    }
}