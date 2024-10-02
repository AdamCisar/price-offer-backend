<?php

namespace App\Http\Controllers;

use App\Services\PriceOfferService\ItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            return response()->json(['message' => 'Item not created!'], 404);
        }

        return response()->json($item, 200);
    }

    public function findByQuery(string $query): JsonResponse
    {
        $item = $this->itemService->getItemsByQuery($query);

        return response()->json($item, 200);
    }

}
