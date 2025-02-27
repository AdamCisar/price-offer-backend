<?php

namespace App\Repositories\PriceOffer;

interface PriceOfferItemRepositoryInterface
{
    public function save(array $items, int $priceOfferId): array;

    public function findById(int $id): array;

    public function getSearchedItems(string $query): array;

    public function deleteNotIncluded(array $idList, int $priceOfferId): void;

    public function duplicate(int $fromPriceOfferId, int $toPriceOfferId): void;
}
