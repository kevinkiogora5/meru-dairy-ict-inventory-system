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

  // In returnService.php

public function update(int $id, array $data): ?returns
{
    // 1. Find the existing return record.
    $items = returns::findOne(['id' => $id]);
    if (!$items) {
        throw new ValidationException(['Return record not found.']);
    }

    // 2. Store the original inventory assignment ID before loading new data.
    $oldInventoryAssignmentId = $items->inventory_assignment_id;

    // 3. Load the new data from the request.
    $items->loadData($data);

    // 4. Validate and save the updated return record.
    if (!$items->validate() || !$items->save()) {
        throw new ValidationException($items->getErrorMessages());
    }

    // 5. Check if the inventory assignment ID has changed.
    if ($oldInventoryAssignmentId !== $items->inventory_assignment_id) {
        // --- Logic for the OLD assignment ---
        // Find the old assignment and "activate" it again.
        $oldAssignment = InventoryAssignment::findOne(['id' => $oldInventoryAssignmentId]);
        if ($oldAssignment) {
            // Un-delete the old assignment to make it "active" again.
            $oldAssignment->deleted_at = null;
            $oldAssignment->save();

            // Find the old item and change its status back to 'Assigned'.
            $oldItem = InventoryItem::findOne(['id' => $oldAssignment->inventory_item_id]);
            if ($oldItem) {
                $oldItem->status = 'Assigned';
                $oldItem->save();
            }
        }

        // --- Logic for the NEW assignment ---
        // Find the new assignment and "deactivate" it.
        $newAssignment = InventoryAssignment::findOne(['id' => $items->inventory_assignment_id]);
        if ($newAssignment) {
            // Delete the new assignment to mark the item as returned.
            $newAssignment->deleted_at = date('Y-m-d H:i:s');
            $newAssignment->save();

            // Find the new item and mark its status as 'Available'.
            $newItem = InventoryItem::findOne(['id' => $newAssignment->inventory_item_id]);
            if ($newItem) {
                $newItem->status = 'Available';
                $newItem->save();
            }
        }
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
}