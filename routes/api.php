<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\PriceOfferController;
use App\Http\Controllers\UserController;
use App\Services\Scrappers\Scrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/', [Scrapper::class, 'importPrices']);


/** USERS */
Route::group(['prefix' => 'users'], function () {
    Route::post('/', [UserController::class, 'update']);
    Route::get('/{id}', [UserController::class, 'getUser']);
});



/** PRICE OFFERS */
Route::group(['prefix' => 'price-offers'], function () {
    Route::get('/', [PriceOfferController::class, 'listPriceOffers']);
    Route::get('/{id}', [PriceOfferController::class, 'findById']);
    Route::post('/', [PriceOfferController::class, 'save']);
});




/** ITEMS */
Route::group(['prefix' => 'items'], function () {
    Route::get('/search/{query}', [ItemController::class, 'findByQuery']);
    Route::post('/', [ItemController::class, 'save']);
});