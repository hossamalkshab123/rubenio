<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
         
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'discount_percentage' => 'required|integer|min:1|max:100',
            'max_discount' => 'nullable|integer|min:1',
            'expires_at' => 'required|date|after:today',
        ]);
        //return $request->all();
       

        Coupon::create($request->all());

        return redirect()->route('admin.coupons.index')->with('success', 'تمت إضافة الكوبون بنجاح');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'discount_percentage' => 'required|integer|min:1|max:100',
            'max_discount' => 'nullable|integer|min:1',
            'expires_at' => 'required|date|after:today',
        ]);

        $coupon->update($request->all());

        return redirect()->route('admin.coupons.index')->with('success', 'تم تحديث الكوبون بنجاح');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'تم حذف الكوبون بنجاح');
    }
}
