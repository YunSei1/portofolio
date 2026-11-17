<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Saya — Portofolio</title>
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
            <a href="{{ route('about') }}" class="text-slate-900 dark:text-white transition">About</a>
            <a href="/#projects" class="hover:text-slate-900 dark:hover:text-white transition" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Projects</a>
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

    {{-- Hero --}}
    <header class="px-5 md:px-10 lg:px-20 pt-16 pb-10 md:pt-24 md:pb-16">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 items-center max-w-7xl mx-auto">

            <div class="md:col-span-8 flex flex-col items-center md:items-start text-center md:text-left">
                <span class="px-3 py-1 rounded-full border border-slate-900/10 dark:border-white/10 text-xs text-purple-600 dark:text-purple-400 mb-6 bg-purple-500/10 uppercase tracking-widest">
                    Tentang Saya / About Me
                </span>

                <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tighter mb-6 bg-gradient-to-b from-slate-900 dark:from-white to-slate-500 dark:to-gray-500 bg-clip-text text-transparent leading-tight">
                    Tentang Saya
                </h1>

                <p class="text-slate-500 dark:text-gray-400 text-lg max-w-xl mb-8">
                    Menjembatani dunia desain dan kode. Sebagai
                    <span class="text-purple-600 dark:text-purple-400">UI/UX Designer</span> sekaligus
                    <span class="text-purple-600 dark:text-purple-400">Front-End Developer</span>, saya tidak hanya merancang
                    tampilan produk digital yang estetis dan mudah digunakan, tetapi juga membangunnya langsung menjadi
                    aplikasi web yang interaktif dan berkinerja tinggi.
                </p>

                <div class="flex flex-wrap justify-center md:justify-start gap-3">
                    <span class="px-3 py-1.5 rounded-lg bg-purple-500/10 border border-purple-500/30 text-xs text-purple-600 dark:text-purple-400 font-medium">UI/UX Designer</span>
                    <span class="px-3 py-1.5 rounded-lg bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 text-xs text-slate-700 dark:text-gray-300">Siswa PPLG</span>
                    <span class="px-3 py-1.5 rounded-lg bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 text-xs text-slate-700 dark:text-gray-300">Front-End Developer</span>
                    <span class="px-3 py-1.5 rounded-lg bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 text-xs text-slate-700 dark:text-gray-300">IoT Learner</span>
                </div>

                @if(\App\Models\Profile::hasCv())
                    <div class="mt-8 flex flex-wrap justify-center md:justify-start gap-3">
                        @if(\App\Models\Profile::hasPdfCv())
                            <a href="{{ route('cv.preview') }}" target="_blank" rel="noopener" data-no-transition class="inline-flex items-center gap-2 border border-purple-500/40 text-purple-600 dark:text-purple-400 hover:bg-purple-500/10 px-6 py-3 rounded-lg font-medium transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                                Preview CV
                            </a>
                        @endif
                        <a href="{{ route('cv.download') }}" data-no-transition class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition shadow-[0_0_20px_rgba(147,51,234,0.25)]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Download CV
                        </a>
                    </div>
                @endif
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

    {{-- 01. Biodata Singkat --}}
    <section class="py-12 md:py-16 px-5 md:px-10 max-w-7xl mx-auto">
        <div class="flex items-baseline gap-5 mb-8 border-b border-slate-900/10 dark:border-white/10 pb-4">
            <span class="section-number">01</span>
            <div>
                <h2 class="text-2xl md:text-3xl font-bold tracking-tight">Biodata Singkat</h2>
                <p class="text-slate-500 dark:text-gray-500 text-sm mt-1">Siapa saya dan apa yang saya tekuni</p>
            </div>
        </div>

        <div class="bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 md:p-8">
            <p class="text-slate-700 dark:text-gray-300 leading-relaxed mb-8">
                Saya merupakan siswa kelas XII SMK Negeri 2 Banjarmasin jurusan Pengembangan Perangkat Lunak dan Gim (PPLG).
                Saya memiliki ketertarikan besar terhadap teknologi, khususnya pengembangan website dan Front-End Development.
                Saya senang mempelajari hal baru, mengembangkan ide, serta menciptakan solusi digital yang bermanfaat.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/5 dark:border-white/5 rounded-xl p-4">
                    <p class="text-[11px] uppercase tracking-widest text-slate-500 dark:text-gray-500 mb-1">Nama</p>
                    <p class="font-semibold text-sm">{{ $profile?->name ?? 'Muhammad Rizky' }}</p>
                </div>
                <div class="bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/5 dark:border-white/5 rounded-xl p-4">
                    <p class="text-[11px] uppercase tracking-widest text-slate-500 dark:text-gray-500 mb-1">Sekolah</p>
                    <p class="font-semibold text-sm">SMK Negeri 2 Banjarmasin</p>
                </div>
                <div class="bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/5 dark:border-white/5 rounded-xl p-4">
                    <p class="text-[11px] uppercase tracking-widest text-slate-500 dark:text-gray-500 mb-1">Jurusan</p>
                    <p class="font-semibold text-sm">PPLG</p>
                </div>
                <div class="bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/5 dark:border-white/5 rounded-xl p-4">
                    <p class="text-[11px] uppercase tracking-widest text-slate-500 dark:text-gray-500 mb-1">Fokus</p>
                    <p class="font-semibold text-sm text-purple-600 dark:text-purple-400">Front-End Development</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 02. Riwayat Pendidikan --}}
    <section class="py-12 md:py-16 px-5 md:px-10 max-w-7xl mx-auto">
        <div class="flex items-baseline gap-5 mb-8 border-b border-slate-900/10 dark:border-white/10 pb-4">
            <span class="section-number">02</span>
            <div>
                <h2 class="text-2xl md:text-3xl font-bold tracking-tight">Riwayat Pendidikan</h2>
                <p class="text-slate-500 dark:text-gray-500 text-sm mt-1">Perjalanan pendidikan formal saya</p>
            </div>
        </div>

        <div class="relative pl-8 sm:pl-10 flex flex-col gap-5">
            <div class="absolute left-[7px] sm:left-[9px] top-2 bottom-2 w-px bg-gradient-to-b from-purple-500/60 via-slate-900/10 dark:via-white/10 to-transparent"></div>

            {{-- 1. SD --}}
            <div class="relative bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 md:p-8 hover:border-purple-500/40 transition">
                <span class="absolute -left-8 sm:-left-10 top-8 w-4 h-4 rounded-full bg-purple-500 ring-4 ring-purple-500/20"></span>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-mono font-bold text-purple-600 dark:text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-lg px-2 py-1">01</span>
                        <h3 class="text-xl font-bold">SD Sabilal Muhtadin</h3>
                    </div>
                    <span class="text-xs font-mono uppercase tracking-widest text-purple-600 dark:text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-full px-3 py-1 w-fit">
                        2015 — 2021
                    </span>
                </div>

                <div class="flex flex-wrap gap-2">
                    <span class="text-xs text-slate-700 dark:text-gray-300 bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/10 dark:border-white/10 rounded-lg px-3 py-1.5">
                        Sekolah Dasar
                    </span>
                </div>
            </div>

            {{-- 2. SMP --}}
            <div class="relative bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 md:p-8 hover:border-purple-500/40 transition">
                <span class="absolute -left-8 sm:-left-10 top-8 w-4 h-4 rounded-full bg-purple-500 ring-4 ring-purple-500/20"></span>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-mono font-bold text-purple-600 dark:text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-lg px-2 py-1">02</span>
                        <h3 class="text-xl font-bold">SMP Negeri 2 Banjarmasin</h3>
                    </div>
                    <span class="text-xs font-mono uppercase tracking-widest text-purple-600 dark:text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-full px-3 py-1 w-fit">
                        2021 — 2024
                    </span>
                </div>

                <div class="flex flex-wrap gap-2">
                    <span class="text-xs text-slate-700 dark:text-gray-300 bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/10 dark:border-white/10 rounded-lg px-3 py-1.5">
                        Sekolah Menengah Pertama
                    </span>
                </div>
            </div>

            {{-- 3. SMK --}}
            <div class="relative bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 md:p-8 hover:border-purple-500/40 transition">
                <span class="absolute -left-8 sm:-left-10 top-8 w-4 h-4 rounded-full bg-purple-500 ring-4 ring-purple-500/20"></span>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-mono font-bold text-purple-600 dark:text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-lg px-2 py-1">03</span>
                        <h3 class="text-xl font-bold">SMK Negeri 2 Banjarmasin</h3>
                    </div>
                    <span class="text-xs font-mono uppercase tracking-widest text-purple-600 dark:text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-full px-3 py-1 w-fit">
                        2024 — 2027
                    </span>
                </div>

                <div class="flex flex-wrap gap-2">
                    <span class="text-xs text-slate-700 dark:text-gray-300 bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/10 dark:border-white/10 rounded-lg px-3 py-1.5">
                        Jurusan: Pengembangan Perangkat Lunak dan Gim (PPLG)
                    </span>
                    <span class="text-xs text-slate-700 dark:text-gray-300 bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/10 dark:border-white/10 rounded-lg px-3 py-1.5">
                        Kelas XII
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- 03. Minat dan Bidang Teknologi --}}
    <section class="py-12 md:py-16 px-5 md:px-10 max-w-7xl mx-auto">
        <div class="flex items-baseline gap-5 mb-8 border-b border-slate-900/10 dark:border-white/10 pb-4">
            <span class="section-number">03</span>
            <div>
                <h2 class="text-2xl md:text-3xl font-bold tracking-tight">Minat &amp; Teknologi</h2>
                <p class="text-slate-500 dark:text-gray-500 text-sm mt-1">Bidang yang saya minati dan teknologi yang saya pelajari</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-5 bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 md:p-8">
                <h3 class="text-lg font-bold mb-3">Web Development</h3>
                <p class="text-slate-500 dark:text-gray-400 text-sm leading-relaxed mb-6">
                    Saya memiliki minat dalam bidang Web Development, khususnya Front-End Development.
                    Saya tertarik merancang tampilan website yang menarik, responsif, dan mudah digunakan.
                </p>

                <p class="text-[11px] uppercase tracking-widest text-slate-500 dark:text-gray-500 mb-3">Teknologi yang saya gunakan</p>
                <div class="flex flex-wrap gap-2">
                    <span class="text-xs text-slate-700 dark:text-gray-300 bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/10 dark:border-white/10 rounded-lg px-3 py-1.5">HTML &amp; CSS</span>
                    <span class="text-xs text-slate-700 dark:text-gray-300 bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/10 dark:border-white/10 rounded-lg px-3 py-1.5">JavaScript</span>
                    <span class="text-xs text-slate-700 dark:text-gray-300 bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/10 dark:border-white/10 rounded-lg px-3 py-1.5">Bootstrap</span>
                    <span class="text-xs text-slate-700 dark:text-gray-300 bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/10 dark:border-white/10 rounded-lg px-3 py-1.5">Tailwind CSS</span>
                    <span class="text-xs text-slate-700 dark:text-gray-300 bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/10 dark:border-white/10 rounded-lg px-3 py-1.5">PHP &amp; MySQL</span>
                    <span class="text-xs text-slate-700 dark:text-gray-300 bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/10 dark:border-white/10 rounded-lg px-3 py-1.5">IoT (ESP32)</span>
                </div>
            </div>

            <div class="lg:col-span-7 grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div class="group p-5 bg-white dark:bg-[#16161a] border border-slate-900/5 dark:border-white/5 rounded-2xl flex flex-col items-center hover:border-orange-500/50 hover:bg-orange-500/5 transition-all duration-300">
                    <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/html5/html5-original.svg" alt="HTML" class="w-11 h-11 mb-3 group-hover:scale-110 transition-transform">
                    <span class="text-xs font-medium text-slate-700 dark:text-gray-300 group-hover:text-slate-900 dark:group-hover:text-white uppercase tracking-widest">HTML</span>
                </div>
                <div class="group p-5 bg-white dark:bg-[#16161a] border border-slate-900/5 dark:border-white/5 rounded-2xl flex flex-col items-center hover:border-blue-600/50 hover:bg-blue-600/5 transition-all duration-300">
                    <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/css3/css3-original.svg" alt="CSS" class="w-11 h-11 mb-3 group-hover:scale-110 transition-transform">
                    <span class="text-xs font-medium text-slate-700 dark:text-gray-300 group-hover:text-slate-900 dark:group-hover:text-white uppercase tracking-widest">CSS</span>
                </div>
                <div class="group p-5 bg-white dark:bg-[#16161a] border border-slate-900/5 dark:border-white/5 rounded-2xl flex flex-col items-center hover:border-yellow-400/50 hover:bg-yellow-400/5 transition-all duration-300">
                    <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/javascript/javascript-original.svg" alt="JavaScript" class="w-11 h-11 mb-3 group-hover:scale-110 transition-transform">
                    <span class="text-xs font-medium text-slate-700 dark:text-gray-300 group-hover:text-slate-900 dark:group-hover:text-white uppercase tracking-widest">JavaScript</span>
                </div>
                <div class="group p-5 bg-white dark:bg-[#16161a] border border-slate-900/5 dark:border-white/5 rounded-2xl flex flex-col items-center hover:border-purple-600/50 hover:bg-purple-600/5 transition-all duration-300">
                    <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/bootstrap/bootstrap-original.svg" alt="Bootstrap" class="w-11 h-11 mb-3 group-hover:scale-110 transition-transform">
                    <span class="text-xs font-medium text-slate-700 dark:text-gray-300 group-hover:text-slate-900 dark:group-hover:text-white uppercase tracking-widest">Bootstrap</span>
                </div>
                <div class="group p-5 bg-white dark:bg-[#16161a] border border-slate-900/5 dark:border-white/5 rounded-2xl flex flex-col items-center hover:border-cyan-400/50 hover:bg-cyan-400/5 transition-all duration-300">
                    <img src="https://www.vectorlogo.zone/logos/tailwindcss/tailwindcss-icon.svg" alt="Tailwind CSS" class="w-11 h-11 mb-3 group-hover:scale-110 transition-transform">
                    <span class="text-xs font-medium text-slate-700 dark:text-gray-300 group-hover:text-slate-900 dark:group-hover:text-white uppercase tracking-widest">Tailwind</span>
                </div>
                <div class="group p-5 bg-white dark:bg-[#16161a] border border-slate-900/5 dark:border-white/5 rounded-2xl flex flex-col items-center hover:border-indigo-400/50 hover:bg-indigo-400/5 transition-all duration-300">
                    <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/php/php-original.svg" alt="PHP" class="w-11 h-11 mb-3 group-hover:scale-110 transition-transform">
                    <span class="text-xs font-medium text-slate-700 dark:text-gray-300 group-hover:text-slate-900 dark:group-hover:text-white uppercase tracking-widest">PHP</span>
                </div>
                <div class="group p-5 bg-white dark:bg-[#16161a] border border-slate-900/5 dark:border-white/5 rounded-2xl flex flex-col items-center hover:border-sky-500/50 hover:bg-sky-500/5 transition-all duration-300">
                    <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/mysql/mysql-original.svg" alt="MySQL" class="w-11 h-11 mb-3 group-hover:scale-110 transition-transform">
                    <span class="text-xs font-medium text-slate-700 dark:text-gray-300 group-hover:text-slate-900 dark:group-hover:text-white uppercase tracking-widest">MySQL</span>
                </div>
                <div class="group p-5 bg-white dark:bg-[#16161a] border border-slate-900/5 dark:border-white/5 rounded-2xl flex flex-col items-center hover:border-emerald-400/50 hover:bg-emerald-400/5 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-11 h-11 mb-3 text-emerald-400 group-hover:scale-110 transition-transform"><rect x="7" y="7" width="10" height="10" rx="1"/><path d="M9 3v4M15 3v4M9 17v4M15 17v4M3 9h4M3 15h4M17 9h4M17 15h4"/></svg>
                    <span class="text-xs font-medium text-slate-700 dark:text-gray-300 group-hover:text-slate-900 dark:group-hover:text-white uppercase tracking-widest">ESP32</span>
                </div>
                <div class="group p-5 bg-white dark:bg-[#16161a] border border-slate-900/5 dark:border-white/5 rounded-2xl flex flex-col items-center hover:border-slate-900/30 dark:hover:border-white/30 hover:bg-slate-900/5 dark:hover:bg-white/5 transition-all duration-300">
                    <img src="https://www.vectorlogo.zone/logos/figma/figma-icon.svg" alt="Figma" class="w-11 h-11 mb-3 group-hover:scale-110 transition-transform">
                    <span class="text-xs font-medium text-slate-700 dark:text-gray-300 group-hover:text-slate-900 dark:group-hover:text-white uppercase tracking-widest">Figma</span>
                </div>
            </div>
        </div>
    </section>

    {{-- 04. Pengalaman Organisasi --}}
    <section class="py-12 md:py-16 px-5 md:px-10 max-w-7xl mx-auto">
        <div class="flex items-baseline gap-5 mb-8 border-b border-slate-900/10 dark:border-white/10 pb-4">
            <span class="section-number">04</span>
            <div>
                <h2 class="text-2xl md:text-3xl font-bold tracking-tight">Pengalaman Organisasi / Kepanitiaan</h2>
                <p class="text-slate-500 dark:text-gray-500 text-sm mt-1">Belajar bekerja sama dan berkoordinasi dalam tim</p>
            </div>
        </div>

        <div class="bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 md:p-8">
            <div class="flex flex-col md:flex-row gap-6 md:gap-8">
                <div class="shrink-0 w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-600 dark:text-purple-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>

                <div>
                    <p class="text-slate-700 dark:text-gray-300 leading-relaxed mb-6">
                        Saya memiliki pengalaman bekerja dalam tim dan berkoordinasi dengan anggota untuk menyelesaikan
                        tugas serta mencapai tujuan bersama. Saya juga berusaha mengembangkan kemampuan komunikasi,
                        tanggung jawab, kepemimpinan, dan manajemen waktu.
                    </p>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/5 dark:border-white/5 rounded-xl px-4 py-3">
                            <p class="text-xs text-slate-500 dark:text-gray-400">Komunikasi</p>
                        </div>
                        <div class="bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/5 dark:border-white/5 rounded-xl px-4 py-3">
                            <p class="text-xs text-slate-500 dark:text-gray-400">Tanggung Jawab</p>
                        </div>
                        <div class="bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/5 dark:border-white/5 rounded-xl px-4 py-3">
                            <p class="text-xs text-slate-500 dark:text-gray-400">Kepemimpinan</p>
                        </div>
                        <div class="bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/5 dark:border-white/5 rounded-xl px-4 py-3">
                            <p class="text-xs text-slate-500 dark:text-gray-400">Manajemen Waktu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 05. Pengalaman PLK --}}
    <section class="py-12 md:py-16 px-5 md:px-10 max-w-7xl mx-auto">
        <div class="flex items-baseline gap-5 mb-8 border-b border-slate-900/10 dark:border-white/10 pb-4">
            <span class="section-number">05</span>
            <div>
                <h2 class="text-2xl md:text-3xl font-bold tracking-tight">Pengalaman PLK</h2>
                <p class="text-slate-500 dark:text-gray-500 text-sm mt-1">Praktik di LeenGroup Company</p>
            </div>
        </div>

        <p class="text-slate-700 dark:text-gray-300 leading-relaxed mb-8 max-w-3xl">
            Selama mengikuti kegiatan PLK di <span class="text-purple-600 dark:text-purple-400 font-semibold">LeenGroup Company</span>,
            saya terlibat dalam beberapa proyek pengembangan teknologi, antara lain:
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="group bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 hover:border-purple-500/50 transition-all duration-300">
                <div class="w-11 h-11 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-600 dark:text-purple-400 mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-[22px] h-[22px]"><rect width="18" height="14" x="3" y="4" rx="2"/><path d="M8 21h8M12 18v3"/><path d="m7 9 2 2-2 2M13 13h4"/></svg>
                </div>
                <h3 class="text-lg font-bold mb-2">Teacher Portfolio Website</h3>
                <p class="text-slate-500 dark:text-gray-400 text-sm leading-relaxed">
                    Mengembangkan website portofolio untuk menampilkan profil dan informasi profesional guru.
                </p>
            </div>

            <div class="group bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 hover:border-purple-500/50 transition-all duration-300">
                <div class="w-11 h-11 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-[22px] h-[22px]"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="m7 14 4-4 3 3 5-5"/><path d="M19 8v4h-4"/></svg>
                </div>
                <h3 class="text-lg font-bold mb-2">Flood Infographic Website</h3>
                <p class="text-slate-500 dark:text-gray-400 text-sm leading-relaxed">
                    Membuat website infografis yang menyajikan informasi mengenai banjir.
                </p>
            </div>

            <div class="group bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 hover:border-purple-500/50 transition-all duration-300">
                <div class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-[22px] h-[22px]"><path d="M7 19H4.8a2.8 2.8 0 0 1-2.8-2.8v-1.4A2.8 2.8 0 0 1 4.8 12H7"/><path d="M17 19h2.2a2.8 2.8 0 0 0 2.8-2.8v-1.4A2.8 2.8 0 0 0 19.2 12H17"/><rect x="7" y="5" width="10" height="14" rx="2"/><path d="M10 9h4M10 12h4M10 15h2"/></svg>
                </div>
                <h3 class="text-lg font-bold mb-2">Smart Waste ATM Machine</h3>
                <p class="text-slate-500 dark:text-gray-400 text-sm leading-relaxed">
                    Berpartisipasi dalam pengembangan mesin ATM sampah berbasis IoT yang mengintegrasikan perangkat keras dan perangkat lunak.
                </p>
            </div>
        </div>

        <div class="mt-6 bg-purple-500/5 border border-purple-500/20 rounded-2xl p-6 md:p-8">
            <p class="text-slate-700 dark:text-gray-300 leading-relaxed">
                Melalui pengalaman tersebut, saya memperoleh pengetahuan praktis tentang pengembangan website,
                teknologi IoT, pemecahan masalah, dan kerja sama tim.
            </p>
        </div>
    </section>

    {{-- 06. Cita-cita / Tujuan Karir --}}
    <section class="py-12 md:py-16 px-5 md:px-10 max-w-7xl mx-auto">
        <div class="flex items-baseline gap-5 mb-8 border-b border-slate-900/10 dark:border-white/10 pb-4">
            <span class="section-number">06</span>
            <div>
                <h2 class="text-2xl md:text-3xl font-bold tracking-tight">Cita-Cita / Tujuan Karir</h2>
                <p class="text-slate-500 dark:text-gray-500 text-sm mt-1">Arah dan tujuan yang ingin saya capai</p>
            </div>
        </div>

        <div class="relative bg-gradient-to-br from-purple-50 dark:from-[#1a1428] to-white dark:to-[#16161a] border border-purple-500/25 rounded-3xl p-6 md:p-10 overflow-hidden">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-purple-600/20 blur-[80px] rounded-full"></div>

            <div class="relative z-10">
                <span class="inline-block text-[11px] uppercase tracking-widest text-purple-600 dark:text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-full px-3 py-1 mb-5">
                    Front-End Developer
                </span>

                <p class="text-slate-700 dark:text-gray-200 text-lg md:text-xl leading-relaxed mb-6 max-w-3xl">
                    Saya bercita-cita menjadi seorang <span class="text-slate-900 dark:text-white font-semibold">Professional Front-End Developer</span>
                    yang mampu menciptakan website dan aplikasi dengan desain modern, responsif, serta memberikan
                    pengalaman pengguna yang baik.
                </p>

                <p class="text-slate-500 dark:text-gray-400 leading-relaxed max-w-3xl">
                    Ke depannya, saya ingin terus mengembangkan kemampuan di bidang teknologi, mempelajari framework
                    dan teknologi baru, serta berkontribusi dalam menciptakan inovasi digital yang bermanfaat bagi masyarakat.
                </p>
            </div>
        </div>
    </section>
{{-- 07. Hobbies --}}
<section id="hobbies" class="py-12 md:py-16 px-5 md:px-10 max-w-7xl mx-auto">
   <div class="flex items-baseline gap-5 mb-8 border-b border-slate-900/10 dark:border-white/10 pb-4">
       <span class="section-number">07</span>
       <div>
           <h2 class="text-2xl md:text-3xl font-bold tracking-tight">Hobbies</h2>
           <p class="text-slate-500 dark:text-gray-500 text-sm mt-1">Kegiatan favorit saya</p>
       </div>
   </div>
   <div class="bg-white dark:bg-[#16161a] border border-slate-900/10 dark:border-white/10 rounded-2xl p-6 md:p-8">
       <h3 class="text-lg font-bold mb-6">Musik</h3>
       <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
           <!-- Track 1: Timeless - The Weeknd -->
           <div class="bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/5 dark:border-white/5 rounded-xl p-5">
               <div class="flex items-center gap-3 mb-3">
                   <iframe style="border-radius:12px" src="https://open.spotify.com/embed/track/0FIDCNYYjNvPVimz5icugS?utm_source=generator" width="100%" height="352" frameborder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen" title="Timeless"></iframe>
               </div>
               <h4 class="font-medium text-slate-700 dark:text-gray-300">Timeless - The Weeknd</h4>
               <p class="text-xs text-slate-500 dark:text-gray-400">K lagu kesukaan</p>
           </div>
