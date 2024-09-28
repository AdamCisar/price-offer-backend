<?php

namespace App\Repositories\Implementations;

use App\Models\Item;
use App\Repositories\ItemRepositoryInterface;

class ItemRepository implements ItemRepositoryInterface
{
    public function getItemsForScrapper(): array
    {
        return Item::where('url', '!=', null)->get()->toArray();
    }

    public function getItems(): array
    {
        return Item::all()->toArray();
    }

    public function findById(int $id): array
    {
        return [];
    }

    public function getSearchedItem(string $query): array
    {
        $item = Item::where('title', 'like', '%' . $query . '%')->first();

        if (!$item) {
            return [];
        }

        return $item->toArray();
    }

    public function save(array $item): int
    {
        $item = Item::updateOrCreate(['id' => $item['id']], 
        $item);

        return $item->id;
    }
}
