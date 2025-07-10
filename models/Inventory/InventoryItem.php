<?php

namespace Mcdcu\Projects\models\Inventory;

use sigawa\mvccore\db\DbModel;

class InventoryItem extends DbModel
{

    public int $id = 0;
    public string $name = "";
    public int $category_id = 0;
    public string $description = "";
    public string $model = "";
    public string $brand = "";
    public string $status = "available";
    public string $item_condition = "";
    public int $created_by = 0;
    public string $created_at = "";
    public string $updated_at = "";
    public string $serial_number = "";
    public ?string $deleted_at = null;  // nullable string
    public static function tableName(): string
    {
        return 'inventory_items';
    }
    public function attributes(): array
    {
        return [
            'name',
            'category_id',
            'description',
            'model',
            'brand',
            'status',
            'item_condition',
            'created_by',
            'deleted_at',
            'created_at',
            'serial_number',
        ];
    }
    public function rules()
    {
        return [
            'name' =>  [self::RULE_REQUIRED, [self::RULE_UNIQUE, 'class' => self::class]],
            'category_id' => [self::RULE_REQUIRED, self::RULE_INTEGER],
            'description' => [self::RULE_REQUIRED],
            'model' => [self::RULE_REQUIRED],
            'brand' => [self::RULE_REQUIRED],
            'status' => [self::RULE_REQUIRED],
            'item_condition' => [self::RULE_REQUIRED],
            'created_by' => [self::RULE_REQUIRED, self::RULE_INTEGER],
            'serial_number' => [self::RULE_REQUIRED, [self::RULE_UNIQUE, 'class' => self::class]]
        ];
    }

    public function beforeSave(): void
    {
        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        $this->updated_at = date('Y-m-d H:i:s');
    }
}
