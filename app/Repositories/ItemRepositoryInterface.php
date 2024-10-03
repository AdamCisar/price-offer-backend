<?php

namespace App\Repositories;

interface ItemRepositoryInterface
{
    public function save(array $item): array;

    public function findById(int $id): array;

    public function getSearchedItems(string $query): array;

    public function getItems(): array;

}
