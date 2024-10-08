<?php

namespace App\Services\Scrappers;

class ScrapperContext {

    public function __construct(private array $scrapers) {}

    public function getAveragePrice(array $itemPrices): float 
    {
        if (empty($itemPrices)) {
            return 0;
        }

        $average = array_sum($itemPrices) / count($itemPrices);
        return round($average, 2);
    }

    public function getItemPrice(array $urls): float 
    {
        $itemPrices = [];

        foreach ($this->scrapers as $scraper) {
            $itemPrice = $scraper->getItemPrice($urls);

            if (empty($itemPrice)) {
                continue;
            }

            $itemPrices[] = $itemPrice;
        }

        return $this->getAveragePrice($itemPrices);
    }
}
