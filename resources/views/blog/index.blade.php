<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Pengembangan — Portofolio</title>
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
<body class="bg-[#f5f6fa] dark:bg-[#0b0b0f] text-slate-900 dark:text-white overflow-x-hidden">

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
                <a href="{{ route('blog.index') }}" class="text-slate-900 dark:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Blog</a>
                <a href="{{ route('guestbook.index') }}" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Guestbook</a>
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

    <header class="px-5 md:px-10 lg:px-20 pt-16 pb-10 md:pt-24 md:pb-14">
        <div class="max-w-7xl mx-auto">
            <span class="inline-block text-[11px] uppercase tracking-widest text-purple-600 dark:text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-full px-3 py-1 mb-5">
                Blog / Tulisan
            </span>

            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tighter mb-5 bg-gradient-to-b from-slate-900 dark:from-white to-slate-500 dark:to-gray-500 bg-clip-text text-transparent leading-tight">
                Blog Pengembangan
            </h1>

            <p class="text-slate-500 dark:text-gray-400 text-lg max-w-2xl">
                Catatan, tutorial, dan pengalaman seputar dunia pengembangan web — dari hal teknis sampai pelajaran di balik sebuah proyek.
            </p>

            <form action="{{ route('blog.index') }}" method="GET" class="mt-8 max-w-2xl">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-gray-500 pointer-events-none">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                        </svg>
                        <input type="search" name="q" value="{{ $q }}" placeholder="Cari artikel blog..." aria-label="Cari artikel"
                               class="w-full bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-lg pl-10 pr-4 py-3 text-sm text-slate-700 dark:text-gray-200 placeholder:text-slate-400 dark:placeholder:text-gray-500 focus:border-purple-500 outline-none transition">
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg text-sm font-semibold transition shadow-[0_0_15px_rgba(147,51,234,0.25)]">
                        Cari Artikel
                    </button>
                    @if($q !== '')
                        <a href="{{ route('blog.index') }}" class="inline-flex items-center justify-center text-slate-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 text-sm font-medium transition px-2">Reset</a>
                    @endif
                </div>
            </form>

            @if($q !== '')
                <p class="text-sm text-slate-500 dark:text-gray-400 mt-4">
                    Menampilkan {{ $blogs->total() }} artikel untuk "<span class="text-slate-900 dark:text-white font-semibold">{{ $q }}</span>"
                </p>
            @endif
        </div>
    </header>

    <section class="px-5 md:px-10 max-w-7xl mx-auto pb-16">
        @if($blogs->isEmpty())
            <div class="text-center py-20 border border-dashed border-slate-900/10 dark:border-white/10 rounded-2xl">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-600 dark:text-purple-400 mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-[26px] h-[26px]">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                </div>

                @if($q !== '')
                    <h2 class="text-xl font-bold mb-2">Tidak ada artikel yang cocok</h2>
                    <p class="text-slate-500 dark:text-gray-400 text-sm mb-6">Coba kata kunci lain atau lihat semua artikel yang tersedia.</p>
                    <a href="{{ route('blog.index') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg font-semibold transition text-sm">Lihat Semua Artikel</a>
                @else
                    <h2 class="text-xl font-bold mb-2">Belum ada artikel</h2>
                    <p class="text-slate-500 dark:text-gray-400 text-sm">Artikel blog akan muncul di sini setelah dipublikasikan.</p>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($blogs as $blog)
                    <article class="group flex flex-col bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl overflow-hidden hover:border-purple-500/50 transition-all duration-500">
                        <a href="{{ route('blog.show', $blog->slug) }}" class="block relative overflow-hidden">
                            @if($blog->cover_image)
                                <img src="{{ asset('storage/' . $blog->cover_image) }}" alt="Cover {{ $blog->title }}"
                                     class="w-full h-52 object-cover opacity-90 group-hover:opacity-100 group-hover:scale-105 transition duration-700">
                            @else
                                <div class="w-full h-52 bg-gradient-to-br from-purple-500/10 to-fuchsia-500/10 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12 text-purple-500/40">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </a>

                        <div class="flex flex-col flex-1 p-6">
                            <div class="flex flex-wrap items-center gap-2 text-[11px] uppercase tracking-widest text-purple-600 dark:text-purple-400 mb-3">
                                <span>{{ $blog->displayDate()->format('d M Y') }}</span>
                                <span class="text-slate-300 dark:text-gray-600">•</span>
                                <span>{{ $blog->readingTime() }} menit baca</span>
                            </div>

                            @if($blog->tagList() !== [])
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach($blog->tagList() as $tag)
                                        <span class="text-[11px] text-slate-500 dark:text-gray-400 bg-slate-900/5 dark:bg-white/5 border border-slate-900/10 dark:border-white/10 rounded-full px-2.5 py-1">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <h2 class="text-xl font-bold mb-3 leading-snug">
                                <a href="{{ route('blog.show', $blog->slug) }}" class="hover:text-purple-600 dark:hover:text-purple-400 transition">{{ $blog->title }}</a>
                            </h2>

                            <p class="text-slate-500 dark:text-gray-400 text-sm leading-relaxed mb-6">
                                {{ Str::limit($blog->summary(), 160) }}
                            </p>

                            <a href="{{ route('blog.show', $blog->slug) }}" class="mt-auto inline-flex items-center gap-2 text-sm font-semibold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition">
                                Baca Artikel
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            @if($blogs->hasPages())
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-10">
                    <p class="text-sm text-slate-500 dark:text-gray-500">
                        Menampilkan {{ $blogs->firstItem() }}–{{ $blogs->lastItem() }} dari {{ $blogs->total() }} artikel
                    </p>
                    <div class="flex items-center gap-2">
                        @if($blogs->onFirstPage())
                            <span class="px-4 py-2 rounded-lg border border-slate-900/10 dark:border-white/10 text-slate-400 dark:text-gray-600 text-sm cursor-not-allowed">Sebelumnya</span>
                        @else
                            <a href="{{ $blogs->previousPageUrl() }}" class="px-4 py-2 rounded-lg border border-slate-900/10 dark:border-white/10 text-slate-600 dark:text-gray-300 hover:border-purple-500/50 hover:text-purple-600 dark:hover:text-purple-400 text-sm transition">Sebelumnya</a>
                        @endif

                        <span class="px-3 py-2 text-sm text-slate-500 dark:text-gray-500">{{ $blogs->currentPage() }} / {{ $blogs->lastPage() }}</span>

                        @if($blogs->hasMorePages())
                            <a href="{{ $blogs->nextPageUrl() }}" class="px-4 py-2 rounded-lg border border-slate-900/10 dark:border-white/10 text-slate-600 dark:text-gray-300 hover:border-purple-500/50 hover:text-purple-600 dark:hover:text-purple-400 text-sm transition">Berikutnya</a>
                        @else
                            <span class="px-4 py-2 rounded-lg border border-slate-900/10 dark:border-white/10 text-slate-400 dark:text-gray-600 text-sm cursor-not-allowed">Berikutnya</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </section>

    <footer class="border-t border-slate-900/10 dark:border-white/10 py-8 text-center text-slate-500 dark:text-gray-500 text-sm">
        &copy; {{ date('Y') }} {{ $profile?->name ?? 'Muhammad Rizky' }}. All rights reserved.
    </footer>

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
