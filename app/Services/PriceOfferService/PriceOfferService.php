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

    public function createOrUpdate(array $request): array
    {
        return $this->priceOfferRepository->save($request);
    }

    public function getPriceOffer(int $id): array
    {
        return $this->priceOfferRepository->findById($id);
    }
}