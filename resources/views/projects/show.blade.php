<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->title }} — Portofolio</title>
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
        .section-number {
            font-size: 4rem;
            line-height: 1;
            font-weight: 800;
            color: transparent;
            -webkit-text-stroke: 1px rgba(15, 23, 42, 0.15);
        }
        .dark .section-number {
            -webkit-text-stroke: 1px rgba(255, 255, 255, 0.12);
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
                <a href="/#projects" class="text-slate-900 dark:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Projects</a>
                <a href="{{ route('blog.index') }}" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Blog</a>
                <a href="{{ route('guestbook.index') }}" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Guestbook</a>
                <a href="/#skills" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Skills</a>
                <a href="{{ route('contact.show') }}" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Contact</a>
                <div class="flex flex-col md:flex-row items-center gap-4 md:gap-6 border-t md:border-l border-slate-900/10 dark:border-white/10 pt-4 md:pt-0 md:pl-8 w-full md:w-auto">
                    @auth
                        <div class="flex flex-col md:flex-row items-center gap-4">
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 text-xs uppercase font-bold transition">Admin</a>
                            @endif
                            <span class="text-purple-600 dark:text-purple-400 text-xs font-mono uppercase tracking-widest">
                                {{ auth()->user()->username }}
                            </span>
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

    @php
        $technologies = $project->technologyList();
        $features = $project->featureList();
        $steps = $project->documentationSteps();
        $sectionNumber = 1;
    @endphp

    <header class="px-5 md:px-10 lg:px-20 pt-12 pb-10 md:pt-16 md:pb-14">
        <div class="max-w-7xl mx-auto">
            <a href="/#projects" class="inline-flex items-center gap-2 text-sm text-slate-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                Kembali ke Galeri Proyek
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">
                <div class="lg:col-span-7">
                    <span class="inline-block text-[11px] uppercase tracking-widest text-purple-600 dark:text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-full px-3 py-1 mb-5">
                        Studi Kasus / Project
                    </span>

                    <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight leading-tight mb-5 bg-gradient-to-b from-slate-900 dark:from-white to-slate-500 dark:to-gray-500 bg-clip-text text-transparent">
                        {{ $project->title }}
                    </h1>

                    @if($project->role)
                        <p class="text-sm text-slate-500 dark:text-gray-400 mb-4">
                            Peran: <span class="text-slate-900 dark:text-white font-semibold">{{ $project->role }}</span>
                        </p>
                    @endif

                    <p class="text-slate-600 dark:text-gray-300 text-lg leading-relaxed max-w-2xl">
                        {{ $project->description }}
                    </p>

                    @if(count($technologies))
                        <div class="flex flex-wrap gap-2 mt-6">
                            @foreach($technologies as $technology)
                                <span class="text-xs text-slate-700 dark:text-gray-300 bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-full px-3 py-1.5">
                                    {{ $technology }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    @if($project->demo_url || $project->source_url)
                        <div class="flex flex-wrap gap-3 mt-8">
                            @if($project->demo_url)
                                <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition shadow-[0_0_20px_rgba(147,51,234,0.25)]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                    Lihat Demo
                                </a>
                            @endif

                            @if($project->source_url)
                                <a href="{{ $project->source_url }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 border border-slate-900/15 dark:border-white/15 hover:border-purple-500/50 hover:text-purple-600 dark:hover:text-purple-400 text-slate-700 dark:text-gray-300 px-5 py-2.5 rounded-lg text-sm font-semibold transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/></svg>
                                    Source Code
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="lg:col-span-5">
                    @if($project->image)
                        <button type="button" class="project-card__media relative block w-full group"
                                data-project-preview
                                data-title="{{ $project->title }}"
                                data-role="{{ $project->role }}"
                                data-description="{{ Str::limit($project->description, 260) }}"
                                data-image="{{ asset('storage/' . $project->image) }}"
                                data-technologies="{{ implode(', ', $technologies) }}"
                                data-demo="{{ $project->demo_url }}"
                                data-source="{{ $project->source_url }}"
                                aria-label="Perbesar gambar {{ $project->title }}">
                            <div class="absolute inset-0 bg-purple-600/20 blur-[50px] rounded-full scale-90 opacity-70 z-0"></div>
                            <img src="{{ asset('storage/' . $project->image) }}" alt="Screenshot {{ $project->title }}"
                                 class="relative z-10 w-full aspect-[4/3] object-cover rounded-2xl border border-slate-900/10 dark:border-white/10 shadow-2xl">
                            <span class="project-card__preview-hint rounded-2xl">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                Perbesar
                            </span>
                        </button>
                    @else
                        <div class="w-full aspect-[4/3] rounded-2xl border border-dashed border-slate-900/10 dark:border-white/10 bg-white dark:bg-[#16161a] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-14 h-14 text-purple-500/40"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 8h6M9 12h6M9 16h4"/></svg>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    {{-- 01. Permasalahan --}}
    @if($project->problem)
        <section class="py-10 md:py-14 px-5 md:px-10 max-w-7xl mx-auto">
            <div class="flex items-baseline gap-5 mb-8 border-b border-slate-900/10 dark:border-white/10 pb-4">
                <span class="section-number">{{ str_pad($sectionNumber++, 2, '0', STR_PAD_LEFT) }}</span>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold tracking-tight">Permasalahan</h2>
                    <p class="text-slate-500 dark:text-gray-500 text-sm mt-1">Masalah yang ingin diselesaikan</p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 md:p-8">
                <p class="text-slate-700 dark:text-gray-300 leading-relaxed">{!! nl2br(e($project->problem)) !!}</p>
            </div>
        </section>
    @endif

    {{-- 02. Tujuan --}}
    @if($project->goal)
        <section class="py-10 md:py-14 px-5 md:px-10 max-w-7xl mx-auto">
            <div class="flex items-baseline gap-5 mb-8 border-b border-slate-900/10 dark:border-white/10 pb-4">
                <span class="section-number">{{ str_pad($sectionNumber++, 2, '0', STR_PAD_LEFT) }}</span>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold tracking-tight">Tujuan Project</h2>
                    <p class="text-slate-500 dark:text-gray-500 text-sm mt-1">Hasil yang ingin dicapai</p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 md:p-8">
                <p class="text-slate-700 dark:text-gray-300 leading-relaxed">{!! nl2br(e($project->goal)) !!}</p>
            </div>
        </section>
    @endif

    {{-- 03. Fitur --}}
    @if(count($features))
        <section class="py-10 md:py-14 px-5 md:px-10 max-w-7xl mx-auto">
            <div class="flex items-baseline gap-5 mb-8 border-b border-slate-900/10 dark:border-white/10 pb-4">
                <span class="section-number">{{ str_pad($sectionNumber++, 2, '0', STR_PAD_LEFT) }}</span>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold tracking-tight">Fitur Utama</h2>
                    <p class="text-slate-500 dark:text-gray-500 text-sm mt-1">Fitur-fitur yang tersedia dalam project</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($features as $feature)
                    <div class="flex items-start gap-3 bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-xl p-4">
                        <span class="mt-0.5 w-6 h-6 shrink-0 rounded-full bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-600 dark:text-purple-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M20 6 9 17l-5-5"/></svg>
                        </span>
                        <p class="text-sm text-slate-700 dark:text-gray-300 leading-relaxed">{{ $feature }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- 04. Dokumentasi Proses --}}
    @if(count($steps))
        <section class="py-10 md:py-14 px-5 md:px-10 max-w-7xl mx-auto">
            <div class="flex items-baseline gap-5 mb-8 border-b border-slate-900/10 dark:border-white/10 pb-4">
                <span class="section-number">{{ str_pad($sectionNumber++, 2, '0', STR_PAD_LEFT) }}</span>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold tracking-tight">Dokumentasi Proses Pekerjaan</h2>
                    <p class="text-slate-500 dark:text-gray-500 text-sm mt-1">Tahapan pengerjaan project dari awal hingga selesai</p>
                </div>
            </div>

            <div class="bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 md:p-10">
                <ol class="relative border-l border-purple-500/25 ml-4 space-y-8">
                    @foreach($steps as $index => $step)
                        <li class="ml-8 relative">
                            <span class="absolute -left-[3.05rem] top-0 w-8 h-8 rounded-full bg-purple-600 text-white text-xs font-bold flex items-center justify-center shadow-[0_0_15px_rgba(147,51,234,0.35)]">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <p class="text-slate-700 dark:text-gray-300 leading-relaxed">{{ $step }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    {{-- 05. Detail Project --}}
    @if($project->detail)
        <section class="py-10 md:py-14 px-5 md:px-10 max-w-7xl mx-auto">
            <div class="flex items-baseline gap-5 mb-8 border-b border-slate-900/10 dark:border-white/10 pb-4">
                <span class="section-number">{{ str_pad($sectionNumber++, 2, '0', STR_PAD_LEFT) }}</span>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold tracking-tight">Detail Project</h2>
                    <p class="text-slate-500 dark:text-gray-500 text-sm mt-1">Cerita lengkap di balik pengerjaan project</p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 md:p-8">
                <p class="text-slate-700 dark:text-gray-300 leading-relaxed">{!! nl2br(e($project->detail)) !!}</p>
            </div>
        </section>
    @endif

    {{-- 06. Feedback User --}}
    @if($project->feedback)
        <section class="py-10 md:py-14 px-5 md:px-10 max-w-7xl mx-auto">
            <div class="flex items-baseline gap-5 mb-8 border-b border-slate-900/10 dark:border-white/10 pb-4">
                <span class="section-number">{{ str_pad($sectionNumber++, 2, '0', STR_PAD_LEFT) }}</span>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold tracking-tight">Feedback User</h2>
                    <p class="text-slate-500 dark:text-gray-500 text-sm mt-1">Testimoni dan masukan dari pengguna</p>
                </div>
            </div>

            <figure class="relative bg-gradient-to-br from-purple-50 dark:from-[#1a1428] to-white dark:to-[#16161a] border border-purple-500/25 rounded-3xl p-6 md:p-10 overflow-hidden">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-purple-600/20 blur-[80px] rounded-full"></div>
                <div class="relative z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="currentColor" class="w-9 h-9 text-purple-500/40 mb-4"><path d="M10 11H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v8a4 4 0 0 1-4 4H6"/><path d="M20 11h-4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v8a4 4 0 0 1-4 4h-2"/></svg>
                    <blockquote class="text-slate-700 dark:text-gray-200 text-lg md:text-xl leading-relaxed max-w-3xl">
                        &ldquo;{{ $project->feedback }}&rdquo;
                    </blockquote>
                    @if($project->feedback_author)
                        <figcaption class="mt-6 flex items-center gap-3">
                            <span class="w-9 h-9 rounded-full bg-purple-600 text-white text-sm font-bold flex items-center justify-center">
                                {{ strtoupper(mb_substr($project->feedback_author, 0, 1)) }}
                            </span>
                            <span class="text-sm text-slate-500 dark:text-gray-400">{{ $project->feedback_author }}</span>
                        </figcaption>
                    @endif
                </div>
            </figure>
        </section>
    @endif

    <div class="px-5 md:px-10 max-w-7xl mx-auto pb-16">
        <a href="/#projects" class="inline-flex items-center gap-2 text-sm font-semibold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Lihat Project Lainnya
        </a>
    </div>

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
