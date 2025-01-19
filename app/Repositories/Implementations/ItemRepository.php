<?php

namespace App\Repositories\Implementations;

use App\Mappers\PriceOfferItemMapper;
use App\Models\Item;
use App\Repositories\ItemRepositoryInterface;
use App\Services\Scrappers\ScrapperContextLoader;

class ItemRepository implements ItemRepositoryInterface
{
    private array $scrapperClasses = [];

    public function __construct(private ScrapperContextLoader $scrapperContextLoader) 
    {  
        $this->initializeScrapperClasses();
    }

    public function getItems(): array
    {
        $items = Item::all()->map(function ($item) {
            $urls = json_decode($item->url, true);
            $item->url = array_replace($this->scrapperClasses, $urls ?? []);
            return $item;
        })->toArray(); 

        return $items;
    }

    public function findById(int $id): array
    {
        $item = Item::find($id);

        if (!$item) {
            return [];
        }

        return $item->toArray();
    }

    public function getSearchedItems(string $query): array
    {
        $items = Item::where('title', 'like', '%' . $query . '%')->select(['id as item_id', 'title', 'unit', 'price'])
        ->get();

        if (!$items) {
            return [];
        }

        [$itemDtos, $total] = PriceOfferItemMapper::toDto($items->toArray());

        return $itemDtos; 
    }

    public function save(array $item): array
    {
        $item = Item::updateOrCreate(['id' => $item['id'] ?? null], 
        $item);

        return $item->toArray();
    }

    private function initializeScrapperClasses(): void
    {
        $classes = $this->scrapperContextLoader->getScrapperClasses();

        if (!$classes) {
            return;
        }

        foreach ($classes as $class) {
            $filename = basename($class); 
            $shop = str_replace('Scrapper.php', '', $filename); 
            $shop = strtolower($shop); 
            $this->scrapperClasses[] = [
                'shop' => $shop,
                'url' => ''
            ];
        }
    }
}
