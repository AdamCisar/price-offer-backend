<?php

namespace App\Mappers;

use App\Dto\PriceOfferCustomerDto;
use App\Dto\PriceOfferItemDto;
use App\Dto\PriceOfferDto;

class PriceOfferMapper
{
    public static function toDto(array $priceOffer): PriceOfferDto
    {
        $itemDtos = [];
        $items = $priceOffer['items'] ?? [];
        $total = 0;

        foreach ($items as $item) {
            $itemDtos[] = PriceOfferItemDto::create(
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
        $customerDto = PriceOfferCustomerDto::create(
            $customer['id'] ?? 0, 
            $customer['name'] ?? '', 
            $customer['address'] ?? '', 
            $customer['city'] ?? '', 
            $customer['zip'] ?? ''
        );

        return PriceOfferDto::create($priceOffer['id'] ?? 0, $priceOffer['title'] ?? '', $priceOffer['description'] ?? '', $total, $customerDto, $itemDtos);
    }
}
