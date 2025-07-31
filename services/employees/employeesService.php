<?php

namespace Mcdcu\Projects\services\employees;

use Mcdcu\Projects\models\employees\employees;
use sigawa\mvccore\exception\ValidationException;

class employeesService
{
    public static function getAll(): array
{
    $sql = "
        SELECT 
            e.id, 
            e.first_name, 
            e.last_name, 
            e.email, 
            e.phone, 
            e.department_id, 
            d.name AS department_name,
            e.created_at
        FROM employees e
        LEFT JOIN department d ON e.department_id = d.id
        WHERE e.deleted_at IS NULL
    ";
    return employees::findAllByQuery($sql);
}
public function getEmployeesWithAssignments(): array
{
    $sql = "
        SELECT DISTINCT e.*
        FROM employees e
        INNER JOIN inventory_assignment ia ON ia.employee_id = e.id
        WHERE ia.deleted_at IS NULL
    ";
    return employees::findAllByQuery($sql);
}



    public function create(array $data): ?employees
    {
        if (empty($data)) {
            throw new ValidationException(['Invalid data.']);
        }
        // 🔒 Check if email already exists
    if (!empty($data['email']) && employees::findOne(['email' => $data['email']])) {
        throw new ValidationException(['Employee Id already exists.']);
    }

    // 🔒 Check if phone already exists
    if (!empty($data['phone']) && employees::findOne(['phone' => $data['phone']])) {
        throw new ValidationException(['Phone number already exists.']);
    }
        $items = new employees();
        $items->loadData($data);
        if(!$items->validate()) {
            throw new ValidationException($items->getErrorMessages());
        }
        if (!$items->save()) {
            throw new ValidationException($items->getErrorMessages());
        }
        return $items;
    }

    public function update(int $id, array $data) : ?employees
    {
        $items = employees::findOne(['id' => $id]);
        if(!$items){
            throw new ValidationException(['Item not found.']);
        }
        $items->loadData($data);
        if (!$items->validate($id)) {
            throw new ValidationException($items->getErrorMessages());
        }
        if (!$items->save()) {
            throw new ValidationException($items->getErrors());
        }
        return $items;
    }

    public function delete(int $id): bool
    {
        $items = employees::findOne(['id' => $id]);
        if(!$items){
            throw new ValidationException(['Item not found.']);
        }
        $items->deleted_at = date('Y-m-d H:i:s');
        return $items->save();
    }

    public function search(string $term, array $columns, ?int $limit = null): array
    {
        return employees::search($term, $columns, $limit);
    }
}