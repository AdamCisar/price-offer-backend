<?php

namespace App\Services\PriceOfferService;

use App\Repositories\ItemRepositoryInterface;

class ItemService
{
    public function __construct(private ItemRepositoryInterface $itemRepository) {}

    public function getItems(): array
    {
        return $this->itemRepository->getItems();
    }

    public function getItemsByQuery(string $query): array
    {
        return $this->itemRepository->getSearchedItems($query);
    }

    public function save(array $item): array
    {
        return $this->itemRepository->save($item);
    }

    public function delete(array $item): bool
    {
        return $this->itemRepository->delete($item['id']);
    }
}