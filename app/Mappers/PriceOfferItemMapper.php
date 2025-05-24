<?php

namespace App\Mappers;

use App\Dto\PriceOfferItemDto;

class PriceOfferItemMapper
{

    public static function toDto(array $items): array
    {
        $total = 0;
        $itemDtos = [];

        foreach ($items as $item) {
            $itemDtos[] = PriceOfferItemDto::create(
                $item['id'] ?? 0,
                $item['item_id'] ?? 0,
                $item['title'],
                $item['unit'] ?? '',
                $item['price'],
                $item['quantity'] ?? 1,
                $item['total'] ?? $item['price'],
                $item['ordering'] ?? 0
            ); 
            $total += $item['total'] ?? $item['price'];
        }; 

        return [$itemDtos, $total];
    }
}
