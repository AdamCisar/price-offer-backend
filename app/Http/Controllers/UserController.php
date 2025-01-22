<?php

namespace App\Http\Controllers;

use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function show(): JsonResponse
    {
        $user = $this->userService->getUserById(Auth::user()->id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user, 200);
    }

    public function update(Request $request): JsonResponse
    {
        $userInfo = $request->all();
        $userInfo['id'] = Auth::user()->id; 
        $user = $this->userService->update($userInfo);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user, 200); 
    }

}
