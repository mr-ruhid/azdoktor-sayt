<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReplyMail;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(15);
        return view('admin.contacts.index', compact('contacts'));
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        // Mesajı oxunmuş kimi işarələ
        if (!$contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|min:10',
        ]);

        $contact = Contact::findOrFail($id);

        // 1. SMTP Ayarlarını Yoxla
        $mailHost = config('mail.mailers.smtp.host');
        $mailUsername = config('mail.mailers.smtp.username');
        $mailPassword = config('mail.mailers.smtp.password');

        if (empty($mailHost) || empty($mailUsername) || empty($mailPassword)) {
            return redirect()->back()->with('error', 'Xəta: SMTP ayarları qurulmayıb! Zəhmət olmasa "Tənzimləmələr -> SMTP" bölməsindən e-poçt ayarlarını daxil edin.');
        }

        // 2. E-poçtu Göndər
        try {
            Mail::to($contact->email)->send(new ContactReplyMail($request->message, $contact->subject));

            // 3. Statusu Yenilə
            $contact->update(['is_replied' => true]);

            return redirect()->back()->with('success', 'Cavab uğurla göndərildi.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'E-poçt göndərilərkən xəta baş verdi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Mesaj silindi.');
    }
}
