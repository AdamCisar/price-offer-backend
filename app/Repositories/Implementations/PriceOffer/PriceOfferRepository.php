<?php

namespace App\Repositories\Implementations\PriceOffer;

use App\Dto\PriceOfferDto;
use App\Mappers\PriceOfferMapper;
use App\Models\PriceOffer\PriceOffer;
use App\Repositories\PriceOffer\PriceOfferRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class PriceOfferRepository implements PriceOfferRepositoryInterface
{
    public function getPriceOffers(): array
    {
        return PriceOffer::where('user_id', Auth::id())->get()->toArray();
    }

    public function save(array $priceOffer): array
    {
        $priceOffer = PriceOffer::updateOrCreate(
[
                'id' => $priceOffer['id'] ?? null, 
                'user_id' => Auth::id()
            ], 
            $priceOffer)->refresh();

        return $priceOffer->toArray();
    }

    public function findById(int $id): PriceOfferDto
    {
        $priceOffer = PriceOffer::with([
            'items' => function ($query) {
                $query->orderBy('ordering', 'asc');
            },
            'customer'
        ])
        ->where('user_id', Auth::id())
        ->find($id);

        return PriceOfferMapper::toDto($priceOffer->toArray());
    }

    public function delete(array $idList): int
    {
        return PriceOffer::whereIn('id', $idList)->where('user_id', Auth::id())->delete();

    }
}