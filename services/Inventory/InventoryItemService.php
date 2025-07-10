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
            ii.*, 
            ic.type AS category_type 
        FROM 
            inventory_items ii
        LEFT JOIN 
            inventory_categories ic ON ii.category_id = ic.id
    ";

    return InventoryItem::findByQuery($sql);
}

    public function create(array $data): ?InventoryItem
    {
        if (empty($data)) {
            throw new ValidationException(['Invalid data.']);
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
        return $items->delete();
    }

    public function search(string $term, array $columns, ?int $limit = null): array
    {
        return InventoryItem::search($term, $columns, $limit);
    }
}
// someway to interact with the inventory_items model.