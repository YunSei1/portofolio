<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $blog->title }} — Blog Portofolio</title>
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
                <a href="{{ url('/') }}#about" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">About</a>
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

    <header class="px-5 md:px-10 lg:px-20 pt-12 pb-10 md:pt-16 md:pb-12">
        <div class="max-w-4xl mx-auto">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                Kembali ke Blog
            </a>

            <span class="inline-block text-[11px] uppercase tracking-widest text-purple-600 dark:text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-full px-3 py-1 mb-5">
                Blog / Artikel
            </span>

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight leading-tight mb-5 bg-gradient-to-b from-slate-900 dark:from-white to-slate-500 dark:to-gray-500 bg-clip-text text-transparent">
                {{ $blog->title }}
            </h1>

            <div class="flex flex-wrap items-center gap-3 text-sm text-slate-500 dark:text-gray-400">
                <span class="inline-flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-[15px] h-[15px]"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    {{ $blog->displayDate()->format('d M Y') }}
                </span>
                <span class="text-slate-300 dark:text-gray-600">•</span>
                <span class="inline-flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-[15px] h-[15px]"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    {{ $blog->readingTime() }} menit baca
                </span>
            </div>

            @if($blog->tagList() !== [])
                <div class="flex flex-wrap gap-2 mt-5">
                    @foreach($blog->tagList() as $tag)
                        <span class="text-[11px] uppercase tracking-widest text-purple-600 dark:text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-full px-3 py-1">{{ $tag }}</span>
                    @endforeach
                </div>
            @endif

            @if($blog->summary())
                <p class="text-slate-600 dark:text-gray-300 text-lg leading-relaxed mt-6 max-w-3xl">
                    {{ $blog->summary(220) }}
                </p>
            @endif
        </div>
    </header>

    @if($blog->cover_image)
        <div class="px-5 md:px-10 max-w-4xl mx-auto pb-10">
            <div class="relative group">
                <div class="absolute inset-0 bg-purple-600/20 blur-[50px] rounded-full scale-90 opacity-70 z-0"></div>
                <img src="{{ asset('storage/' . $blog->cover_image) }}" alt="Cover {{ $blog->title }}"
                     class="relative z-10 w-full aspect-[16/9] object-cover rounded-2xl border border-slate-900/10 dark:border-white/10 shadow-2xl">
            </div>
        </div>
    @endif

    <article class="px-5 md:px-10 pb-16">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 md:p-10">
                <div class="text-slate-700 dark:text-gray-300 leading-relaxed text-base md:text-lg">
                    @foreach(preg_split('/\r\n|\r|\n+/', $blog->content) as $paragraph)
                        @if(trim($paragraph) !== '')
                            <p class="mb-4 last:mb-0">{{ $paragraph }}</p>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    Kembali ke Blog
                </a>
                <span class="text-xs text-slate-400 dark:text-gray-500">Dipublikasikan {{ $blog->displayDate()->format('d M Y') }}</span>
            </div>
        </div>
    </article>

    @if($related->isNotEmpty())
        <section class="px-5 md:px-10 max-w-7xl mx-auto pb-16">
            <div class="border-t border-slate-900/10 dark:border-white/10 pt-10">
                <h2 class="text-2xl font-bold mb-6">Artikel Lainnya</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($related as $item)
                        <a href="{{ route('blog.show', $item->slug) }}" class="group bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 hover:border-purple-500/50 transition-all duration-300">
                            <p class="text-[11px] uppercase tracking-widest text-purple-600 dark:text-purple-400 mb-3">
                                {{ $item->displayDate()->format('d M Y') }}
                            </p>
                            <h3 class="text-lg font-bold mb-2 leading-snug group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">
                                {{ $item->title }}
                            </h3>
                            <p class="text-slate-500 dark:text-gray-400 text-sm leading-relaxed">
                                {{ Str::limit($item->summary(), 110) }}
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

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
