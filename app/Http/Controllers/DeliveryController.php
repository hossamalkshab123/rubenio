<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deliveries = Delivery::paginate(10);
        return view('admin.deliveries.index', compact('deliveries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.deliveries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:deliveries',
            'phone' => 'required|numeric|unique:deliveries',
            'password' => 'required|string|min:6',
        ]);
        Delivery::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.deliveries.index')->with('success', 'تمت إضافة عامل التوصيل بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $delivery = Delivery::findOrFail($id);
        return view('admin.deliveries.edit', compact('delivery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Delivery $delivery )
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:deliveries,email,' . $delivery->id,
            'phone' => 'required|numeric|unique:deliveries,phone,' . $delivery->id,
            'password' => 'nullable|string|min:6',
        ]);

        $delivery->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password ? Hash::make($request->password) : $delivery->password,
        ]);

        return redirect()->route('admin.deliveries.index')->with('success', 'تم تحديث عامل التوصيل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delivery $delivery )
    {
        $delivery->delete();
        return redirect()->route('admin.deliveries.index')->with('success', 'تم حذف عامل التوصيل بنجاح');
    }
}
