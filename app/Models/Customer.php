<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    public function priceOffer()
    {
        return $this->hasOne(PriceOffer\PriceOffer::class);
    }
    
    protected $fillable = [
        'name',
        'address',
        'city',
        'zip',
    ];

}
