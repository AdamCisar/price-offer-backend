<?php

namespace App\Models;

use App\Models\Scopes\ItemScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items'; 

    protected $fillable = [
        'title',
        'unit',
        'price',
        'url'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new ItemScope);

        static::creating(function ($item) {
            $item->user_id = Auth::id();
        });

        static::updating(function ($item) {
            $item->user_id = Auth::id();
        });

        static::deleting(function ($item) {
            if ($item->user_id !== Auth::id()) {
                return false;
            }
        });
    }
}
