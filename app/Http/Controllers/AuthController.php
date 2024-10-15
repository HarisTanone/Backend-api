<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:4|max:100',
            'password' => 'required|string|min:8|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid Input',
                'validations' => $validator->errors()
            ], 400);
        }

        if (!$token = JWTAuth::attempt($validator->validated())) {
            return response()->json(['message' => 'Username atau password salah'], 401);
        }

        return $this->createNewToken($token);
    }
    protected function createNewToken($token)
    {
        return response()->json([
            'data' => [
                'token' => $token,
            ]
        ]);
    }
}
