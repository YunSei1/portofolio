<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Blog — Admin</title>
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
            <span class="text-purple-400 text-xs font-mono uppercase tracking-widest truncate max-w-[45vw] sm:max-w-none">{{ auth()->user()->username }}</span>
            <form action="{{ route('logout') }}" method="POST" class="shrink-0">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-red-400 text-xs transition uppercase font-bold">Logout</button>
            </form>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 md:px-10 py-10 sm:py-12 relative z-10">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white text-sm transition mb-6 inline-block">&larr; Kembali ke Dashboard</a>

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-10">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tighter bg-gradient-to-b from-white to-gray-500 bg-clip-text text-transparent">Kelola Blog</h1>
                <p class="text-gray-400 text-sm mt-1">Tulis, edit, dan publikasikan artikel blog pengembangan</p>
            </div>
            <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg font-semibold transition shadow-[0_0_15px_rgba(147,51,234,0.3)] text-sm">
                + Tulis Artikel
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-3 text-sm text-green-400 mb-6">{{ session('success') }}</div>
        @endif

        @php
            $activeFilters = collect([
                'q' => $q,
                'status' => $status,
                'sort' => $sort !== 'latest' ? $sort : null,
            ])->filter();
        @endphp

        <form method="GET" action="{{ route('admin.blogs.index') }}" class="bg-[#16161a] border border-white/10 rounded-2xl p-4 md:p-5 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-6 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                    <input type="search" name="q" value="{{ $q }}" placeholder="Cari judul atau isi artikel..." aria-label="Cari artikel"
                           class="w-full bg-[#0b0b0f] border border-white/10 rounded-lg pl-10 pr-3 py-2.5 text-sm text-white placeholder:text-gray-600 focus:border-purple-500 outline-none transition">
                </div>

                <div class="md:col-span-3">
                    <select name="status" onchange="this.form.submit()" aria-label="Filter status artikel"
                            class="w-full bg-[#0b0b0f] border border-white/10 rounded-lg px-3 py-2.5 text-sm text-gray-300 focus:border-purple-500 outline-none transition">
                        <option value="">Semua Status</option>
                        <option value="published" @selected($status === 'published')>Terbit</option>
                        <option value="scheduled" @selected($status === 'scheduled')>Terjadwal</option>
                        <option value="draft" @selected($status === 'draft')>Private</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <select name="sort" onchange="this.form.submit()" aria-label="Urutkan artikel"
                            class="w-full bg-[#0b0b0f] border border-white/10 rounded-lg px-3 py-2.5 text-sm text-gray-300 focus:border-purple-500 outline-none transition">
                        <option value="latest" @selected($sort === 'latest')>Terbaru</option>
                        <option value="oldest" @selected($sort === 'oldest')>Terlama</option>
                        <option value="title_asc" @selected($sort === 'title_asc')>Judul A-Z</option>
                        <option value="title_desc" @selected($sort === 'title_desc')>Judul Z-A</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 mt-4">
                <button type="submit" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                    Cari
                </button>

                @if($activeFilters->isNotEmpty())
                    <a href="{{ route('admin.blogs.index') }}" class="text-gray-400 hover:text-white text-sm transition">Reset filter</a>
                    <span class="text-xs text-gray-500">{{ $blogs->total() }} artikel ditemukan</span>
                @endif
            </div>
        </form>

        @if($blogs->isEmpty())
            <div class="bg-[#16161a] border border-white/10 rounded-2xl p-12 text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 mb-5">
                    <x-admin-icon name="pen" class="w-7 h-7" />
                </div>
                @if($activeFilters->isNotEmpty())
                    <h3 class="text-lg font-bold mb-2">Tidak ada artikel yang cocok</h3>
                    <p class="text-gray-400 text-sm mb-6">Coba kata kunci atau filter lain, lalu reset untuk melihat semua artikel.</p>
                    <a href="{{ route('admin.blogs.index') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg font-semibold transition text-sm">Reset Pencarian</a>
                @else
                    <h3 class="text-lg font-bold mb-2">Belum ada artikel blog</h3>
                    <p class="text-gray-400 text-sm mb-6">Mulai tulis artikel pertama untuk dibagikan ke pengunjung.</p>
                    <a href="{{ route('admin.blogs.create') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg font-semibold transition text-sm">+ Tulis Artikel</a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($blogs as $blog)
                    <article class="bg-[#16161a] border border-white/10 rounded-2xl overflow-hidden hover:border-purple-500/50 transition-all duration-500 flex flex-col">
                        @if($blog->cover_image)
                            <img src="{{ asset('storage/' . $blog->cover_image) }}" alt="Cover {{ $blog->title }}" class="w-full h-44 object-cover opacity-90">
                        @else
                            <div class="w-full h-44 bg-gradient-to-br from-purple-500/10 to-fuchsia-500/10 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12 text-purple-500/40">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                                </svg>
                            </div>
                        @endif

                        <div class="p-6 flex flex-col flex-1">
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
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach($blog->tagList() as $tag)
                                        <span class="text-[11px] text-purple-300 bg-purple-500/10 border border-purple-500/20 rounded-full px-2.5 py-1">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <h3 class="text-lg font-bold mb-2">{{ $blog->title }}</h3>
                            <p class="text-gray-400 text-sm leading-relaxed mb-5">{{ Str::limit($blog->summary(), 120) }}</p>

                            <div class="mt-auto flex flex-wrap items-center gap-4 pt-4 border-t border-white/5">
                                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="text-purple-400 hover:text-purple-300 text-sm font-semibold transition">Edit</a>

                                <form action="{{ route('admin.blogs.toggle', $blog->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $blog->is_published ? 'text-yellow-400 hover:text-yellow-300' : 'text-green-400 hover:text-green-300' }} text-sm font-semibold transition">
                                        {{ $blog->is_published ? 'Jadikan Private' : 'Publikasikan' }}
                                    </button>
                                </form>

                                @if($blog->is_published && (! $blog->published_at || $blog->published_at->isPast()))
                                    <a href="{{ route('blog.show', $blog->slug) }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white text-sm font-semibold transition">Lihat</a>
                                @endif

                                <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')" class="ml-auto">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-semibold transition">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if($blogs->hasPages())
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-10">
                    <p class="text-sm text-gray-500">
                        Menampilkan {{ $blogs->firstItem() }}–{{ $blogs->lastItem() }} dari {{ $blogs->total() }} artikel
                    </p>
                    <div class="flex items-center gap-2">
                        @if($blogs->onFirstPage())
                            <span class="px-4 py-2 rounded-lg border border-white/10 text-gray-600 text-sm cursor-not-allowed">Sebelumnya</span>
                        @else
                            <a href="{{ $blogs->previousPageUrl() }}" class="px-4 py-2 rounded-lg border border-white/10 text-gray-300 hover:border-purple-500/50 hover:text-purple-400 text-sm transition">Sebelumnya</a>
                        @endif

                        <span class="px-3 py-2 text-sm text-gray-500">{{ $blogs->currentPage() }} / {{ $blogs->lastPage() }}</span>

                        @if($blogs->hasMorePages())
                            <a href="{{ $blogs->nextPageUrl() }}" class="px-4 py-2 rounded-lg border border-white/10 text-gray-300 hover:border-purple-500/50 hover:text-purple-400 text-sm transition">Berikutnya</a>
                        @else
                            <span class="px-4 py-2 rounded-lg border border-white/10 text-gray-600 text-sm cursor-not-allowed">Berikutnya</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>

</body>
</html>
