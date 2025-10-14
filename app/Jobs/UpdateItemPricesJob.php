<?php

namespace App\Jobs;

use App\Events\ItemPriceUpdated;
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
        $totalSteps = count($this->data['item_ids']);

        foreach ($this->data['item_ids'] as $index => $item) {
            sleep(1);

            $percentage = intval((($index + 1) / $totalSteps) * 100);

            new ItemPriceUpdated($percentage);
        }

        Cache::restoreLock('update-prices', $this->owner)->release();
    }
}
