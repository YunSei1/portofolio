<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kesan &amp; Pesan — Admin</title>
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

    <div class="max-w-4xl mx-auto px-4 sm:px-6 md:px-10 py-10 sm:py-12 relative z-10">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white text-sm transition mb-6 inline-block">&larr; Kembali ke Dashboard</a>

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-10">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tighter bg-gradient-to-b from-white to-gray-500 bg-clip-text text-transparent">Kesan &amp; Pesan</h1>
                <p class="text-gray-400 text-sm mt-1">
                    Kelola kesan dan pesan dari pengunjung (buku tamu)
                    @if($guestMessages->isNotEmpty())
                        — <span class="text-purple-400 font-semibold">{{ $guestMessages->count() }} pesan</span>
                    @endif
                </p>
            </div>
            <a href="{{ route('guestbook.index') }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 bg-transparent border border-white/20 hover:border-purple-500 text-gray-300 hover:text-white px-5 py-2.5 rounded-lg font-semibold transition text-sm self-start">
                Lihat Halaman Publik
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-3 text-sm text-green-400 mb-6">{{ session('success') }}</div>
        @endif

        @if($guestMessages->isEmpty())
            <div class="bg-[#16161a] border border-white/10 rounded-2xl p-12 text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 mb-5">
                    <x-admin-icon name="message" class="w-7 h-7" />
                </div>
                <h3 class="text-lg font-bold mb-2">Belum ada kesan &amp; pesan</h3>
                <p class="text-gray-400 text-sm">Kesan dan pesan yang dikirim pengunjung akan muncul di sini.</p>
            </div>
        @else
            <div class="flex flex-col gap-4">
                @foreach($guestMessages as $entry)
                    <div class="bg-[#16161a] border border-white/10 rounded-2xl p-6 hover:border-purple-500/50 transition-all duration-500">
                        <div class="flex justify-between items-start gap-4 mb-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 shrink-0 rounded-full bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 font-bold text-sm">
                                    {{ strtoupper(mb_substr($entry->name, 0, 1)) }}
                                </div>
                                <h3 class="text-lg font-bold text-white truncate">{{ $entry->name }}</h3>
                            </div>
                            <span class="text-gray-500 text-xs shrink-0">{{ $entry->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <p class="text-gray-300 leading-relaxed whitespace-pre-line break-words">{{ $entry->message }}</p>
                        <div class="flex flex-wrap items-center gap-3 mt-4">
                            <a href="{{ route('guestbook.index') }}" target="_blank" rel="noopener" class="text-purple-400 hover:text-purple-300 text-sm font-semibold transition">Lihat di Halaman Publik</a>
                            <form action="{{ route('admin.guest-messages.destroy', $entry->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kesan & pesan ini?')" class="ml-auto">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-semibold transition">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</body>
</html>
