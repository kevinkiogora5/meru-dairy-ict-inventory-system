<?php

namespace Mcdcu\Projects\services\Inventory;

use Mcdcu\Projects\models\Inventory\InventoryCategories;
use sigawa\mvccore\exception\ValidationException;

class InventoryCategoriesService
{
   public function getAll(): array
    {
        return InventoryCategories::findAll();
    }

    public function create(array $data): ?InventoryCategories
    {
        if (empty($data)) {
            throw new ValidationException(['Invalid data.']);
        }
        $items = new InventoryCategories();
        $items->loadData($data);
        if(!$items->validate()) {
            throw new ValidationException($items->getErrorMessages());
        }
        if (!$items->save()) {
            throw new ValidationException($items->getErrorMessages());
        }
        return $items;
    }

    public function update(int $id, array $data) : ?InventoryCategories
    {
        $items = InventoryCategories::findOne(['id' => $id]);
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
        $items = InventoryCategories::findOne(['id' => $id]);
        if(!$items){
            throw new ValidationException(['Item not found.']);
        }
        return $items->delete();
    }

    public function search(string $term, array $columns, ?int $limit = null): array
    {

        return InventoryCategories::search($term, $columns, $limit);
    }
}