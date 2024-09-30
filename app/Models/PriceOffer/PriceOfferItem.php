<?php

namespace App\Models\PriceOffer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceOfferItem extends Model
{
    use HasFactory;

    protected $table = 'price_offer_items';

    public function price_offer()
    {
        return $this->belongsTo(PriceOffer::class);
    }

    protected $fillable = [
        'item_id',
        'price_offer_id',
        'unit',
        'title',
        'quantity',
        'price',
        'total',
    ];
}
