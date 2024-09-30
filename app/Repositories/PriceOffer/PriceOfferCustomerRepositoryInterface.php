<?php

namespace App\Repositories\PriceOffer;

interface PriceOfferCustomerRepositoryInterface
{
    public function save(array $customer, int $priceOfferId): array;

    public function findById(int $id): array;
}