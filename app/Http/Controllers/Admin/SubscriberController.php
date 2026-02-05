<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriber;

class SubscriberController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::latest()->paginate(20);
        return view('admin.subscribers.index', compact('subscribers'));
    }

    // Abunəçini silmək
    public function destroy($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();

        return redirect()->back()->with('success', 'Abunəçi siyahıdan silindi.');
    }

    // Statusu dəyişmək (Opsional)
    public function updateStatus($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->update(['is_active' => !$subscriber->is_active]);

        return redirect()->back()->with('success', 'Status yeniləndi.');
    }
}
