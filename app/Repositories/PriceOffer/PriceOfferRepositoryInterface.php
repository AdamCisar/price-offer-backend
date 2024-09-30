<?php

namespace App\Repositories\PriceOffer;

use App\Dto\PriceOfferDto;

interface PriceOfferRepositoryInterface
{
    public function save(array $items): array;

    public function getPriceOffers(): array;

    public function findById(int $id): PriceOfferDto;
}
