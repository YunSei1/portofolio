<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\Message;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        // Simpan pesan ke database agar bisa dikelola admin lewat dashboard
        Message::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
            'is_read' => false,
        ]);

        // Kirim email (jika gagal, pesan tetap tersimpan di dashboard)
        try {
            $to = Profile::first()?->email ?: 'muhammadrizkyy232@gmail.com';

            Mail::to($to)->send(new ContactMail(
                $request->name,
                $request->email,
                $request->message,
            ));
        } catch (\Throwable $e) {
            // Email gagal terkirim, tapi pesan sudah tersimpan di database
        }

        return redirect()->back()->with('success', 'Pesan berhasil dikirim!');
    }
}
