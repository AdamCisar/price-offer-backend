<?php

namespace App\Repositories\Implementations;

use App\Models\Item;
use App\Repositories\ItemRepositoryInterface;

class ItemRepository implements ItemRepositoryInterface
{
    public function save(array $items): void
    {
        foreach ($items as $item) {
            Item::updateOrCreate(
                ['id' => $item['id']], 
                ['price' => $item['price']]
            );
        }
    }

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
}