<!-- Track 2: Mia - Bad Bunny, Drake -->
<div class="bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/5 dark:border-white/5 rounded-xl p-5">
    <iframe style="border-radius:12px; width:100%;"
            src="https://open.spotify.com/embed/track/03HVXaakidVAtO5qY2ALoJ?utm_source=generator"
            width="100%" height="352" frameborder="0"
            allowfullscreen=""
            allow="autoplay; clipboard-write; encrypted-media; fullscreen"
            title="Mia"></iframe>
    <h4 class="font-medium text-slate-700 dark:text-gray-300 mt-3">Mia - Bad Bunny, Drake</h4>
    <p class="text-xs text-slate-500 dark:text-gray-400">K lagu kesukaan</p>
</div>
<!-- Track 3: Into You - alt J -->
<div class="bg-[#f5f6fa] dark:bg-[#0b0b0f] border border-slate-900/5 dark:border-white/5 rounded-xl p-5">
    <iframe style="border-radius:12px; width:100%;" src="https://open.spotify.com/embed/track/6EXKngefS01i0Wv7mM6GHM?utm_source=generator&si=eb25839971df4ddd" width="100%" height="352" frameborder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
    <h4 class="font-medium text-slate-700 dark:text-gray-300 mt-3">Into You - alt J</h4>
    <p class="text-xs text-slate-500 dark:text-gray-400">K lagu kesukaan</p>
</div>
   </div>
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
