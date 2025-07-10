<?php

namespace Mcdcu\Projects\services\department;

use Mcdcu\Projects\models\department\department;
use sigawa\mvccore\exception\ValidationException;

class departmentService
{
    public function getAll(): array
    {
        return department::findAll();
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

    public function update(int $id, array $data) : ?department
    {
        $items = department::findOne(['id' => $id]);
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
        $items = department::findOne(['id' => $id]);
        if(!$items){
            throw new ValidationException(['Item not found.']);
        }
        return $items->delete();
    }

    public function search(string $term, array $columns, ?int $limit = null): array
    {
        return department::search($term, $columns, $limit);
    }
}