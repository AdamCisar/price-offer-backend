<?php

namespace App\Repositories\Implementations;

use App\Models\PriceOffer;
use App\Repositories\PriceOfferRepositoryInterface;

class PriceOfferRepository implements PriceOfferRepositoryInterface
{
    public function getPriceOffers(): array
    {
        return PriceOffer::all()->toArray();
    }

    public function save(array $priceOffer): array
    {
        return PriceOffer::updateOrCreate(['id' => $priceOffer['id'] ?? null], $priceOffer);
    }


    public function findById(int $id): array
    {
        $priceOffer = PriceOffer::find($id);

        if (!$priceOffer) {
            return [];
        }

        return $priceOffer->toArray();
    }
}