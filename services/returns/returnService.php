<?php

namespace Mcdcu\Projects\services\returns;

use Mcdcu\Projects\models\Inventory\InventoryAssignment;
use Mcdcu\Projects\models\Inventory\InventoryItem;
use Mcdcu\Projects\models\returns\returns;
use sigawa\mvccore\exception\ValidationException;

class returnService
{
    public function getAll(): array
    {
      $sql = " SELECT 
                r.id,
                r.returned_condition,
                r.return_date,
                r.comments,
                r.inventory_assignment_id,
                ia.employee_id,
                ia.inventory_item_id,
                i.name AS inventory_item_name,
                CONCAT(e.first_name, ' - ', e.last_name) AS employee_email
            FROM returns r
            LEFT JOIN inventory_assignment ia ON r.inventory_assignment_id = ia.id
            LEFT JOIN inventory_items i ON ia.inventory_item_id = i.id
            LEFT JOIN employees e ON ia.employee_id = e.id
            WHERE r.deleted_at IS NULL
            ORDER BY r.return_date DESC
            ";
        return returns::findAllByQuery($sql);
    }

    public function create(array $data): ?returns
{
    if (empty($data)) {
        throw new ValidationException(['Invalid data.']);
    }

    // ✅ Check if item has already been returned
    $existingReturn = returns::findOne([
        'inventory_assignment_id' => $data['inventory_assignment_id'],
        'deleted_at' => null
    ]);

    if ($existingReturn) {
        throw new ValidationException(['This item has already been returned.']);
    }

    $items = new returns();
    $items->loadData($data);

    if (!$items->validate()) {
        throw new ValidationException($items->getErrorMessages());
    }

    if (!$items->save()) {
        throw new ValidationException($items->getErrorMessages());
    }

    // ✅ Fetch assignment
    $assignment = InventoryAssignment::findOne([
        'id' => $data['inventory_assignment_id'],
        'deleted_at' => null
    ]);

    if ($assignment) {
        $assignment->deleted_at = date('Y-m-d H:i:s');
        $assignment->save();

        // ✅ Mark inventory item as available
        $item = InventoryItem::findOne(['id' => $assignment->inventory_item_id]);
        if ($item) {
            $item->status = 'Available';
            $item->save();
        }
    }

    return $items;
}

    public function update(int $id, array $data) : ?returns
    {
        $items = returns::findOne(['id' => $id]);
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
        $items = returns::findOne(['id' => $id]);
        if(!$items){
            throw new ValidationException(['Item not found.']);
        }
        $items->deleted_at = date('Y-m-d H:i:s');
        return $items->save();
    }

    public function search(string $term, array $columns, ?int $limit = null): array
    {
        return returns::search($term, $columns, $limit);
    }
}