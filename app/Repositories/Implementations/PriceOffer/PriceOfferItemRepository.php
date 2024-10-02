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
        $item = PriceOfferItem::updateOrCreate(['item_id' => $item['id'] ?? null], 
        $item);

        return $item->toArray();
    }

    /**
     * @param int[] $idList The list of IDs to not be deleted.
     */
    public function deleteNotIncluded(array $idList): void
    {
        PriceOfferItem::whereNotIn('item_id', $idList)->delete();
    }
}
