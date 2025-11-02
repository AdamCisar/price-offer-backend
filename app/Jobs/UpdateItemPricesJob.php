<?php

namespace App\Jobs;

use App\Events\ItemPriceUpdated;
use App\Exceptions\PtacekScrapperException;
use App\Models\Item;
use App\Models\PriceOffer\PriceOfferItem;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateItemPricesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly array $data, 
        private readonly string $owner,
        private readonly string $scrapperClassName
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lock = Cache::restoreLock('update-prices', $this->owner);

        if (empty($this->data['item_ids'])) {
            $lock->release();
            throw new Exception('Chýbajú položky!');
        }

        $scrapper = app($this->scrapperClassName, [
            'email' => Crypt::decryptString($this->data['email']),
            'password' => Crypt::decryptString($this->data['password']),
        ]);

        $totalSteps = count($this->data['item_ids']);

        $items = Item::fromRaw('items')
                ->whereIn('id', $this->data['item_ids'])
                ->where('user_id', $this->data['user_id'])->get();
        $urls = $items->pluck('url', 'id')->toArray();

        foreach ($this->data['item_ids'] as $index => $itemId) {
            $itemName = $items->where('id', $itemId)->first()->title;

            if (empty($urls[$itemId])) {
                new ItemPriceUpdated($itemId, [
                    'price_offer_id' => $this->data['price_offer_id'],
                    'error' => "Nie je nastavená URL položku {$itemName}!",
                ]);
                continue;
            }

            try {
                $price = $scrapper->getItemPrice($urls[$itemId]);
            } catch (PtacekScrapperException $e) {
                new ItemPriceUpdated($itemId, [
                    'price_offer_id' => $this->data['price_offer_id'],
                    'error' => $e->getMessage(),
                ]);

                $lock->release();
                throw new Exception($e->getMessage());
            } catch (Throwable $e) {
                new ItemPriceUpdated($itemId, [
                    'price_offer_id' => $this->data['price_offer_id'],
                    'error' => "Neznáma chyba!",
                ]);

                $lock->release();
                throw new Exception($e->getMessage());
            }

            $this->savePrice($price, $itemId, $this->data['price_offer_id']);

            new ItemPriceUpdated($itemId, [
                'percentage' => intval((($index + 1) / $totalSteps) * 100),
                'price_offer_id' => $this->data['price_offer_id'],
                'price' => $price,
            ]);
        }

        $lock->release();
    }

    private function savePrice(float $price, int $itemId, int $priceOfferId): void
    {
        // Item::where('id', $itemId)
        //     ->update(['price' => $price]);

        PriceOfferItem::where('price_offer_id', $priceOfferId)
            ->where('item_id', $itemId)
            ->update(['price' => $price]);
    }
}
