<?php

namespace App\Http\Controllers\Api\Warehouse;

use App\Models\Warehouse;
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

        $warehouse = Warehouse::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();

        if (!$warehouse || !Hash::check($request->password, $warehouse->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $warehouse->createToken('warehouse-token')->plainTextToken;

        return response()->json([
            'warehouse' => $warehouse,
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}