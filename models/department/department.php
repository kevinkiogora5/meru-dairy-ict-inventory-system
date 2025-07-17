<?php

namespace Mcdcu\Projects\models\department;

use sigawa\mvccore\db\DbModel;

class department extends DbModel
{
    public int $id = 0;
    public string $name = "";
    public string $description = "";
    public int $created_by = 0;
    public string $created_at = "";
    public string $updated_at = "";
    public ?string $deleted_at = null;  // nullable string


    public static function tableName(): string { return strtolower('department'); }
    public function attributes(): array { return [
        'name',
        'description',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ]; }
    public function labels(): array { return []; }
    public function rules() { return [
        'name' => [self::RULE_REQUIRED],
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
    $sql = "SELECT * FROM department WHERE deleted_at IS NULL";
    return self::findAllByQuery($sql); // Use the appropriate method from DbModel to execute raw SQL
}


}