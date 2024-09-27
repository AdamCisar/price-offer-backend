<?php

namespace App\Services\Scrappers;

class ScrapperContext {

    public function __construct(private array $scrapers) {}

    public function getAveragePrice(array $itemPrices): float 
    {
        $average = array_sum($itemPrices) / count($itemPrices);
        return round($average, 2);
    }

    public function getItemPrice(string $url): float 
    {
        $itemPrices = [];

        foreach ($this->scrapers as $scraper) {
            $itemPrices[] = $scraper->getItemPrice($url);
        }
        
        return $this->getAveragePrice($itemPrices);
    }
}
