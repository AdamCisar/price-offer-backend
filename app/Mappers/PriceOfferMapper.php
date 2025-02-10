<?php

namespace App\Mappers;

use App\Dto\PriceOfferCustomerDto;
use App\Dto\PriceOfferItemDto;
use App\Dto\PriceOfferDto;

class PriceOfferMapper
{
    public static function toDto(array $priceOffer): PriceOfferDto
    {
        $items = $priceOffer['items'] ?? [];

        [$itemDtos, $total] = PriceOfferItemMapper::toDto($items);
        $customer = $priceOffer['customer'] ?? [];
        $customerDto = PriceOfferCustomerDto::create(
            $customer['id'] ?? 0, 
            $customer['name'] ?? '', 
            $customer['address'] ?? '', 
            $customer['city'] ?? '', 
            $customer['zip'] ?? ''
        );

        return PriceOfferDto::create($priceOffer['id'] ?? 0, $priceOffer['title'] ?? '', $priceOffer['description'] ?? '', $total, $priceOffer['is_vat'], $customerDto, $itemDtos);
    }
}
