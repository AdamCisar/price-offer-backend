<?php

namespace App\Repositories;

interface ItemRepositoryInterface
{
    public function save(array $item): array;

    public function getItemsForScrapper(): array;

    public function findById(int $id): array;

    public function getSearchedItems(string $query): array;

}
