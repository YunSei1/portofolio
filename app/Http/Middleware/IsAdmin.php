<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    // Mengambil user yang sedang login
    $user = auth()->user();

    // Pastikan user ada dan merupakan instance dari model User kita
    if ($user instanceof \App\Models\User && $user->role === 'admin') {
        return $next($request);
    }

    // Jika bukan admin, kembalikan ke home dengan pesan error
    return redirect('/')->with('error', 'Kamu tidak punya akses Admin.');
}
}
