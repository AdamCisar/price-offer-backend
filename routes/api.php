<?php

use App\Services\PriceOfferService\ItemService;
use App\Services\PriceOfferService\PriceOfferService;
use App\Services\Scrappers\Scrapper;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/', [Scrapper::class, 'importPrices']);

Route::get('/users/{id}', [UserService::class, 'getUserById']);
Route::get('/price-offers', [PriceOfferService::class, 'getPriceOffers']);
Route::post('/price-offers', [PriceOfferService::class, 'createPriceOffer']);

Route::get('/items/search/{query}', [ItemService::class, 'getItemByQuery']);