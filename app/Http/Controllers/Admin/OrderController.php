<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Sifarişlərin Siyahısı
     */
    public function index(Request $request)
    {
        // Bütün sifarişləri gətiririk (Ən yenilər yuxarıda)
        $orders = Order::with('user') // İstifadəçi varsa gətirsin
                       ->latest()
                       ->paginate(15);

        return view('admin.ecommerce.orders.index', compact('orders'));
    }

    /**
     * Sifariş Detalları
     */
    public function show($id)
    {
        // Sifariş, Məhsullar və Məhsulun özü (orderable) ilə birlikdə
        $order = Order::with(['items.orderable', 'user'])->findOrFail($id);

        return view('admin.ecommerce.orders.show', compact('order'));
    }

    /**
     * Status Yeniləmə
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed'
        ]);

        $data = [
            'status' => $request->status,
        ];

        if ($request->has('payment_status')) {
            $data['payment_status'] = $request->payment_status;
        }

        $order->update($data);

        return redirect()->back()->with('success', 'Sifariş statusu yeniləndi.');
    }

    /**
     * Sifarişi Silmək
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        // Sifariş silinəndə items avtomatik silinir (cascade varsa)
        $order->delete();

        return redirect()->back()->with('success', 'Sifariş silindi.');
    }
}
