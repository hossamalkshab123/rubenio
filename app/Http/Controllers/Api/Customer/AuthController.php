<?php

namespace App\Http\Controllers\Api\Customer;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendVerificationCode;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|unique:customers',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $verificationCode = rand(1000, 9999);

        $customer = Customer::create([
            'phone' => $request->phone,
            'verification_code' => $verificationCode,
        ]);

        // هنا يجب إرسال الكود إلى الهاتف (يمكنك استخدام خدمة مثل Twilio)
        // Notification::send($customer, new SendVerificationCode($verificationCode));

        return response()->json([
            'message' => 'Verification code sent to your phone',
            'phone' => $customer->phone
        ], 201);
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:customers',
            'verification_code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $customer = Customer::where('phone', $request->phone)
            ->where('verification_code', $request->verification_code)
            ->first();

        if (!$customer) {
            return response()->json(['error' => 'Invalid verification code'], 401);
        }

        $customer->update([
            'phone_verified_at' => now(),
            'verification_code' => null,
        ]);

        return response()->json([
            'message' => 'Phone verified successfully',
            'customer' => $customer
        ]);
    }

    public function completeRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:customers,phone',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $customer = Customer::where('phone', $request->phone)->first();

        if (!$customer->phone_verified_at) {
            return response()->json(['error' => 'Phone not verified'], 401);
        }

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $customer->createToken('customer-token')->plainTextToken;

        return response()->json([
            'customer' => $customer,
            'token' => $token
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $customer = Customer::where('phone', $request->phone)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $customer->createToken('customer-token')->plainTextToken;

        return response()->json([
            'customer' => $customer,
            'token' => $token
        ], 200);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:customers',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $verificationCode = rand(1000, 9999);

        $customer = Customer::where('phone', $request->phone)->first();
        $customer->update(['verification_code' => $verificationCode]);

        // إرسال الكود إلى الهاتف
        // Notification::send($customer, new SendVerificationCode($verificationCode));

        return response()->json([
            'message' => 'Verification code sent to your phone',
            'phone' => $customer->phone
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:customers',
            'verification_code' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $customer = Customer::where('phone', $request->phone)
            ->where('verification_code', $request->verification_code)
            ->first();

        if (!$customer) {
            return response()->json(['error' => 'Invalid verification code'], 401);
        }

        $customer->update([
            'password' => Hash::make($request->password),
            'verification_code' => null,
        ]);


        return response()->json([
            'message' => 'Password reset successfully',
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}