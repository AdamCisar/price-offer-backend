<?php

namespace App\Services\Scrappers;

use App\Repositories\ItemRepositoryInterface;

class Scrapper
{
    protected ScrapperContext $scrapperContext;

    public function __construct(private ItemRepositoryInterface $itemRepository, private ScrapperContextLoader $scrapperContextLoader) 
    {
         $scrappers = $scrapperContextLoader->loadScrappers();
         $this->scrapperContext = new ScrapperContext($scrappers);
    }

    public function importPrices(): void
    {
        $items = $this->itemRepository->getItemsForScrapper();

        foreach ($items as $item) {
            $urls = $this->parseJsonUrls($item['url']);

            if (!$urls) {
                continue;
            }

            $price = $this->scrapperContext->getItemPrice($urls);

            if (empty($price)) {
                continue;
            }

            $result = [
                'id' => $item['id'],
                'price' => $price
            ];

            dump($result);

            $this->itemRepository->save($result);
        }
    }

    private function parseJsonUrls(string $jsonUrls): array
    {
        $result = [];

        if (empty($jsonUrls)) {
            return $result;
        }

        $urls = json_decode($jsonUrls, true);

        if (empty($urls)) {
            return $result;
        }

        foreach ($urls as $url) {
            $result[$url['shop']] = $url['url'];
        }

        return $result;
    }

}