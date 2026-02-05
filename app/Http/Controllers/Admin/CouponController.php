<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.ecommerce.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.ecommerce.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'min_spend' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status');
        $data['code'] = strtoupper($request->code); // Kodları böyük hərflə saxlayırıq

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Kupon yaradıldı.');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.ecommerce.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,'.$id,
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'min_spend' => 'nullable|numeric|min:0',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status');
        $data['code'] = strtoupper($request->code);

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Kupon yeniləndi.');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect()->back()->with('success', 'Kupon silindi.');
    }
}
