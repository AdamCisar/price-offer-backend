<?php

namespace App\Services\PriceOfferService;

use App\Repositories\ItemRepositoryInterface;

class ItemService
{
    public function __construct(private ItemRepositoryInterface $itemRepository) {}

    public function getItemsByQuery(string $query): array
    {
        return $this->itemRepository->getSearchedItems($query);
    }

    public function save(array $item): array
    {
        return $this->itemRepository->save($item);
    }
}