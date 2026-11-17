<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesan — Admin</title>
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

    <div class="max-w-2xl mx-auto px-4 sm:px-6 md:px-10 py-10 sm:py-12 relative z-10">
        <a href="{{ route('admin.messages.index') }}" class="text-gray-400 hover:text-white text-sm transition mb-6 inline-block">&larr; Kembali ke Pesan Masuk</a>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-3 text-sm text-green-400 mb-6">{{ session('success') }}</div>
        @endif

        <div class="bg-[#16161a] border border-white/10 rounded-2xl p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-6 pb-6 border-b border-white/10">
                <div>
                    <h2 class="text-2xl font-bold mb-1">{{ $message->name }}</h2>
                    <a href="mailto:{{ $message->email }}" class="text-purple-400 text-sm hover:text-purple-300 transition">{{ $message->email }}</a>
                </div>
                <span class="text-gray-500 text-xs">{{ $message->created_at->format('d M Y, H:i') }}</span>
            </div>

            <p class="text-gray-300 leading-relaxed whitespace-pre-line mb-8">{{ $message->message }}</p>

            <div class="flex flex-wrap items-center gap-3 pt-6 border-t border-white/10">
                <a href="mailto:{{ $message->email }}?subject=Re: Pesan dari portofolio"
                   class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg font-semibold transition text-sm">Balas via Email</a>

                <form action="{{ route('admin.messages.toggle', $message->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-transparent border border-white/20 hover:border-purple-500 text-gray-300 hover:text-white px-5 py-2.5 rounded-lg font-semibold transition text-sm">
                        Tandai {{ $message->is_read ? 'Belum Dibaca' : 'Sudah Dibaca' }}
                    </button>
                </form>

                <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesan ini?')" class="ml-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-semibold transition">Hapus Pesan</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
