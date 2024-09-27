<?php

namespace App\Repositories;

interface ItemRepositoryInterface
{
    public function save(array $items): void;

    public function getItemsForScrapper(): array;

    public function findById(int $id): array;
}
