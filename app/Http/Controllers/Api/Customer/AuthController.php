<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\RegisterRequest;
use App\Http\Requests\Customer\LoginRequest;
use App\Http\Requests\Customer\ProfileUpdateRequest;
use App\Models\Customer;
use App\Notifications\SendVerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $verificationCode = rand(1000, 9999);

        $customer = Customer::create([
            'phone' => $request->phone,
            'verification_code' => $verificationCode,
        ]);

        // Notification::send($customer, new SendVerificationCode($verificationCode));

        return $this->responseSuccess([
            'message' => __('messages.verification_sent'),
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
            return $this->responseError($validator->errors(), 422);
        }

        $customer = Customer::where('phone', $request->phone)
            ->where('verification_code', $request->verification_code)
            ->first();

        if (!$customer) {
            return $this->responseError(__('messages.invalid_verification_code'), 401);
        }

        $customer->update([
            'phone_verified_at' => now(),
            'verification_code' => null,
        ]);

        return $this->responseSuccess([
            'message' => __('messages.phone_verified'),
            'customer' => $customer
        ]);
    }

    public function completeRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:customers,phone|unique:customers,phone',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors(), 422);
        }

        $customer = Customer::where('phone', $request->phone)->first();

        if (!$customer->phone_verified_at) {
            return $this->responseError(__('messages.phone_not_verified'), 401);
        }

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $customer->createToken('customer-token')->plainTextToken;

        return $this->responseSuccess([
            'customer' => $customer,
            'token' => $token
        ]);
    }

    public function login(LoginRequest $request)
    {
        $customer = Customer::where('phone', $request->phone)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return $this->responseError(__('messages.invalid_credentials'), 401);
        }

        $token = $customer->createToken('customer-token')->plainTextToken;

        return $this->responseSuccess([
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->responseSuccess(['message' => __('messages.logged_out')]);
    }

    public function profile()
    {
        $customer = auth()->guard('customer-api')->user();

        return $this->responseSuccess([
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'image' => $customer->image ? asset('uploads/customers/' . $customer->image) : null,
            'birth_date' => $customer->birth_date,
            'region' => $customer->region,
            'district' => $customer->district,
            'nationality' => $customer->nationality,
        ]);
    }

    public function updateProfile(ProfileUpdateRequest $request)
    {
        $customer = auth()->guard('customer-api')->user();

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/customers'), $imageName);
            $data['image'] = $imageName;
        }

        $customer->update($data);

        return $this->responseSuccess(['message' => __('messages.profile_updated')]);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:customers',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors(), 422);
        }

        $verificationCode = rand(1000, 9999);

        $customer = Customer::where('phone', $request->phone)->first();
        $customer->update(['verification_code' => $verificationCode]);

        return $this->responseSuccess([
            'message' => __('messages.verification_sent'),
            'phone' => $customer->phone
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:customers',
            'verification_code' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors(), 422);
        }

        $customer = Customer::where('phone', $request->phone)
            ->where('verification_code', $request->verification_code)
            ->first();

        if (!$customer) {
            return $this->responseError(__('messages.invalid_verification_code'), 401);
        }

        $customer->update([
            'password' => Hash::make($request->password),
            'verification_code' => null,
        ]);

        return $this->responseSuccess([
            'message' => __('messages.password_reset')
        ]);
    }

    protected function responseSuccess($data, $status = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data
        ], $status);
    }

    protected function responseError($message, $status = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $status);
    }
}