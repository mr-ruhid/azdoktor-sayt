<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        // Yalnız 'product' tipli sifarişlərin ödənişlərini gətiririk
        $payments = Payment::whereHas('order', function($query) {
            $query->where('type', 'product');
        })->with('order')->latest('paid_at')->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    // Ödəniş silmək (Ehtiyat üçün)
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->back()->with('success', 'Ödəniş qeydi silindi.');
    }
}
