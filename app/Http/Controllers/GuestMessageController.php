<?php

namespace App\Http\Controllers;

use App\Models\GuestMessage;
use Illuminate\Http\Request;

class GuestMessageController extends Controller
{
    /**
     * Halaman publik "Kesan & Pesan" (buku tamu): form + daftar pesan tamu.
     */
    public function index()
    {
        $guestMessages = GuestMessage::latest()->get();

        return view('guestbook', compact('guestMessages'));
    }

    /**
     * Simpan kesan & pesan baru dari pengunjung.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:60',
            'message' => 'required|string|max:500',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 60 karakter.',
            'message.required' => 'Kesan & pesan wajib diisi.',
            'message.max' => 'Kesan & pesan maksimal 500 karakter.',
        ]);

        GuestMessage::create($validated);

        return redirect()->route('guestbook.index')
            ->with('success', 'Terima kasih! Kesan dan pesanmu sudah tersimpan.');
    }

    /**
     * Daftar kesan & pesan untuk admin.
     */
    public function adminIndex()
    {
        $guestMessages = GuestMessage::latest()->get();

        return view('guest-messages.index', compact('guestMessages'));
    }

    /**
     * Hapus kesan & pesan.
     */
    public function destroy(GuestMessage $guestMessage)
    {
        $guestMessage->delete();

        return redirect()->route('admin.guest-messages.index')
            ->with('success', 'Kesan & pesan berhasil dihapus!');
    }
}
