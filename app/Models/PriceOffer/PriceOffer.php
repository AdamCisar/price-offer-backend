<?php

namespace App\Models\PriceOffer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceOffer extends Model
{
    use HasFactory;

    protected $table = 'price_offers';

    public function customer()
    {
        return $this->hasOne(PriceOfferCustomer::class);
    }

    public function items()
    {
        return $this->hasMany(PriceOfferItem::class, 'price_offer_id');
    }

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'is_vat',
        'notes',
    ];

    protected $attributes = [
        'notes' => '',
    ];
}
