<?php

namespace Mcdcu\Projects\models\location;

use sigawa\mvccore\db\DbModel;

class locations extends DbModel
{
    public int $id = 0;
    public string $county = "";
    public string $office = "";
    public int $created_by = 0;
    public string $created_at = "";
    public string $modified = "";
    public ?string $deleted_at = null;  // nullable string

    public static function tableName(): string { return strtolower('location'); }
    public function attributes(): array { return [
            'county',
            'office',
            'created_by',
            'created_at',
            'modified',
            'deleted_at'
    ]; }
    public function rules() { return [

        'county' => [self::RULE_REQUIRED],
        'office' => [self::RULE_REQUIRED],
        'created_by' => [self::RULE_REQUIRED, self::RULE_INTEGER],
    ]; 
}
    public function beforeSave(): void
    {
        $now = date('Y-m-d H:i:s');
        if ($this->isNewRecord) {
            $this->created_at = $now;
        }
        $this->modified = $now;
    }
      public static function findAllActive(): array
{
    $sql = "SELECT * FROM location WHERE deleted_at IS NULL";
    return self::findAllByQuery($sql); // Use the appropriate method from DbModel to execute raw SQL
}
}