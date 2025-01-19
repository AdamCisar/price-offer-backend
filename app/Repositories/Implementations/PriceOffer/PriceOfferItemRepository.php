<?php

namespace App\Repositories\Implementations\PriceOffer;

use App\Models\PriceOffer\PriceOfferItem;
use App\Repositories\PriceOffer\PriceOfferItemRepositoryInterface;

class PriceOfferItemRepository implements PriceOfferItemRepositoryInterface
{
    public function getItems(): array
    {
        return PriceOfferItem::all()->toArray();
    }

    public function findById(int $id): array
    {
        $item = PriceOfferItem::find($id);

        if (!$item) {
            return [];
        }

        return $item->toArray();
    }

    public function getSearchedItems(string $query): array
    {
        $items = PriceOfferItem::where('title', 'like', '%' . $query . '%')->get();

        if (!$items) {
            return [];
        }

        return $items->toArray();
    }

    public function save(array $item, int $priceOfferId): array
    {
        $item['price_offer_id'] = $priceOfferId;
        $item = PriceOfferItem::updateOrCreate(['id' => $item['id'] ?? null], 
        $item);

        return $item->toArray();
    }

    /**
     * @param int[] $idList The list of IDs not to be deleted.
     */
    public function deleteNotIncluded(array $idList, int $priceOfferId): void
    {
        PriceOfferItem::whereNotIn('id', $idList)
        ->where('price_offer_id', $priceOfferId)
        ->delete();
    }

    public function duplicate(int $fromPriceOfferId, int $toPriceOfferId): void
    {
        $itemsToDuplicate = PriceOfferItem::where('price_offer_id', $fromPriceOfferId)->get();

        foreach ($itemsToDuplicate as $item) {
            $duplicate = $item->replicate();
            $duplicate->price_offer_id = $toPriceOfferId;
            $duplicate->save();
        }
    }
}
