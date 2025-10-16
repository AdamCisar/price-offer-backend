<?php

namespace App\Jobs;

use App\Events\ItemPriceUpdated;
use App\Models\Item;
use App\Models\PriceOffer\PriceOfferItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;

class UpdateItemPricesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly array $data, private readonly string $owner)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lock = Cache::restoreLock('update-prices', $this->owner);

        if (empty($this->data['item_ids'])) {
            $lock->release();
            return;
        }

        $totalSteps = count($this->data['item_ids']);
        $urls = Item::whereIn('id', $this->data['item_ids'])
            ->pluck('url', 'id')->toArray(); 

        foreach ($this->data['item_ids'] as $index => $itemId) {
            $price = $this->scrapePrice($urls[$itemId]);
            $percentage = intval((($index + 1) / $totalSteps) * 100);

            $this->savePrice($price, $itemId, $this->data['price_offer_id']);

            new ItemPriceUpdated($itemId, [
                'percentage' => $percentage,
                'price_offer_id' => $this->data['price_offer_id'],
                'price' => $price,
            ]);
        }

        $lock->release();
    }

    private function scrapePrice(string $url): float
    {
        //TODO scraper

        return 0;
    }

    private function savePrice(float $price, int $itemId, int $priceOfferId): void
    {
        Item::where('id', $itemId)
            ->update(['price' => $price]);

        PriceOfferItem::where('price_offer_id', $priceOfferId)
            ->where('item_id', $itemId)
            ->update(['price' => $price]);
    }
}
