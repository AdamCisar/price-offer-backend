<?php

namespace App\Repositories;

interface ItemRepositoryInterface
{
    public function save(array $item): int;

    public function getItemsForScrapper(): array;

    public function findById(int $id): array;

    public function getSearchedItem(string $query): array;

}
