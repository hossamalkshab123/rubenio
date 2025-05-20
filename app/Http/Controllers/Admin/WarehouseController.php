<?php
namespace App\Http\Controllers\Admin;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::paginate(10);
        return view('admin.warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('admin.warehouses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:warehouses',
            'phone' => 'required|numeric|unique:warehouses',
            'password' => 'required|string|min:6',
        ]);

        Warehouse::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.warehouses.index')->with('success', 'تمت إضافة المستودع بنجاح');
    }

    public function edit(Warehouse $warehouse)
    {
        return view('admin.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:warehouses,email,' . $warehouse->id,
            'phone' => 'required|numeric|unique:warehouses,phone,' . $warehouse->id,
            'password' => 'nullable|string|min:6',
        ]);

        $warehouse->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password ? Hash::make($request->password) : $warehouse->password,
        ]);

        return redirect()->route('admin.warehouses.index')->with('success', 'تم تحديث المستودع بنجاح');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('admin.warehouses.index')->with('success', 'تم حذف المستودع بنجاح');
    }
}
