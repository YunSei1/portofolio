<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubungi Saya — Portofolio</title>
    @vite('resources/css/app.css')
    @include('partials.theme-head')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glow {
            position: absolute;
            width: min(600px, 100%);
            height: 600px;
            background: radial-gradient(circle, rgba(94, 59, 238, 0.15) 0%, rgba(0,0,0,0) 70%);
            top: -150px;
            left: 50%;
            transform: translateX(-50%);
            z-index: -1;
        }
    </style>
</head>
<body class="bg-[#f5f6fa] dark:bg-[#0b0b0f] text-slate-900 dark:text-white min-h-screen">

    <div class="glow"></div>

    <nav class="flex justify-between items-center px-5 md:px-10 py-6 sticky top-0 backdrop-blur-md bg-[#f5f6fa]/80 dark:bg-[#0b0b0f]/50 z-50 border-b border-slate-900/10 dark:border-white/10">
        <a href="/" class="text-xl font-bold tracking-tighter hover:text-purple-600 dark:hover:text-purple-400 transition">PORTOFOLIO.</a>

        <div class="flex items-center gap-2 md:gap-4">
            <button id="menuBtn" class="md:hidden text-slate-500 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white transition"
                    onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
            </button>

            <div id="mobileMenu" class="hidden flex md:flex absolute md:static top-full left-0 w-full md:w-auto flex-col md:flex-row gap-6 md:gap-8 text-sm text-slate-500 dark:text-gray-400 font-medium items-center bg-[#f5f6fa] dark:bg-[#0b0b0f] md:bg-transparent border-b md:border-0 border-slate-900/10 dark:border-white/10 px-5 md:px-0 py-6 md:py-0">
                <a href="/" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Home</a>
                <a href="{{ route('about') }}" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">About</a>
                <a href="/#projects" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Projects</a>
                <a href="{{ route('blog.index') }}" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Blog</a>
                <a href="{{ route('guestbook.index') }}" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Guestbook</a>
                <a href="{{ route('contact.show') }}" class="text-slate-900 dark:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Contact</a>
                <div class="flex flex-col md:flex-row items-center gap-4 md:gap-6 border-t md:border-l border-slate-900/10 dark:border-white/10 pt-4 md:pt-0 md:pl-8 w-full md:w-auto">
                    @auth
                        <div class="flex flex-col md:flex-row items-center gap-4">
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 text-xs uppercase font-bold transition">Admin</a>
                            @endif
                            <span class="text-purple-600 dark:text-purple-400 text-xs font-mono uppercase tracking-widest">{{ auth()->user()->username }}</span>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="text-slate-500 dark:text-gray-400 hover:text-red-400 text-xs transition uppercase font-bold">Logout</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-slate-900 dark:hover:text-white transition">Login</a>
                    @endauth
                </div>
            </div>

            @include('partials.theme-toggle')
        </div>
    </nav>

    <div class="min-h-screen flex items-center justify-center px-4 py-24 relative z-10">
        <div class="w-full max-w-lg" data-reveal>
            <div class="text-center mb-10">
                <h1 class="text-4xl font-extrabold tracking-tighter bg-gradient-to-b from-slate-900 dark:from-white to-slate-500 dark:to-gray-500 bg-clip-text text-transparent">Hubungi Saya</h1>
                <p class="text-slate-500 dark:text-gray-400 text-sm mt-2">Punya pertanyaan atau ingin bekerja sama? Kirim pesan!</p>
            </div>

            <div class="bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 sm:p-8">
                @if(session('success'))
                    <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-3 text-sm text-green-400 mb-6">{{ session('success') }}</div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST" class="flex flex-col gap-5">
                    @csrf

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs text-slate-500 dark:text-gray-400 font-medium uppercase tracking-widest ml-1">Nama</label>
                        <input type="text" name="name" placeholder="Nama lengkap" value="{{ old('name') }}"
                               class="bg-transparent border border-slate-900/10 dark:border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-slate-400 dark:placeholder:text-gray-600" required>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs text-slate-500 dark:text-gray-400 font-medium uppercase tracking-widest ml-1">Email</label>
                        <input type="email" name="email" placeholder="email@contoh.com" value="{{ old('email') }}"
                               class="bg-transparent border border-slate-900/10 dark:border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-slate-400 dark:placeholder:text-gray-600" required>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs text-slate-500 dark:text-gray-400 font-medium uppercase tracking-widest ml-1">Pesan</label>
                        <textarea name="message" rows="5" placeholder="Tulis pesan kamu..."
                                  class="bg-transparent border border-slate-900/10 dark:border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-slate-400 dark:placeholder:text-gray-600 resize-none" required>{{ old('message') }}</textarea>
                    </div>

                    <button type="submit"
                            class="bg-purple-600 hover:bg-purple-700 text-white p-3.5 rounded-lg font-semibold transition shadow-[0_0_20px_rgba(147,51,234,0.3)] hover:shadow-[0_0_30px_rgba(147,51,234,0.5)]">
                        Kirim Pesan
                    </button>
                </form>
            </div>

            <div class="mt-8 text-center">
                <a href="/" class="text-slate-500 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white text-sm transition">&larr; Kembali ke Beranda</a>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('click', function(e) {
        var menu = document.getElementById('mobileMenu');
        var btn = document.getElementById('menuBtn');
        if (menu && btn && !menu.contains(e.target) && !btn.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
    </script>

</body>
</html>
