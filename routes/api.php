<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PriceOfferController;
use App\Http\Controllers\UserController;
use App\Services\Scrappers\ScrapperService;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/', [ScrapperService::class, 'importPrices']);

    /** USERS */
    Route::group(['prefix' => 'users'], function () {
        Route::post('/', [UserController::class, 'update']);
        Route::get('/{id}', [UserController::class, 'show']);
    });

    /** PRICE OFFERS */
    Route::group(['prefix' => 'price-offers'], function () {
        Route::get('/', [PriceOfferController::class, 'index']);
        Route::get('/{id}', [PriceOfferController::class, 'show']);
        Route::post('/', [PriceOfferController::class, 'save']);
        Route::delete('/', [PriceOfferController::class, 'destroy']);
        Route::get('customers/search/{query}', [PriceOfferController::class, 'findCustomersByQuery']);
    });

    /** ITEMS */
    Route::group(['prefix' => 'items'], function () {
        Route::get('/search/{query}', [ItemController::class, 'findByQuery']);
        Route::post('/', [ItemController::class, 'save']);
        Route::get('/', [ItemController::class, 'index']);
    });
});
