<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kesan &amp; Pesan — Portofolio</title>
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
<body class="bg-[#f5f6fa] dark:bg-[#0b0b0f] text-slate-900 dark:text-white min-h-screen overflow-x-hidden">

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
                <a href="{{ route('guestbook.index') }}" class="text-slate-900 dark:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Guestbook</a>
                <a href="{{ route('contact.show') }}" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Contact</a>
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

    <section class="max-w-6xl mx-auto px-5 md:px-10 pt-16 pb-24 relative z-10">
        <div class="text-center mb-12" data-reveal>
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tighter bg-gradient-to-b from-slate-900 dark:from-white to-slate-500 dark:to-gray-500 bg-clip-text text-transparent">Kesan &amp; Pesan</h1>
            <p class="text-slate-500 dark:text-gray-400 text-sm md:text-base mt-3 max-w-xl mx-auto">
                Terima kasih sudah mampir! Tinggalkan kesan dan pesanmu untuk portofolio ini.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Formulir --}}
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-28">
                    <div class="bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 sm:p-8" data-reveal>
                        <h2 class="text-lg font-bold mb-1">Tulis Kesan &amp; Pesan</h2>
                        <p class="text-slate-500 dark:text-gray-400 text-xs mb-6">Bagikan pendapat atau masukanmu.</p>

                        @if(session('success'))
                            <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-3 text-sm text-green-600 dark:text-green-400 mb-5">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-3 text-sm text-red-500 dark:text-red-400 mb-5">
                                @foreach($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('guestbook.store') }}" method="POST" class="flex flex-col gap-5">
                            @csrf

                            <div class="flex flex-col gap-1.5">
                                <label for="name" class="text-xs text-slate-500 dark:text-gray-400 font-medium uppercase tracking-widest ml-1">Nama</label>
                                <input id="name" type="text" name="name" maxlength="60" placeholder="Nama kamu" value="{{ old('name') }}"
                                       class="bg-transparent border border-slate-900/10 dark:border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-slate-400 dark:placeholder:text-gray-600 @error('name') border-red-500 @enderror" required>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label for="message" class="text-xs text-slate-500 dark:text-gray-400 font-medium uppercase tracking-widest ml-1">Kesan &amp; Pesan</label>
                                <textarea id="message" name="message" rows="5" maxlength="500" placeholder="Tulis kesan dan pesanmu..."
                                          class="bg-transparent border border-slate-900/10 dark:border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-slate-400 dark:placeholder:text-gray-600 resize-none @error('message') border-red-500 @enderror" required>{{ old('message') }}</textarea>
                            </div>

                            <button type="submit"
                                    class="bg-purple-600 hover:bg-purple-700 text-white p-3.5 rounded-lg font-semibold transition shadow-[0_0_20px_rgba(147,51,234,0.3)] hover:shadow-[0_0_30px_rgba(147,51,234,0.5)]">
                                Kirim Kesan &amp; Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Daftar kesan & pesan --}}
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-6" data-reveal>
                    <h2 class="text-lg font-bold">Pesan Pengunjung</h2>
                    <span class="text-xs font-mono uppercase tracking-widest text-purple-600 dark:text-purple-400">{{ $guestMessages->count() }} pesan</span>
                </div>

                @forelse($guestMessages as $entry)
                    <article class="bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 mb-4 hover:border-purple-500/40 transition-colors duration-300">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 shrink-0 rounded-full bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-600 dark:text-purple-400 font-bold">
                                {{ strtoupper(mb_substr($entry->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold truncate">{{ $entry->name }}</p>
                                <p class="text-xs text-slate-400 dark:text-gray-500">{{ $entry->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <p class="text-slate-600 dark:text-gray-300 leading-relaxed whitespace-pre-line break-words">{{ $entry->message }}</p>
                    </article>
                @empty
                    <div class="bg-white dark:bg-[#16161a] border border-dashed border-slate-900/15 dark:border-white/10 rounded-2xl p-12 text-center">
                        <div class="text-4xl mb-4">💬</div>
                        <h3 class="text-lg font-bold mb-2">Belum ada kesan &amp; pesan</h3>
                        <p class="text-slate-500 dark:text-gray-400 text-sm">Jadilah yang pertama meninggalkan kesan dan pesan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

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
