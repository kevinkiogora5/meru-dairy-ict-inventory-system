<?php

namespace Mcdcu\Projects\services\location;

use Mcdcu\Projects\models\location\locations;
use sigawa\mvccore\exception\ValidationException;

class locationsService
{
   public function getAll(): array
    {
        return locations::findAll();
    }

    public function create(array $data): ?locations
    {
        if (empty($data)) {
            throw new ValidationException(['Invalid data.']);
        }
        $items = new locations();
        $items->loadData($data);
        if(!$items->validate()) {
            throw new ValidationException($items->getErrorMessages());
        }
        if (!$items->save()) {
            throw new ValidationException($items->getErrorMessages());
        }
        return $items;
    }

    public function update(int $id, array $data) : ?locations
    {
        $items = locations::findOne(['id' => $id]);
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
        $items = locations::findOne(['id' => $id]);
        if(!$items){
            throw new ValidationException(['Item not found.']);
        }
        return $items->delete();
    }

    public function search(string $term, array $columns, ?int $limit = null): array
    {
        return locations::search($term, $columns, $limit);
    }
}