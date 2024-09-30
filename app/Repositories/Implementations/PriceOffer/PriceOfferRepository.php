<?php

namespace App\Repositories\Implementations\PriceOffer;

use App\Dto\PriceOfferDto;
use App\Mappers\PriceOfferMapper;
use App\Models\PriceOffer\PriceOffer;
use App\Repositories\PriceOffer\PriceOfferRepositoryInterface;

class PriceOfferRepository implements PriceOfferRepositoryInterface
{
    public function getPriceOffers(): array
    {
        return PriceOffer::all()->toArray();
    }

    public function save(array $priceOffer): array
    {
        $priceOffer = PriceOffer::updateOrCreate(['id' => $priceOffer['id'] ?? null], $priceOffer);

        return $priceOffer->toArray();
    }


    public function findById(int $id): PriceOfferDto
    {
        $priceOffer = PriceOffer::with(['items', 'customer'])->find($id);

        return PriceOfferMapper::toDto($priceOffer->toArray());
    }
}