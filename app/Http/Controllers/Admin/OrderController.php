<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Default olaraq 'product' sifarişləri gəlsin, əgər URL-də ?type=service yoxdursa
        $type = $request->query('type', 'product');

        $orders = Order::where('type', $type)
                       ->latest()
                       ->paginate(15)
                       ->appends(['type' => $type]);

        return view('admin.ecommerce.orders.index', compact('orders', 'type'));
    }

    public function show($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('admin.ecommerce.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'nullable|in:unpaid,paid,refunded'
        ]);

        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status ?? $order->payment_status
        ]);

        return redirect()->back()->with('success', 'Sifariş statusu yeniləndi.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->back()->with('success', 'Sifariş silindi.');
    }
}
