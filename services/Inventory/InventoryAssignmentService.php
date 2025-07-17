<?php

namespace Mcdcu\Projects\services\Inventory;

use Mcdcu\Projects\models\Inventory\InventoryAssignment;
use Mcdcu\Projects\models\Inventory\InventoryItem;
use sigawa\mvccore\exception\ValidationException;

class InventoryAssignmentService
{
public function getAll(): array
{
   $sql = "
    SELECT 
        ia.id,
        ia.employee_id,
        ia.inventory_item_id,
        ia.location_id,
        CONCAT(e.first_name, ' - ', e.last_name) AS employees_email,
        ii.name AS items_name,
        CONCAT(l.county, ' - ', l.office) AS location_name,
        ia.issue_date,
        ia.notes
    FROM inventory_assignment ia
    JOIN inventory_items ii ON ia.inventory_item_id = ii.id
    JOIN employees e ON ia.employee_id = e.id
    JOIN location l ON ia.location_id = l.id
    WHERE ia.deleted_at IS NULL
      AND ii.deleted_at IS NULL
";
    return InventoryAssignment::findAllByQuery($sql);
}

    public function create(array $data): ?InventoryAssignment
    {
        if (empty($data)) {
            throw new ValidationException(['Invalid data.']);
        }
          // 🔍 Check if this inventory item is already assigned
    $existing = InventoryAssignment::findOne([
        'inventory_item_id' => $data['inventory_item_id'],
        'deleted_at' => null
    ]);

    if ($existing) {
        throw new ValidationException(['This inventory item is already assigned.']);
    }
        $items = new InventoryAssignment();
        $items->loadData($data);
        if(!$items->validate()) {
            throw new ValidationException($items->getErrorMessages());
        }
        if (!$items->save()) {
            throw new ValidationException($items->getErrorMessages());
        }
        
    // ✅ Update inventory item's status to 'Assigned'
    $item = InventoryItem::findOne(['id' => $data['inventory_item_id']]);
    if ($item) {
        $item->status = 'Assigned';
        $item->save();
    }
        return $items;
    }
    public function findAssignment(int $employeeId, int $itemId): ?InventoryAssignment
{
    return InventoryAssignment::findOne([
        'employee_id' => $employeeId,
        'inventory_item_id' => $itemId,
    ]);
}


    public function update(int $id, array $data) : ?InventoryAssignment
    {
        $items = InventoryAssignment::findOne(['id' => $id]);
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
        $items = InventoryAssignment::findOne(['id' => $id]);
        if(!$items){
            throw new ValidationException(['Item not found.']);
        }
        $items->deleted_at=date('Y-m-d H:i:s');
        return $items->save();
    }

    /**
     * Search products using free-text across relevant fields.
     */
    public function searchAndPaginate(array $params): array
    {
        $term = trim($params['search'] ?? '');
        $page = max(1, (int)($params['page'] ?? 1));
        $perPage = (int)($params['perPage'] ?? 20);
        $filters = $params['filters'] ?? [];
        $sortBy = $params['sortBy'] ?? 'ia.issue_date';
        $direction = strtoupper($params['direction'] ?? 'ASC');

        if(!in_array($direction, ['ASC','DESC'])) {
            $direction = 'DESC';
        }

        // Define the base searchable columns including joined tables
        $searchableColumns = [
            'ii.name',        //Inventory item name
            'ii.serial_number',       //Serial
            'e.name',               //Employee name
            'l.name',               //location name
            'u.name',          // Issued by(user)
            'ia.notes',       //Notes field
        ];

        // Create the query: with search or base
        $query = InventoryAssignment::Query()
        
            ->from('inventory_assignment ia')
            ->join('inventory_items ii', 'ia.inventory_item_id', '=', 'ii.id')
            ->join('employees e', 'ia.employee_id', '=', 'e.id')
            ->join('location l', 'ia.location_id', '=', 'l.id')
            ->join('user u', 'ia.issued_by','=','u.id')
            ->select([
                'ia.*',
                'ii.name AS item_name',
                'ii.serial_number',
                'e.email AS employee_email',
                'l.office AS location_office',
                'u.email AS user_email',
            ]);
            //Apply search across all fields
            if(!empty($term)) {
                $query->whereGroup(function ($group) use ($term, $searchableColumns) {
                    foreach ($searchableColumns as $column) {
                        $group->orWhere($column,'LIKE','%'. $term .'%');
                    }
                });
            }

        // Apply filters
        foreach ($filters as $key => $val) {
            if ($val !== null) {
                $query->where($key, '=', $val);
            }
        }

        // Apply sorting and pagination
        $query->orderBy($sortBy, $direction)
            ->limit($perPage)
            ->offset(($page - 1) * $perPage);

        // Fetch paginated data
        $data = $query->all();

        //Clone and count total without limit/offset
         $total = $query->cloneWithoutLimitOffset()->count();
        //  if ($term && strlen($term) > 2) {
        //     $exists = ProductSearchLog::query()
        //         ->where('user_id', '=', AuthProvider::id())
        //         ->where('term', '=', $term)
        //         ->all();
        //     if (!$exists) {
        //         ProductSearchLoggerService::logSearch($term, $total);
        //     }
        // }

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'lastPage' => (int)ceil($total / $perPage),
        ];
    }
 }