<?php

namespace App\Services\Scrappers;

use App\Repositories\ItemRepositoryInterface;

class ScrapperService
{
    protected ScrapperContext $scrapperContext;

    public function __construct(private ItemRepositoryInterface $itemRepository, private ScrapperContextLoader $scrapperContextLoader) 
    {
         $scrappers = $scrapperContextLoader->loadScrappers();
         $this->scrapperContext = new ScrapperContext($scrappers);
    }

    public function importPrices(): void
    {
        $items = $this->itemRepository->getItems();

        foreach ($items as $item) {
            
            if (empty($item['url'])) {
                continue;
            }
            
            $urls = $this->parseUrls($item['url']);

            $price = $this->scrapperContext->getItemPrice($urls);

            if (empty($price)) {
                continue;
            }

            $result = [
                'id' => $item['id'],
                'price' => $price
            ];

            $this->itemRepository->save($result);
        }
    }

    private function parseUrls(array $urls): array
    {
        $result = [];

        if (empty($urls)) {
            return $result;
        }

        foreach ($urls as $url) {
            $result[$url['shop']] = $url['url'];
        }

        return $result;
    }

}