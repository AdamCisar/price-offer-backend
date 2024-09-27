<?php

namespace App\Services\PriceOfferService;

class ItemService
{
    public function getItemByQuery(string $query): float
    {
        dd($query);
        $item = Item::where('url', $url)->first();
        return $item->price;
    }
}