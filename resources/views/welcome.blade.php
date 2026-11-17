<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite('resources/css/app.css')
        @include('partials.theme-head')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
    body { font-family: 'Inter', sans-serif; }
    /* Efek animasi gradient ala Linear */
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
    <div class="text-xl font-bold tracking-tighter">PORTOFOLIO.</div>

    <div class="flex items-center gap-2 md:gap-4">
    <button id="menuBtn" class="md:hidden text-slate-500 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
    </button>

    <div id="mobileMenu" class="hidden flex md:flex absolute md:static top-full left-0 w-full md:w-auto flex-col md:flex-row gap-6 md:gap-8 text-sm text-slate-500 dark:text-gray-400 font-medium items-center bg-[#f5f6fa] dark:bg-[#0b0b0f] md:bg-transparent border-b md:border-0 border-slate-900/10 dark:border-white/10 px-5 md:px-0 py-6 md:py-0">
        <a href="{{ route('about') }}" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">About</a>
        <a href="#projects" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Projects</a>
        <a href="{{ route('blog.index') }}" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Blog</a>
        <a href="{{ route('guestbook.index') }}" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Guestbook</a>
        @if($certificates->isNotEmpty())
            <a href="#certificates" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Certificates</a>
        @endif
        <a href="#skills" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Skills</a>
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

    <header class="min-h-screen flex items-center justify-center px-5 md:px-10 lg:px-20 relative pt-24 pb-16 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 items-center max-w-7xl mx-auto z-10 w-full">
            
            <div class="md:col-span-8 flex flex-col items-center md:items-start text-center md:text-left">
                <span class="px-3 py-1 rounded-full border border-slate-900/10 dark:border-white/10 text-xs text-purple-600 dark:text-purple-400 mb-6 bg-purple-500/10">
                    {{ $profile?->badge_text ?? 'Available for New Projects' }}
                </span>
                
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tighter mb-6 bg-gradient-to-b from-slate-900 dark:from-white to-slate-500 dark:to-gray-500 bg-clip-text text-transparent leading-tight w-full">
                    Halo Saya<br>{{ $profile?->name ?? 'Muhammad Rizky' }},<br>{{ $profile?->role ?? 'Front-End Developer' }}.
                </h1>
                
                <p class="text-slate-500 dark:text-gray-400 text-lg max-w-xl mb-10">
                    {{ $profile?->bio ?? 'Saya adalah seorang front-end developer yang bersemangat dalam menciptakan pengalaman digital yang menarik dan responsif.' }}
                </p>

                <div class="flex items-center gap-6 mb-10">
                    @if($profile?->instagram_url)
                        <a href="{{ $profile->instagram_url }}" target="_blank" class="text-slate-500 dark:text-gray-400 hover:text-pink-400 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                        </a>
                    @endif
                    @if($profile?->email)
                        <a href="mailto:{{ $profile->email }}" class="text-slate-500 dark:text-gray-400 hover:text-yellow-400 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </a>
                    @endif
                    @if($profile?->whatsapp_number)
                        <a href="https://wa.me/{{ $profile->whatsapp_number }}" target="_blank" class="text-slate-500 dark:text-gray-400 hover:text-green-400 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                        </a>
                    @endif
                </div>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    @if(\App\Models\Profile::hasCv())
                        <a href="{{ route('cv.download') }}" data-no-transition class="inline-flex items-center gap-2 border border-purple-500/40 text-purple-600 dark:text-purple-400 hover:bg-purple-500/10 px-8 py-3 rounded-lg font-medium transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Download CV
                        </a>
                    @endif
                </div>
            </div>

            <div class="md:col-span-4 flex justify-center items-center relative group">
                <div class="absolute inset-0 bg-purple-600/20 dark:bg-purple-600/30 blur-[60px] rounded-full scale-90 opacity-80 z-0"></div>
                
                <img 
                    src="{{ $profile?->photo ? asset('storage/' . $profile->photo) : asset('assets/img/iki.png') }}" 
                    alt="Foto Profil" 
                    class="relative z-10 w-full max-w-sm aspect-square md:aspect-[3/4] object-cover rounded-3xl border border-slate-900/10 dark:border-white/10 shadow-2xl transition-all duration-500 group-hover:scale-105 group-hover:border-purple-500/50"
                >

                
            </div>

        </div>
    </header>

    <section id="skills" class="py-16 md:py-24 px-5 md:px-10 max-w-7xl mx-auto">
    <div class="text-center mb-16">
        <span class="inline-block text-[11px] uppercase tracking-widest text-purple-600 dark:text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-full px-3 py-1 mb-4">
            Keahlian
        </span>
        <h2 class="text-4xl font-bold mb-4 bg-gradient-to-r from-slate-900 dark:from-white to-slate-500 dark:to-gray-500 bg-clip-text text-transparent">
            Keahlian / Skills
        </h2>
        <p class="text-slate-500 dark:text-gray-400">Kemampuan teknis dan non-teknis yang saya kuasai</p>
    </div>

    @php
        $hardSkills = [
            ['name' => 'HTML', 'level' => 90, 'icon' => 'https://raw.githubusercontent.com/devicons/devicon/master/icons/html5/html5-original.svg'],
            ['name' => 'CSS', 'level' => 60, 'icon' => 'https://raw.githubusercontent.com/devicons/devicon/master/icons/css3/css3-original.svg'],
            ['name' => 'Bootstrap', 'level' => 70, 'icon' => 'https://raw.githubusercontent.com/devicons/devicon/master/icons/bootstrap/bootstrap-original.svg'],
            ['name' => 'JavaScript', 'level' => 60, 'icon' => 'https://raw.githubusercontent.com/devicons/devicon/master/icons/javascript/javascript-original.svg'],
            ['name' => 'Tailwind CSS', 'level' => 70, 'icon' => 'https://raw.githubusercontent.com/devicons/devicon/master/icons/tailwindcss/tailwindcss-original.svg'],
            ['name' => 'PHP', 'level' => 70, 'icon' => 'https://raw.githubusercontent.com/devicons/devicon/master/icons/php/php-original.svg'],
            ['name' => 'MySQL', 'level' => 80, 'icon' => 'https://raw.githubusercontent.com/devicons/devicon/master/icons/mysql/mysql-original.svg'],
            ['name' => 'Figma', 'level' => 90, 'icon' => 'https://www.vectorlogo.zone/logos/figma/figma-icon.svg'],
        ];

        $softSkills = [
            ['name' => 'Komunikasi', 'level' => 85],
            ['name' => 'Kerja Sama Tim', 'level' => 90],
            ['name' => 'Tanggung Jawab', 'level' => 90],
            ['name' => 'Manajemen Waktu', 'level' => 80],
            ['name' => 'Kepemimpinan', 'level' => 75],
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">

        {{-- Hard Skills --}}
        <div class="bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-3xl p-6 md:p-8 hover:border-purple-500/40 transition-colors duration-300">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-11 h-11 shrink-0 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-600 dark:text-purple-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-[22px] h-[22px]"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold">Hard Skills</h3>
                    <p class="text-slate-500 dark:text-gray-400 text-sm">Kemampuan teknis</p>
                </div>
            </div>

            <div class="flex flex-col gap-6">
                @foreach($hardSkills as $skill)
                    <div>
                        <div class="flex items-center justify-between gap-4 mb-2.5">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-7 h-7 shrink-0 rounded-lg bg-slate-100 dark:bg-[#0b0b0f] border border-slate-900/5 dark:border-white/5 flex items-center justify-center">
                                    <img src="{{ $skill['icon'] }}" alt="{{ $skill['name'] }}" class="w-4 h-4">
                                </span>
                                <span class="text-sm font-medium text-slate-700 dark:text-gray-300 truncate">{{ $skill['name'] }}</span>
                            </div>
                            <span class="text-sm font-mono font-semibold text-purple-600 dark:text-purple-400">{{ $skill['level'] }}%</span>
                        </div>
                        <div class="h-2.5 rounded-full bg-slate-200/80 dark:bg-white/5 overflow-hidden"
                             role="progressbar" aria-valuenow="{{ $skill['level'] }}" aria-valuemin="0" aria-valuemax="100" aria-label="{{ $skill['name'] }}">
                            <div class="skill-progress" style="--progress: {{ $skill['level'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Soft Skills --}}
        <div class="bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-3xl p-6 md:p-8 hover:border-purple-500/40 transition-colors duration-300">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-11 h-11 shrink-0 rounded-xl bg-fuchsia-500/10 border border-fuchsia-500/20 flex items-center justify-center text-fuchsia-600 dark:text-fuchsia-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-[22px] h-[22px]"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold">Soft Skills</h3>
                    <p class="text-slate-500 dark:text-gray-400 text-sm">Kemampuan non-teknis</p>
                </div>
            </div>

            <div class="flex flex-col gap-6">
                @foreach($softSkills as $skill)
                    <div>
                        <div class="flex items-center justify-between gap-4 mb-2.5">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-1.5 h-1.5 shrink-0 rounded-full bg-fuchsia-500"></span>
                                <span class="text-sm font-medium text-slate-700 dark:text-gray-300 truncate">{{ $skill['name'] }}</span>
                            </div>
                            <span class="text-sm font-mono font-semibold text-fuchsia-600 dark:text-fuchsia-400">{{ $skill['level'] }}%</span>
                        </div>
                        <div class="h-2.5 rounded-full bg-slate-200/80 dark:bg-white/5 overflow-hidden"
                             role="progressbar" aria-valuenow="{{ $skill['level'] }}" aria-valuemin="0" aria-valuemax="100" aria-label="{{ $skill['name'] }}">
                            <div class="skill-progress skill-progress--soft" style="--progress: {{ $skill['level'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
    </section>

    <section id="projects" data-reveal-ignore class="py-16 md:py-20 px-5 md:px-10 max-w-7xl mx-auto">
        <div data-reveal class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12 border-b border-slate-900/10 dark:border-white/10 pb-5">
            <div>
                <span class="inline-block text-[11px] uppercase tracking-widest text-purple-600 dark:text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-full px-3 py-1 mb-4">
                    Portofolio
                </span>
                <h2 class="text-3xl md:text-4xl font-bold tracking-tight">
                    Galeri Proyek <span class="text-slate-400 dark:text-gray-500 font-normal">/ My Projects</span>
                </h2>
            </div>
            <p class="text-slate-500 dark:text-gray-400 text-sm max-w-md md:text-right">
                Kumpulan proyek yang saya kerjakan, lengkap dengan permasalahan, tujuan, teknologi, dan dokumentasi prosesnya.
            </p>
        </div>

        @php
            $activeFilters = collect($filters ?? [])->filter();
        @endphp

        <form method="GET" action="{{ url('/') }}#projects" data-reveal class="mb-10 bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-4 md:p-5">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-5 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-gray-500 pointer-events-none">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari project, teknologi, atau role..." aria-label="Cari project"
                           class="w-full bg-slate-50 dark:bg-[#0b0b0f] border border-slate-900/10 dark:border-white/10 rounded-lg pl-10 pr-3 py-2.5 text-sm text-slate-700 dark:text-gray-200 placeholder:text-slate-400 dark:placeholder:text-gray-500 focus:border-purple-500 outline-none transition">
                </div>

                <div class="md:col-span-3">
                    <select name="technology" onchange="this.form.submit()" aria-label="Filter teknologi"
                            class="w-full bg-slate-50 dark:bg-[#0b0b0f] border border-slate-900/10 dark:border-white/10 rounded-lg px-3 py-2.5 text-sm text-slate-700 dark:text-gray-200 focus:border-purple-500 outline-none transition">
                        <option value="">Semua Teknologi</option>
                        @foreach($technologies ?? [] as $technology)
                            <option value="{{ $technology }}" @selected(($filters['technology'] ?? '') === $technology)>{{ $technology }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <select name="role" onchange="this.form.submit()" aria-label="Filter role"
                            class="w-full bg-slate-50 dark:bg-[#0b0b0f] border border-slate-900/10 dark:border-white/10 rounded-lg px-3 py-2.5 text-sm text-slate-700 dark:text-gray-200 focus:border-purple-500 outline-none transition">
                        <option value="">Semua Role</option>
                        @foreach($roles ?? [] as $role)
                            <option value="{{ $role }}" @selected(($filters['role'] ?? '') === $role)>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <select name="sort" onchange="this.form.submit()" aria-label="Urutkan project"
                            class="w-full bg-slate-50 dark:bg-[#0b0b0f] border border-slate-900/10 dark:border-white/10 rounded-lg px-3 py-2.5 text-sm text-slate-700 dark:text-gray-200 focus:border-purple-500 outline-none transition">
                        <option value="latest" @selected(($filters['sort'] ?? 'latest') === 'latest')>Terbaru</option>
                        <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Terlama</option>
                        <option value="title_asc" @selected(($filters['sort'] ?? '') === 'title_asc')>Judul A-Z</option>
                        <option value="title_desc" @selected(($filters['sort'] ?? '') === 'title_desc')>Judul Z-A</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 mt-4">
                <button type="submit" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition shadow-[0_0_15px_rgba(147,51,234,0.25)]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                    Cari Project
                </button>

                @if($activeFilters->isNotEmpty())
                    <a href="{{ url('/') }}#projects" class="text-slate-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 text-sm font-medium transition">Reset filter</a>
                    <span class="text-xs text-slate-400 dark:text-gray-500">{{ $projects->count() }} project ditemukan</span>
                @endif
            </div>
        </form>

        @if($projects->isEmpty())
            <div class="text-center py-16 border border-dashed border-slate-900/10 dark:border-white/10 rounded-2xl">
                @if($activeFilters->isNotEmpty())
                    <p class="text-slate-500 dark:text-gray-400">Tidak ada project yang cocok dengan pencarian atau filter kamu.</p>
                    <a href="{{ url('/') }}#projects" class="inline-block mt-3 text-sm font-semibold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition">Reset pencarian</a>
                @else
                    <p class="text-slate-500 dark:text-gray-400">Belum ada proyek yang ditambahkan.</p>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($projects as $project)
                    @php
                        $technologies = $project->technologyList();
                    @endphp

                    <article
                        class="project-card group flex flex-col bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl overflow-hidden hover:border-purple-500/50 transition-all duration-500"
                        data-project-data
                        data-title="{{ $project->title }}"
                        data-role="{{ $project->role }}"
                        data-description="{{ Str::limit($project->description, 260) }}"
                        data-image="{{ $project->image ? asset('storage/' . $project->image) : '' }}"
                        data-technologies="{{ implode(', ', $technologies) }}"
                        data-detail="{{ route('projects.show', $project) }}"
                        data-demo="{{ $project->demo_url }}"
                        data-source="{{ $project->source_url }}"
                    >
                        <button type="button" class="project-card__media relative overflow-hidden"
                                data-project-preview aria-label="Preview project {{ $project->title }}">
                            @if($project->image)
                                <img src="{{ asset('storage/' . $project->image) }}" alt="Screenshot {{ $project->title }}"
                                     class="w-full h-64 object-cover opacity-90 group-hover:opacity-100 group-hover:scale-105 transition duration-700">
                            @else
                                <div class="w-full h-64 bg-gradient-to-br from-purple-500/10 to-fuchsia-500/10 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-14 h-14 text-purple-500/40"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 8h6M9 12h6M9 16h4"/></svg>
                                </div>
                            @endif

                            <span class="absolute top-4 left-4 text-[11px] uppercase tracking-widest text-white bg-black/50 backdrop-blur-md border border-white/10 rounded-full px-3 py-1">
                                Project {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>

                            <span class="project-card__preview-hint">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                Preview
                            </span>
                        </button>

                        <div class="flex flex-col flex-1 p-6">
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                @if($project->role)
                                    <span class="text-[11px] uppercase tracking-widest text-purple-600 dark:text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-full px-2.5 py-1">
                                        {{ $project->role }}
                                    </span>
                                @endif
                                @foreach(array_slice($technologies, 0, 3) as $technology)
                                    <span class="text-[11px] text-slate-500 dark:text-gray-400 bg-slate-900/5 dark:bg-white/5 border border-slate-900/10 dark:border-white/10 rounded-full px-2.5 py-1">
                                        {{ $technology }}
                                    </span>
                                @endforeach
                                @if(count($technologies) > 3)
                                    <span class="text-[11px] text-slate-500 dark:text-gray-500">+{{ count($technologies) - 3 }}</span>
                                @endif
                            </div>

                            <h3 class="text-xl font-bold mb-2">
                                <a href="{{ route('projects.show', $project) }}" class="hover:text-purple-600 dark:hover:text-purple-400 transition">{{ $project->title }}</a>
                            </h3>

                            <p class="text-slate-500 dark:text-gray-400 text-sm leading-relaxed mb-6">
                                {{ Str::limit($project->description, 180) }}
                            </p>

                            <div class="mt-auto flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-slate-900/5 dark:border-white/5">
                                <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition">
                                    Lihat Detail
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </a>

                                <div class="flex items-center gap-2">
                                    <button type="button" data-project-preview title="Preview project"
                                            class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 dark:text-gray-300 border border-slate-900/10 dark:border-white/10 hover:border-purple-500/50 hover:text-purple-600 dark:hover:text-purple-400 rounded-lg px-3 py-2 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Preview
                                    </button>

                                    @if($project->demo_url)
                                        <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer" title="Live Demo"
                                           class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 dark:text-gray-300 border border-slate-900/10 dark:border-white/10 hover:border-purple-500/50 hover:text-purple-600 dark:hover:text-purple-400 rounded-lg px-3 py-2 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                            Demo
                                        </a>
                                    @endif

                                    @if($project->source_url)
                                        <a href="{{ $project->source_url }}" target="_blank" rel="noopener noreferrer" title="Source Code"
                                           class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 dark:text-gray-300 border border-slate-900/10 dark:border-white/10 hover:border-purple-500/50 hover:text-purple-600 dark:hover:text-purple-400 rounded-lg px-3 py-2 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/></svg>
                                            Source
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    @if($certificates->isNotEmpty())
    <section id="certificates" class="py-16 md:py-20 px-5 md:px-10 max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold mb-12 border-b border-slate-900/10 dark:border-white/10 pb-4">Sertifikasi</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($certificates as $certificate)
                <div class="group bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl overflow-hidden hover:border-purple-500/50 transition-all duration-500">
                    @if($certificate->image)
                        <img src="{{ asset('storage/' . $certificate->image) }}" class="w-full h-48 object-cover opacity-80 group-hover:opacity-100 transition duration-500">
                    @else
                        <div class="w-full h-48 bg-slate-100 dark:bg-[#1e1e24] flex items-center justify-center text-4xl opacity-60">🏆</div>
                    @endif
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-1">{{ $certificate->title }}</h3>
                        <div class="flex items-center gap-3 mb-2 flex-wrap">
                            @if($certificate->issuer)
                                <span class="text-purple-600 dark:text-purple-400 text-xs uppercase tracking-widest">{{ $certificate->issuer }}</span>
                            @endif
                            @if($certificate->issue_date)
                                <span class="text-slate-500 dark:text-gray-500 text-xs">{{ $certificate->issue_date->format('d M Y') }}</span>
                            @endif
                        </div>
                        @if($certificate->description)
                            <p class="text-slate-500 dark:text-gray-400 text-sm">{{ $certificate->description }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    <section id="blog" class="py-16 md:py-20 px-5 md:px-10 max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10 border-b border-slate-900/10 dark:border-white/10 pb-5">
            <div>
                <span class="inline-block text-[11px] uppercase tracking-widest text-purple-600 dark:text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-full px-3 py-1 mb-4">
                    Blog
                </span>
                <h2 class="text-3xl md:text-4xl font-bold tracking-tight">
                    Artikel Blog <span class="text-slate-400 dark:text-gray-500 font-normal">/ Blog</span>
                </h2>
            </div>
            <p class="text-slate-500 dark:text-gray-400 text-sm max-w-md md:text-right">
                Tulisan seputar pengembangan web, teknologi, dan hal-hal lain yang saya pelajari.
            </p>
        </div>

        @php
            $activeBlogFilters = collect([
                'q' => $blogFilters['q'],
                'tag' => $blogFilters['tag'],
                'sort' => $blogFilters['sort'] !== 'latest' ? $blogFilters['sort'] : null,
            ])->filter();
        @endphp

        <form method="GET" action="{{ url('/') }}#blog" class="mb-8 bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-4 md:p-5">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-6 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-gray-500 pointer-events-none">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                    <input type="search" name="blog_q" value="{{ $blogFilters['q'] }}" placeholder="Cari judul atau isi artikel..." aria-label="Cari artikel blog"
                           class="w-full bg-slate-50 dark:bg-[#0b0b0f] border border-slate-900/10 dark:border-white/10 rounded-lg pl-10 pr-3 py-2.5 text-sm text-slate-700 dark:text-gray-200 placeholder:text-slate-400 dark:placeholder:text-gray-500 focus:border-purple-500 outline-none transition">
                </div>

                <div class="md:col-span-3">
                    <select name="blog_tag" onchange="this.form.submit()" aria-label="Filter tag artikel"
                            class="w-full bg-slate-50 dark:bg-[#0b0b0f] border border-slate-900/10 dark:border-white/10 rounded-lg px-3 py-2.5 text-sm text-slate-700 dark:text-gray-200 focus:border-purple-500 outline-none transition">
                        <option value="">Semua Tag</option>
                        @foreach($blogTags as $tag)
                            <option value="{{ $tag }}" @selected($blogFilters['tag'] === $tag)>{{ $tag }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-3">
                    <select name="blog_sort" onchange="this.form.submit()" aria-label="Urutkan artikel blog"
                            class="w-full bg-slate-50 dark:bg-[#0b0b0f] border border-slate-900/10 dark:border-white/10 rounded-lg px-3 py-2.5 text-sm text-slate-700 dark:text-gray-200 focus:border-purple-500 outline-none transition">
                        <option value="latest" @selected($blogFilters['sort'] === 'latest')>Terbaru</option>
                        <option value="oldest" @selected($blogFilters['sort'] === 'oldest')>Terlama</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 mt-4">
                <button type="submit" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition shadow-[0_0_15px_rgba(147,51,234,0.25)]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                    Cari
                </button>

                @if($activeBlogFilters->isNotEmpty())
                    <a href="{{ url('/') }}#blog" class="text-slate-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 text-sm font-medium transition">Reset filter</a>
                    <span class="text-xs text-slate-400 dark:text-gray-500">{{ $latestBlogs->count() }} artikel ditampilkan</span>
                @endif
            </div>
        </form>

        @if($latestBlogs->isEmpty())
            <div class="text-center py-16 border border-dashed border-slate-900/10 dark:border-white/10 rounded-2xl">
                @if($activeBlogFilters->isNotEmpty())
                    <p class="text-slate-500 dark:text-gray-400">Tidak ada artikel yang cocok dengan pencarian atau filter kamu.</p>
                    <a href="{{ url('/') }}#blog" class="inline-block mt-3 text-sm font-semibold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition">Reset pencarian</a>
                @else
                    <p class="text-slate-500 dark:text-gray-400">Belum ada artikel blog yang dipublikasikan.</p>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($latestBlogs as $blog)
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

                            <h3 class="text-xl font-bold mb-3 leading-snug">
                                <a href="{{ route('blog.show', $blog->slug) }}" class="hover:text-purple-600 dark:hover:text-purple-400 transition">{{ $blog->title }}</a>
                            </h3>

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

            <div class="text-center mt-10">
                <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition">
                    Lihat Semua Artikel
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>
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
