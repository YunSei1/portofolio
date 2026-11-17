<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Proyek — Admin</title>
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

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-10">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tighter bg-gradient-to-b from-white to-gray-500 bg-clip-text text-transparent">Kelola Proyek</h1>
                <p class="text-gray-400 text-sm mt-1">Kelola project portofolio kamu</p>
            </div>
            <a href="{{ route('admin.projects.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg font-semibold transition shadow-[0_0_15px_rgba(147,51,234,0.3)] text-sm">
                + Tambah Project
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-3 text-sm text-green-400 mb-6">{{ session('success') }}</div>
        @endif

        {{-- Publikasi Blog --}}
        @php $blogOptions = $blogs ?? collect(); @endphp
        <div class="bg-[#16161a] border border-white/10 rounded-2xl p-5 md:p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-5">
                <div>
                    <h2 class="text-lg font-bold">Publikasi Blog</h2>
                    <p class="text-gray-400 text-sm mt-1">Pilih blog, lalu tentukan apakah blog tersebut <span class="text-green-400 font-semibold">Publikasi</span> atau <span class="text-yellow-400 font-semibold">Private</span>.</p>
                </div>
                <a href="{{ route('admin.blogs.index') }}" class="self-start text-sm font-semibold text-purple-400 hover:text-purple-300 transition">Kelola Blog &rarr;</a>
            </div>

            @if($blogOptions->isEmpty())
                <p class="text-gray-400 text-sm">
                    Belum ada blog yang bisa diatur.
                    <a href="{{ route('admin.blogs.create') }}" class="text-purple-400 hover:text-purple-300 font-semibold transition">Tulis artikel</a> terlebih dahulu.
                </p>
            @else
                <form action="{{ route('admin.blogs.visibility') }}" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="redirect_to" value="projects">

                    <div class="md:col-span-6">
                        <label for="blog_id" class="block text-xs text-gray-400 font-medium uppercase tracking-widest ml-1 mb-1.5">Pilih Blog</label>
                        <select name="blog_id" id="blog_id" required
                                class="w-full bg-[#0b0b0f] border border-white/10 rounded-lg px-3 py-2.5 text-sm text-gray-300 focus:border-purple-500 outline-none transition @error('blog_id') border-red-500 @enderror">
                            <option value="">— Pilih blog —</option>
                            @foreach($blogOptions as $blog)
                                <option value="{{ $blog->id }}" @selected((string) old('blog_id') === (string) $blog->id)>
                                    {{ $blog->title }} — {{ $blog->is_published ? 'Publikasi' : 'Private' }}
                                </option>
                            @endforeach
                        </select>
                        @error('blog_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label for="is_published" class="block text-xs text-gray-400 font-medium uppercase tracking-widest ml-1 mb-1.5">Status Blog</label>
                        <select name="is_published" id="is_published" required
                                class="w-full bg-[#0b0b0f] border border-white/10 rounded-lg px-3 py-2.5 text-sm text-gray-300 focus:border-purple-500 outline-none transition @error('is_published') border-red-500 @enderror">
                            <option value="1" @selected(old('is_published', '1') === '1')>Publikasi</option>
                            <option value="0" @selected(old('is_published') === '0')>Private</option>
                        </select>
                        @error('is_published') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-3 flex items-end">
                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition shadow-[0_0_15px_rgba(147,51,234,0.25)]">
                            Simpan Status
                        </button>
                    </div>
                </form>

                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($blogOptions as $blog)
                        <span class="inline-flex items-center gap-2 text-[11px] rounded-full px-2.5 py-1 border {{ $blog->is_published ? 'text-green-400 bg-green-500/10 border-green-500/20' : 'text-yellow-400 bg-yellow-500/10 border-yellow-500/20' }}">
                            <span class="max-w-[180px] truncate">{{ $blog->title }}</span>
                            <span class="uppercase tracking-widest text-[10px]">{{ $blog->is_published ? 'Publikasi' : 'Private' }}</span>
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        @php
            $activeFilters = collect($filters ?? [])->filter();
        @endphp

        <form method="GET" action="{{ route('admin.projects.index') }}" class="bg-[#16161a] border border-white/10 rounded-2xl p-4 md:p-5 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-4 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari judul, deskripsi, teknologi, atau role..." aria-label="Cari project"
                           class="w-full bg-[#0b0b0f] border border-white/10 rounded-lg pl-10 pr-3 py-2.5 text-sm text-white placeholder:text-gray-600 focus:border-purple-500 outline-none transition">
                </div>

                <div class="md:col-span-2">
                    <select name="technology" onchange="this.form.submit()" aria-label="Filter teknologi"
                            class="w-full bg-[#0b0b0f] border border-white/10 rounded-lg px-3 py-2.5 text-sm text-gray-300 focus:border-purple-500 outline-none transition">
                        <option value="">Semua Teknologi</option>
                        @foreach($technologies ?? [] as $technology)
                            <option value="{{ $technology }}" @selected(($filters['technology'] ?? '') === $technology)>{{ $technology }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <select name="role" onchange="this.form.submit()" aria-label="Filter role"
                            class="w-full bg-[#0b0b0f] border border-white/10 rounded-lg px-3 py-2.5 text-sm text-gray-300 focus:border-purple-500 outline-none transition">
                        <option value="">Semua Role</option>
                        @foreach($roles ?? [] as $role)
                            <option value="{{ $role }}" @selected(($filters['role'] ?? '') === $role)>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <select name="status" onchange="this.form.submit()" aria-label="Filter status publikasi"
                            class="w-full bg-[#0b0b0f] border border-white/10 rounded-lg px-3 py-2.5 text-sm text-gray-300 focus:border-purple-500 outline-none transition">
                        <option value="">Semua Status</option>
                        <option value="published" @selected(($filters['status'] ?? '') === 'published')>Publikasi</option>
                        <option value="private" @selected(($filters['status'] ?? '') === 'private')>Private</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <select name="sort" onchange="this.form.submit()" aria-label="Urutkan project"
                            class="w-full bg-[#0b0b0f] border border-white/10 rounded-lg px-3 py-2.5 text-sm text-gray-300 focus:border-purple-500 outline-none transition">
                        <option value="latest" @selected(($filters['sort'] ?? 'latest') === 'latest')>Terbaru</option>
                        <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Terlama</option>
                        <option value="title_asc" @selected(($filters['sort'] ?? '') === 'title_asc')>Judul A-Z</option>
                        <option value="title_desc" @selected(($filters['sort'] ?? '') === 'title_desc')>Judul Z-A</option>
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
                    <a href="{{ route('admin.projects.index') }}" class="text-gray-400 hover:text-white text-sm transition">Reset filter</a>
                    <span class="text-xs text-gray-500">{{ $projects->count() }} hasil</span>
                @endif
            </div>
        </form>

        @if($projects->isEmpty())
            <div class="text-center py-16 border border-dashed border-white/10 rounded-2xl">
                @if($activeFilters->isNotEmpty())
                    <p class="text-gray-400">Tidak ada project yang cocok dengan pencarian atau filter.</p>
                    <a href="{{ route('admin.projects.index') }}" class="inline-block mt-3 text-sm font-semibold text-purple-400 hover:text-purple-300 transition">Reset pencarian</a>
                @else
                    <p class="text-gray-400">Belum ada project. Klik "Tambah Project" untuk memulai.</p>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($projects as $project)
                <div class="bg-[#16161a] border border-white/10 rounded-2xl overflow-hidden hover:border-purple-500/50 transition-all duration-500">
                    @if($project->image)
                        <img src="{{ asset('storage/' . $project->image) }}" class="w-full h-48 object-cover opacity-80">
                    @else
                        <div class="w-full h-48 bg-[#1e1e24] flex items-center justify-center text-gray-500 text-sm">No Image</div>
                    @endif
                    <div class="p-6">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            @if($project->is_published)
                                <span class="text-[11px] uppercase tracking-widest text-green-400 bg-green-500/10 border border-green-500/20 rounded-full px-2.5 py-1">{{ $project->visibilityLabel() }}</span>
                            @else
                                <span class="text-[11px] uppercase tracking-widest text-yellow-400 bg-yellow-500/10 border border-yellow-500/20 rounded-full px-2.5 py-1">{{ $project->visibilityLabel() }}</span>
                            @endif
                            @if($project->role)
                                <span class="text-[11px] uppercase tracking-widest text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-full px-2.5 py-1">{{ $project->role }}</span>
                            @endif
                            @foreach(array_slice($project->technologyList(), 0, 3) as $technology)
                                <span class="text-[11px] text-gray-400 bg-white/5 border border-white/10 rounded-full px-2.5 py-1">{{ $technology }}</span>
                            @endforeach
                        </div>

                        <h3 class="text-lg font-bold mb-1">{{ $project->title }}</h3>
                        <p class="text-gray-400 text-sm mb-4">{{ Str::limit($project->description, 120) }}</p>

                        <div class="flex flex-wrap items-center gap-4">
                            <a href="{{ route('projects.show', $project) }}" target="_blank" class="text-gray-300 hover:text-white text-sm font-semibold transition">Lihat Detail</a>
                            <a href="{{ route('admin.projects.edit', $project->id) }}" class="text-purple-400 hover:text-purple-300 text-sm font-semibold transition">Edit</a>
                            <form action="{{ route('admin.projects.toggle', $project->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="{{ $project->is_published ? 'text-yellow-400 hover:text-yellow-300' : 'text-green-400 hover:text-green-300' }} text-sm font-semibold transition">
                                    {{ $project->is_published ? 'Jadikan Private' : 'Publikasikan' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-semibold transition">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</body>
</html>
