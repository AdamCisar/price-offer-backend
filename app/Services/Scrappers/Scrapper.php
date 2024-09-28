<?php

namespace App\Services\Scrappers;

use App\Repositories\ItemRepositoryInterface;
use App\Services\Scrappers\Eshops\EmpiriaScrapper;

class Scrapper
{
    private ScrapperContext $scrapperContext;

    public function __construct(private \GuzzleHttp\Client $guzzle, private ItemRepositoryInterface $itemRepository)
    {
        $this->scrapperContext = new ScrapperContext([new EmpiriaScrapper($this->guzzle)]);
    }

    public function importPrices(): void
    {
        $items = $this->itemRepository->getItemsForScrapper();

        foreach ($items as $item) {
            $urls = json_decode($item['url'], true);

            if (!$urls) {
                continue;
            }

            $result = [
                'id' => $item['id'],
                'price' => $this->scrapperContext->getItemPrice($urls)
            ];

            $this->itemRepository->save($result);
        }
    }
}