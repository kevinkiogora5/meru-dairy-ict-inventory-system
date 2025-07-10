<?php

namespace Mcdcu\Projects\services\Inventory;

use Mcdcu\Projects\models\Inventory\InventoryAssignment;
use sigawa\mvccore\exception\ValidationException;

class InventoryAssignmentService
{
public function getAll(): array
{
    $sql = "
    SELECT 
        ia.id,
        e.email AS employees_email,
        ii.name AS items_name,
        CONCAT(l.county, ' - ', l.office) AS location_name,
        ia.issue_date,
        ia.notes
    FROM inventory_assignment ia
    JOIN inventory_items ii ON ia.inventory_item_id = ii.id
    JOIN employees e ON ia.employee_id = e.id
    JOIN location l ON ia.location_id = l.id
    WHERE ii.deleted_at IS NULL
";


    return InventoryAssignment::findAllByQuery($sql);
}

    public function create(array $data): ?InventoryAssignment
    {
        if (empty($data)) {
            throw new ValidationException(['Invalid data.']);
        }
        $items = new InventoryAssignment();
        $items->loadData($data);
        if(!$items->validate()) {
            throw new ValidationException($items->getErrorMessages());
        }
        if (!$items->save()) {
            throw new ValidationException($items->getErrorMessages());
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
        return $items->delete();
    }

    /**
     * Search products using free-text across relevant fields.
     */
//     public function search(string $term, int $limit = 15): array
//     {
//         return InventoryAssignment::search($term, ['inventory_item_name', 'employee_name', 'location_name','issued_by_name'], $limit);
//     }
//     public function searchAndPaginate(array $params): array
//     {
//         $term = trim($params['search'] ?? '');
//         $page = max(1, (int)($params['page'] ?? 1));
//         $perPage = (int)($params['perPage'] ?? 20);
//         $filters = $params['filters'] ?? [];
//         $sortBy = $params['sortBy'] ?? 'p.price';
//         $direction = strtoupper($params['direction'] ?? 'ASC');

//         // Define the base searchable columns including joined tables
//         $searchableColumns = [
//             'p.name',
//             'p.description',
//             'p.brand',
//             'p.category',
//             's.name',          // Supermarket
//             'sb.name',         // Branch
//             'sb.address'
//         ];

//         // Create the query: with search or base
//         $query = $term? InventoryAssignment::searchQuery($term, $searchableColumns)
//             : InventoryAssignment::query()
//             ->from('inventory_assignment p')
//             ->join('inventory_items i', 'p.inventory_item_id', '=', 'i.id')
//             ->join('User e', 'p.issued_by', '=', 'e.id')
//             ->join('employees emp', 'p.employee_id', '=', 'emp.id')
//             ->join('locations l', 'p.location_id', '=', 'l.id')
//             ->select([
//                 'p.*',
//                 'i.name AS inventory_item_name',
//                 'emp.name AS employee_name',
//                 'l.name AS location_name',
//                 'e.name AS issued_by_name'
//             ]);

//         // Apply filters
//         foreach ($filters as $key => $val) {
//             if ($val !== null) {
//                 $query->where($key, '=', $val);
//             }
//         }

//         // Apply sorting and pagination
//         $query->orderBy($sortBy, $direction)
//             ->limit($perPage)
//             ->offset(($page - 1) * $perPage);

//         // Fetch paginated data
//         $data = $query->all();

//         // Clone and count total without limit/offset
//         // $total = $query->cloneWithoutLimitOffset()->count();
//         // if ($term && strlen($term) > 2) {
//         //     $exists = ProductSearchLog::query()
//         //         ->where('user_id', '=', AuthProvider::id())
//         //         ->where('term', '=', $term)
//         //         ->all();
//         //     if (!$exists) {
//         //         ProductSearchLoggerService::logSearch($term, $total);
//         //     }
//         // }

//         return [
//             'data' => $data,
//             //'total' => $total,
//             'page' => $page,
//             'perPage' => $perPage,
//             //'lastPage' => (int)ceil($total / $perPage),
//         ];
//     }
 }