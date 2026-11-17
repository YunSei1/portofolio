<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of incoming messages.
     */
    public function index()
    {
        $messages = Message::latest()->get();
        $unreadCount = Message::where('is_read', false)->count();

        return view('messages.index', compact('messages', 'unreadCount'));
    }

    /**
     * Display the specified message (marks it as read).
     */
    public function show(Message $message)
    {
        // Tandai pesan sebagai sudah dibaca saat dibuka
        if (! $message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('messages.show', compact('message'));
    }

    /**
     * Toggle the read status of a message.
     */
    public function toggleRead(Message $message)
    {
        $message->update(['is_read' => ! $message->is_read]);

        $status = $message->is_read ? 'sudah dibaca' : 'belum dibaca';

        return back()->with('success', "Pesan ditandai {$status}.");
    }

    /**
     * Remove the specified message from storage.
     */
    public function destroy(Message $message)
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Pesan berhasil dihapus!');
    }
}
