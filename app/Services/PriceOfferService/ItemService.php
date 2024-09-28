<?php

namespace App\Services\PriceOfferService;

use App\Repositories\Implementations\ItemRepository;

class ItemService
{
    public function __construct(private ItemRepository $itemRepository) {}

    public function getItemByQuery(string $query): array
    {
        return $this->itemRepository->getSearchedItem($query);
    }

    public function save(array $item): array
    {
        return $this->itemRepository->save($item);
    }
}