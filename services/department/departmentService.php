<?php

namespace Mcdcu\Projects\services\department;

use Mcdcu\Projects\models\department\department;
use sigawa\mvccore\exception\ValidationException;

class departmentService
{
    public function getAll(): array
    {
        // Fetch all departments that are not soft-deleted
        return department::findAllActive();
    }

    public function create(array $data): ?department
    {
        if (empty($data)) {
            throw new ValidationException(['Invalid data.']);
        }
        $items = new department();
        $items->loadData($data);
        if(!$items->validate()) {
            throw new ValidationException($items->getErrorMessages());
        }
        if (!$items->save()) {
            throw new ValidationException($items->getErrorMessages());
        }
        return $items;
    }

    public function update(int $id, array $data): ?department
{
    $item = department::findOne(['id' => $id]);

    if (!$item) {
        throw new ValidationException(['Item not found.']);
    }

    $item->loadData($data);

    // Validate before saving
    if (!$item->validate()) {
        throw new ValidationException($item->getErrorMessages());
    }

    if (!$item->save()) {
        throw new ValidationException($item->getErrorMessages());
    }

    return $item;
}


    public function delete(int $id): bool
{
    $item = department::findOne(['id' => $id]);

    if (!$item) {
        throw new ValidationException(['Item not found.']);
    }

    // Soft delete by setting deleted_at
    $item->deleted_at = date('Y-m-d H:i:s');
    return $item->save(); // ✅ Persist the soft-deleted status
}


    public function search(string $term, array $columns, ?int $limit = null): array
    {
        return department::search($term, $columns, $limit);
    }
}