<?php

namespace App\Services\PriceOfferService;

use App\Repositories\PriceOfferRepositoryInterface;
use Illuminate\Http\Request;

class PriceOfferService
{
    public function __construct(private PriceOfferRepositoryInterface $priceOfferRepository) {}

    public function getPriceOffers(): array
    {
        return $this->priceOfferRepository->getPriceOffers();
    }

    public function createPriceOffer(Request $request): array
    {
        return $this->priceOfferRepository->save($request->toArray());
    }
}