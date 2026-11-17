<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Admin</title>
    @vite('resources/css/app.css')
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
<body class="bg-[#0b0b0f] text-white min-h-screen">

    <div class="glow"></div>

    <nav class="flex justify-between items-center gap-3 px-4 sm:px-6 md:px-10 py-4 sm:py-6 bg-[#0b0b0f] border-b border-white/10">
        <a href="/" class="text-xl font-bold tracking-tighter hover:text-purple-400 transition">PORTOFOLIO.</a>
        <div class="flex items-center gap-3 sm:gap-6 min-w-0">
            <a href="{{ route('admin.blogs.index') }}" class="text-gray-400 hover:text-purple-400 text-xs uppercase font-bold transition shrink-0">Blog</a>
            <span class="text-purple-400 text-xs font-mono uppercase tracking-widest truncate max-w-[45vw] sm:max-w-none">{{ auth()->user()->username }}</span>
            <form action="{{ route('logout') }}" method="POST" class="shrink-0">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-red-400 text-xs transition uppercase font-bold">Logout</button>
            </form>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 md:px-10 py-10 sm:py-12 relative z-10">
        <div class="mb-10">
            <h1 class="text-3xl font-extrabold tracking-tighter bg-gradient-to-b from-white to-gray-500 bg-clip-text text-transparent">
                Dashboard Admin
            </h1>
            <p class="text-gray-400 text-sm mt-1">Selamat datang, <span class="text-purple-400 font-semibold">{{ auth()->user()->name }}</span></p>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-3 text-sm text-green-400 mb-6">{{ session('success') }}</div>
        @endif

        {{-- Statistik Ringkas --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4 mb-10">
            <div class="bg-[#16161a] border border-white/10 rounded-2xl p-5">
                <div class="flex items-start justify-between gap-2 mb-3">
                    <p class="text-gray-400 text-xs uppercase tracking-widest leading-tight">Proyek</p>
                    <div class="w-9 h-9 rounded-lg bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 shrink-0">
                        <x-admin-icon name="folder" class="w-4 h-4" />
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-purple-400">{{ $projectCount }}</p>
            </div>

            <div class="bg-[#16161a] border border-white/10 rounded-2xl p-5">
                <div class="flex items-start justify-between gap-2 mb-3">
                    <p class="text-gray-400 text-xs uppercase tracking-widest leading-tight">Sertifikat</p>
                    <div class="w-9 h-9 rounded-lg bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 shrink-0">
                        <x-admin-icon name="award" class="w-4 h-4" />
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-purple-400">{{ $certificateCount }}</p>
            </div>

            <div class="bg-[#16161a] border border-white/10 rounded-2xl p-5">
                <div class="flex items-start justify-between gap-2 mb-3">
                    <p class="text-gray-400 text-xs uppercase tracking-widest leading-tight">Blog</p>
                    <div class="w-9 h-9 rounded-lg bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 shrink-0">
                        <x-admin-icon name="pen" class="w-4 h-4" />
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-purple-400">{{ $blogCount }}</p>
            </div>

            <div class="bg-[#16161a] border border-white/10 rounded-2xl p-5">
                <div class="flex items-start justify-between gap-2 mb-3">
                    <p class="text-gray-400 text-xs uppercase tracking-widest leading-tight">Total Pesan</p>
                    <div class="w-9 h-9 rounded-lg bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 shrink-0">
                        <x-admin-icon name="mail" class="w-4 h-4" />
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-purple-400">{{ $messageCount }}</p>
            </div>

            <div class="bg-[#16161a] border border-white/10 rounded-2xl p-5">
                <div class="flex items-start justify-between gap-2 mb-3">
                    <p class="text-gray-400 text-xs uppercase tracking-widest leading-tight">Belum Dibaca</p>
                    <div class="w-9 h-9 rounded-lg border flex items-center justify-center shrink-0 {{ $unreadCount > 0 ? 'bg-red-500/10 border-red-500/20 text-red-400' : 'bg-purple-500/10 border-purple-500/20 text-purple-400' }}">
                        <x-admin-icon name="inbox" class="w-4 h-4" />
                    </div>
                </div>
                <p class="text-3xl font-extrabold {{ $unreadCount > 0 ? 'text-red-400' : 'text-purple-400' }}">{{ $unreadCount }}</p>
            </div>

            <div class="bg-[#16161a] border border-white/10 rounded-2xl p-5">
                <div class="flex items-start justify-between gap-2 mb-3">
                    <p class="text-gray-400 text-xs uppercase tracking-widest leading-tight">Kesan &amp; Pesan</p>
                    <div class="w-9 h-9 rounded-lg bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 shrink-0">
                        <x-admin-icon name="message" class="w-4 h-4" />
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-purple-400">{{ $guestMessageCount }}</p>
            </div>
        </div>

        {{-- Blog Terbaru --}}
        <div id="blog" class="mb-6 bg-[#16161a] border border-white/10 rounded-2xl p-6 md:p-8 scroll-mt-24">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold">Blog Terbaru</h2>
                    <p class="text-gray-400 text-sm mt-1">3 artikel terbaru yang kamu tulis</p>
                </div>
                <a href="{{ route('admin.blogs.index') }}" class="inline-flex items-center gap-2 self-start text-sm font-semibold text-purple-400 hover:text-purple-300 transition">
                    Lihat Semua Blog
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>

            @if($latestBlogs->isEmpty())
                <div class="border border-dashed border-white/10 rounded-xl p-8 text-center">
                    <p class="text-sm text-gray-400 mb-4">Belum ada artikel blog yang ditulis.</p>
                    <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
                        + Tulis Artikel
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($latestBlogs as $blog)
                        <article class="flex flex-col bg-[#0b0b0f] border border-white/10 rounded-xl p-5 hover:border-purple-500/40 transition-colors duration-300">
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                @if(! $blog->is_published)
                                    <span class="text-[11px] uppercase tracking-widest text-yellow-400 bg-yellow-500/10 border border-yellow-500/20 rounded-full px-2.5 py-1">Private</span>
                                @elseif($blog->published_at && $blog->published_at->isFuture())
                                    <span class="text-[11px] uppercase tracking-widest text-yellow-400 bg-yellow-500/10 border border-yellow-500/20 rounded-full px-2.5 py-1">Terjadwal</span>
                                @else
                                    <span class="text-[11px] uppercase tracking-widest text-green-400 bg-green-500/10 border border-green-500/20 rounded-full px-2.5 py-1">Terbit</span>
                                @endif
                                <span class="text-xs text-gray-500">{{ $blog->displayDate()->format('d M Y') }}</span>
                            </div>

                            @if($blog->tagList() !== [])
                                <div class="flex flex-wrap gap-1.5 mb-3">
                                    @foreach(array_slice($blog->tagList(), 0, 2) as $tag)
                                        <span class="text-[10px] text-purple-300 bg-purple-500/10 border border-purple-500/20 rounded-full px-2 py-0.5">{{ $tag }}</span>
                                    @endforeach
                                    @if(count($blog->tagList()) > 2)
                                        <span class="text-[10px] text-gray-500">+{{ count($blog->tagList()) - 2 }}</span>
                                    @endif
                                </div>
                            @endif

                            <h3 class="font-bold mb-2">{{ $blog->title }}</h3>
                            <p class="text-gray-400 text-sm leading-relaxed mb-4">{{ Str::limit($blog->summary(), 100) }}</p>

                            <div class="mt-auto flex items-center gap-4 pt-4 border-t border-white/5">
                                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="text-purple-400 hover:text-purple-300 text-sm font-semibold transition">Edit</a>

                                @if($blog->is_published && (! $blog->published_at || $blog->published_at->isPast()))
                                    <a href="{{ route('blog.show', $blog->slug) }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white text-sm font-semibold transition">Lihat</a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Menu Pengelolaan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('admin.projects.index') }}" class="group bg-[#16161a] border border-white/10 rounded-2xl p-6 sm:p-8 hover:border-purple-500/50 transition-all duration-500">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 mb-4 group-hover:scale-110 group-hover:bg-purple-500/20 group-hover:text-purple-300 transition-all duration-300">
                    <x-admin-icon name="folder" class="w-6 h-6" />
                </div>
                <h2 class="text-xl font-bold mb-2 group-hover:text-purple-400 transition">Kelola Proyek</h2>
                <p class="text-gray-400 text-sm">Tambah, edit, atau hapus project portofolio</p>
            </a>

            <a href="{{ route('admin.blogs.index') }}" class="group bg-[#16161a] border border-white/10 rounded-2xl p-6 sm:p-8 hover:border-purple-500/50 transition-all duration-500">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 mb-4 group-hover:scale-110 group-hover:bg-purple-500/20 group-hover:text-purple-300 transition-all duration-300">
                    <x-admin-icon name="pen" class="w-6 h-6" />
                </div>
                <h2 class="text-xl font-bold mb-2 group-hover:text-purple-400 transition">Kelola Blog</h2>
                <p class="text-gray-400 text-sm">Tulis, edit, dan publikasikan artikel blog pengembangan</p>
            </a>

            <a href="{{ route('admin.certificates.index') }}" class="group bg-[#16161a] border border-white/10 rounded-2xl p-6 sm:p-8 hover:border-purple-500/50 transition-all duration-500">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 mb-4 group-hover:scale-110 group-hover:bg-purple-500/20 group-hover:text-purple-300 transition-all duration-300">
                    <x-admin-icon name="award" class="w-6 h-6" />
                </div>
                <h2 class="text-xl font-bold mb-2 group-hover:text-purple-400 transition">Kelola Sertifikat</h2>
                <p class="text-gray-400 text-sm">Tambah, edit, atau hapus sertifikat</p>
            </a>

            <a href="{{ route('admin.messages.index') }}" class="group bg-[#16161a] border border-white/10 rounded-2xl p-6 sm:p-8 hover:border-purple-500/50 transition-all duration-500 relative">
                @if($unreadCount > 0)
                    <span class="absolute top-6 right-6 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">{{ $unreadCount }} baru</span>
                @endif
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 mb-4 group-hover:scale-110 group-hover:bg-purple-500/20 group-hover:text-purple-300 transition-all duration-300">
                    <x-admin-icon name="mail" class="w-6 h-6" />
                </div>
                <h2 class="text-xl font-bold mb-2 group-hover:text-purple-400 transition">Pesan Masuk</h2>
                <p class="text-gray-400 text-sm">Baca, balas, dan kelola pesan dari form kontak</p>
            </a>

            <a href="{{ route('admin.guest-messages.index') }}" class="group bg-[#16161a] border border-white/10 rounded-2xl p-6 sm:p-8 hover:border-purple-500/50 transition-all duration-500 relative">
                @if($guestMessageCount > 0)
                    <span class="absolute top-6 right-6 bg-purple-500/20 text-purple-300 border border-purple-500/30 text-xs font-bold px-2.5 py-1 rounded-full">{{ $guestMessageCount }}</span>
                @endif
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 mb-4 group-hover:scale-110 group-hover:bg-purple-500/20 group-hover:text-purple-300 transition-all duration-300">
                    <x-admin-icon name="message" class="w-6 h-6" />
                </div>
                <h2 class="text-xl font-bold mb-2 group-hover:text-purple-400 transition">Kesan &amp; Pesan</h2>
                <p class="text-gray-400 text-sm">Kelola kesan dan pesan dari pengunjung (buku tamu)</p>
            </a>

            <a href="{{ route('admin.profile.edit') }}" class="group bg-[#16161a] border border-white/10 rounded-2xl p-6 sm:p-8 hover:border-purple-500/50 transition-all duration-500">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 mb-4 group-hover:scale-110 group-hover:bg-purple-500/20 group-hover:text-purple-300 transition-all duration-300">
                    <x-admin-icon name="user" class="w-6 h-6" />
                </div>
                <h2 class="text-xl font-bold mb-2 group-hover:text-purple-400 transition">Informasi Profil</h2>
                <p class="text-gray-400 text-sm">Ubah nama, bio, foto, dan kontak tanpa menyentuh kode</p>
            </a>

            <a href="/" class="group bg-[#16161a] border border-white/10 rounded-2xl p-6 sm:p-8 hover:border-purple-500/50 transition-all duration-500">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 mb-4 group-hover:scale-110 group-hover:bg-purple-500/20 group-hover:text-purple-300 transition-all duration-300">
                    <x-admin-icon name="eye" class="w-6 h-6" />
                </div>
                <h2 class="text-xl font-bold mb-2 group-hover:text-purple-400 transition">Lihat Portofolio</h2>
                <p class="text-gray-400 text-sm">Lihat halaman utama portofolio</p>
            </a>

            <a href="#cv" class="group bg-[#16161a] border border-white/10 rounded-2xl p-6 sm:p-8 hover:border-purple-500/50 transition-all duration-500">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 mb-4 group-hover:scale-110 group-hover:bg-purple-500/20 group-hover:text-purple-300 transition-all duration-300">
                    <x-admin-icon name="file" class="w-6 h-6" />
                </div>
                <h2 class="text-xl font-bold mb-2 group-hover:text-purple-400 transition">Kelola CV</h2>
                <p class="text-gray-400 text-sm">Unggah atau hapus CV yang bisa diunduh pengunjung</p>
            </a>
        </div>

        {{-- Kelola CV --}}
        @php
            $cvExtension = $profile?->cv_file ? strtoupper(pathinfo($profile->cv_file, PATHINFO_EXTENSION)) : null;
        @endphp
        <div id="cv" class="mt-6 bg-[#16161a] border border-white/10 rounded-2xl p-6 md:p-8 scroll-mt-24">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        </div>
                        <h2 class="text-xl font-bold">Curriculum Vitae (CV)</h2>
                    </div>
                    <p class="text-gray-400 text-sm max-w-xl">
                        Unggah CV dalam format PDF, DOC, atau DOCX. Jika CV tersedia, tombol
                        <span class="text-gray-300">Download CV</span> akan otomatis muncul di halaman portofolio.
                    </p>
                </div>

                @if($profile?->cv_file)
                    <span class="inline-flex items-center gap-2 self-start text-xs font-semibold text-green-400 bg-green-500/10 border border-green-500/30 rounded-full px-3 py-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                        CV Aktif
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 self-start text-xs font-semibold text-gray-400 bg-white/5 border border-white/10 rounded-full px-3 py-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span>
                        Belum Ada CV
                    </span>
                @endif
            </div>

            @if($profile?->cv_file)
                <div class="mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-[#0b0b0f] border border-white/5 rounded-xl p-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-400 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium truncate">Curriculum Vitae.{{ strtolower($cvExtension) }}</p>
                            <p class="text-xs text-gray-500">{{ $cvExtension }} • Siap diunduh pengunjung</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <a href="{{ route('cv.download') }}" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Download
                        </a>

                        <form action="{{ route('admin.cv.delete') }}" method="POST" onsubmit="return confirm('Hapus CV ini? Pengunjung tidak akan bisa mengunduh CV sampai kamu mengunggahnya lagi.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-2 border border-red-500/30 text-red-400 hover:bg-red-500/10 text-sm font-medium px-4 py-2 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                Hapus CV
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="mt-6 border border-dashed border-white/10 rounded-xl p-6 text-center">
                    <p class="text-sm text-gray-400">Belum ada CV yang diunggah. Upload file CV pertamamu di bawah ini.</p>
                </div>
            @endif

            <form action="{{ route('admin.cv.upload') }}" method="POST" enctype="multipart/form-data" class="mt-6">
                @csrf
                <label for="cv" class="block text-sm font-medium text-gray-300 mb-2">Pilih file CV</label>
                <div class="flex flex-col sm:flex-row gap-3 sm:items-start">
                    <div class="flex-1">
                        <input
                            id="cv"
                            type="file"
                            name="cv"
                            accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                            required
                            class="block w-full text-sm text-gray-400 cursor-pointer bg-[#0b0b0f] border border-white/10 rounded-lg p-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-600 file:text-white hover:file:bg-purple-700 file:cursor-pointer"
                        >
                        <p class="text-xs text-gray-500 mt-2">Format PDF, DOC, atau DOCX. Maksimal 10 MB.</p>
                        @error('cv')
                            <p class="text-sm text-red-400 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-medium px-6 py-2.5 rounded-lg transition shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        {{ $profile?->cv_file ? 'Ganti CV' : 'Upload CV' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
