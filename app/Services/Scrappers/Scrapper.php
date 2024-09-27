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

    private function saveItems(array $items): void
    {
       $this->itemRepository->saveItems($items);
    }

    private function getItemsFromDb(): array
    {
        return $this->itemRepository->getItemsForScrapper();
    }

    public function importPrices(): void
    {
        $resultItems = [];
        $items = $this->getItemsFromDb();

        foreach ($items as $item) {
            $resultItems[] = [
                'id' => $item['id'],
                'price' => $this->scrapperContext->getItemPrice($item['url']),
            ];
        }

        $this->saveItems($resultItems);
    }
}