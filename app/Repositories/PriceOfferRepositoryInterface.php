<?php

namespace App\Repositories;

interface PriceOfferRepositoryInterface
{
    public function save(array $items): array;

    public function getPriceOffers(): array;

    public function findById(int $id): array;
}
