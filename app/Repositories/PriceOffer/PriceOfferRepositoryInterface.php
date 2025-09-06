<?php

namespace App\Repositories\PriceOffer;

use App\Dto\PriceOfferDto;

interface PriceOfferRepositoryInterface
{
    public function save(array $priceOffer): array;

    public function getPriceOffers(int $offset): array;

    public function findById(int $id): PriceOfferDto;

    public function delete(array $idList): int;
}