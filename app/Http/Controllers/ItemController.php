<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateItemPricesJob;
use App\Services\PriceOfferService\ItemService;
use App\Services\Scrappers\Eshops\PtacekScrapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class ItemController extends Controller
{
    public function __construct(private ItemService $itemService) {}

    public function index(): JsonResponse
    {
        $items = $this->itemService->getItems();

        return response()->json($items, 200);
    }

    public function save(Request $request): JsonResponse
    {
        $item = $this->itemService->save($request->toArray());

        if (!$item) {
            return response()->json(['message' => 'Item not created!'], 400);
        }

        return response()->json($item, 200);
    }
    public function destroy(Request $request): JsonResponse
    {
        $isDeleted = $this->itemService->delete($request->toArray());

        if (!$isDeleted) {
            return response()->json(['message' => 'Item has not been deleted!'], 400);
        }

        return response()->json(['message' => 'Item has been deleted!'], 200);
    }

    public function findByQuery(string $query): JsonResponse
    {
        $item = $this->itemService->getItemsByQuery($query);

        return response()->json($item, 200);
    }

    public function updatePrices(Request $request): JsonResponse
    {
        $lock = Cache::lock('update-prices');

        if (!$lock->get()) {
            return response()->json(['message' => 'There is another update in progress!'], 409);    
        }

        UpdateItemPricesJob::dispatch(
            [
                ...$request->toArray(), 
                ...[
                    'email' => Crypt::encryptString($request->input('email')), 
                    'password' => Crypt::encryptString($request->input('password')),
                    'user_id' => Auth::user()->id
                ]],
            $lock->owner(),
            PtacekScrapper::class
        );

        return response()->json(['message' => 'Price update has been started!'], 200);
    }
}
