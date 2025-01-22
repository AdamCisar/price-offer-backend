<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email'=>'required|string|email',
                'password'=>'required|string'
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $cridentials = $request->only('email', 'password');

        $token = Auth::attempt($cridentials);
        
        if(!$token){
            return response()->json([
                'status'=>'error',
                'message'=>'unauthorized'
            ], 401);
        }

        return response()->json([
            'status'=> 'success',
            'auth'=> [
                'token' => $token,
                'type' => 'bearer'
            ]
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'auth' => [
                'token' => Auth::refresh(),
                'type' => 'bearer'
            ]
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
