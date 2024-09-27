<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceOffer extends Model
{
    use HasFactory;

    protected $table = 'price_offers';

    protected $fillable = [
        'title',
        'description',
    ];
}
