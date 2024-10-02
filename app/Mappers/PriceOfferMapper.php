<?php

namespace App\Mappers;

use App\Dto\CustomerDto;
use App\Dto\ItemDto;
use App\Dto\PriceOfferDTO;

class PriceOfferMapper
{
    public static function toDto(array $priceOffer): PriceOfferDTO
    {
        $itemDtos = [];
        $items = $priceOffer['items'] ?? [];
        $total = 0;

        foreach ($items as $item) {
            $itemDtos[] = ItemDto::create(
                $item['item_id'] ?? $item['id'],
                $item['title'],
                $item['unit'] ?? '',
                $item['price'],
                $item['quantity'],
                $item['total'],
            ); 
            $total += $item['total'];
        }; 

        $customer = $priceOffer['customer'] ?? [];
        $customerDto = CustomerDto::create(
            $customer['id'] ?? 0, 
            $customer['name'] ?? '', 
            $customer['address'] ?? '', 
            $customer['city'] ?? '', 
            $customer['zip'] ?? ''
        );

        return PriceOfferDTO::create($priceOffer['id'] ?? 0, $priceOffer['title'] ?? '', $priceOffer['description'] ?? '', $total, $customerDto, $itemDtos);
    }
}
