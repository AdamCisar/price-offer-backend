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
        $items = Item::all()->map(function ($item) {
            $item->url = json_decode($item->url, true);
            return $item;
        })->toArray(); 

        return $items;
    }

    public function findById(int $id): array
    {
        $item = Item::find($id);

        if (!$item) {
            return [];
        }

        return $item->toArray();
    }

    public function getSearchedItems(string $query): array
    {
        $items = Item::where('title', 'like', '%' . $query . '%')->get();

        if (!$items) {
            return [];
        }

        return $items->toArray();
    }

    public function save(array $item): array
    {
        $item = Item::updateOrCreate(['id' => $item['id'] ?? null], 
        $item);

        return $item->toArray();
    }
}
