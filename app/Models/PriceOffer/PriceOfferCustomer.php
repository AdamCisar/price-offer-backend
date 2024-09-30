<?php

namespace App\Models\PriceOffer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceOfferCustomer extends Model
{
    use HasFactory;

    protected $table = 'price_offer_customers';

    public function price_offer()
    {
        return $this->belongsTo(PriceOffer::class);
    }

    protected $fillable = [
        'price_offer_id',
        'name',
        'address',
        'city',
        'zip',
    ];
}
