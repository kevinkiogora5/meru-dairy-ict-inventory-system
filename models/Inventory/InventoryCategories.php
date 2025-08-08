<?php

namespace Mcdcu\Projects\models\Inventory;

use sigawa\mvccore\db\DbModel;

class InventoryCategories extends DbModel
{ 
    public int $id = 0;
    public string $type = "";
    public string $description = "";
    public int $created_by = 0;
    public string $created_at = "";
    public string $updated_at = "";
    public ?string $deleted_at = null;  // nullable string
    public static function tableName(): string { return strtolower('inventory_categories'); }
    public function attributes(): array { return [
        'type',
        'description',
        'created_by',
        'deleted_at'
    ]; }
    public function rules() { return [
        'type' => [self::RULE_REQUIRED,[self::RULE_UNIQUE, 'class' => self::class]],
        'description' => [self::RULE_REQUIRED],
        'created_by' => [self::RULE_REQUIRED, self::RULE_INTEGER],
    ];
 }
    public function beforeSave(): void
    {
        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        $this->updated_at = date('Y-m-d H:i:s');
    }
       public static function findAllActive(): array
{
    $sql = "SELECT * FROM inventory_categories WHERE deleted_at IS NULL";
    return self::findAllByQuery($sql); // Use the appropriate method from DbModel to execute raw SQL
}
}