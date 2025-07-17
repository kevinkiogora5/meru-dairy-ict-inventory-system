<?php

namespace Mcdcu\Projects\services\Inventory;

use Mcdcu\Projects\models\Inventory\InventoryItem;
use sigawa\mvccore\exception\ValidationException;

class InventoryItemService
{ 
    public function getAll(): array
{
   $sql = "
    SELECT 
        ii.id,
        ii.name,
        ii.model,
        ii.brand,
        ii.status,
        ii.item_condition,
        ii.serial_number,
        ii.category_id,
        ii.created_at,
        ic.type AS category_type 
    FROM 
        inventory_items ii
    LEFT JOIN 
        inventory_categories ic ON ii.category_id = ic.id
    WHERE 
        ii.deleted_at IS NULL
";

    return InventoryItem::findByQuery($sql);
}

    public function create(array $data): ?InventoryItem
    {
        if (empty($data)) {
            throw new ValidationException(['Invalid data.']);
        }
         // 🔒 Check if item name already exists
    if (!empty($data['name']) && InventoryItem::findOne(['name' => $data['name']])) {
        throw new ValidationException(['Item name already exists.']);
    }

    // 🔒 Check if serial number already exists
    if (!empty($data['serial_number']) && InventoryItem::findOne(['serial_number' => $data['serial_number']])) {
        throw new ValidationException(['Serial number already exists.']);
    }
        $items = new InventoryItem();
        $items->loadData($data);
     
        if(!$items->validate()) {
            throw new ValidationException($items->getErrorMessages());
        }
      
        if (!$items->save()) {
            throw new ValidationException($items->getErrorMessages());
        }
        return $items;
    }

    public function update(int $id, array $data) : ?InventoryItem
    {
        $items = InventoryItem::findOne(['id' => $id]);
        if(!$items){
            throw new ValidationException(['Item not found.']);
        }
        $items->loadData($data);
        if (!$items->save()) {
            throw new ValidationException($items->getErrors());
        }
        return $items;
    }

    public function delete(int $id): bool
    {
        $items = InventoryItem::findOne(['id' => $id]);
        if(!$items){
            throw new ValidationException(['Item not found.']);
        }
        $items->deleted_at=date('Y-m-d H:i:s');
        return $items->save();
    }

    public function search(string $term, array $columns, ?int $limit = null): array
    {
        return InventoryItem::search($term, $columns, $limit);
    }
}
// someway to interact with the inventory_items model.