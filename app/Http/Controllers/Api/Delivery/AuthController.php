<?php

namespace App\Http\Controllers\Api\Delivery;

use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required_without:phone|email',
            'phone' => 'required_without:email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $delivery = Delivery::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();

        if (!$delivery || !Hash::check($request->password, $delivery->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $delivery->createToken('delivery-token')->plainTextToken;

        return response()->json([
            'delivery' => $delivery,
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}