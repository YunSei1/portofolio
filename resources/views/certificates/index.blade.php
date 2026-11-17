<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Sertifikat — Admin</title>
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
                <h1 class="text-3xl font-extrabold tracking-tighter bg-gradient-to-b from-white to-gray-500 bg-clip-text text-transparent">Kelola Sertifikat</h1>
                <p class="text-gray-400 text-sm mt-1">Tambah, edit, atau hapus sertifikat portofolio</p>
            </div>
            <a href="{{ route('admin.certificates.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg font-semibold transition shadow-[0_0_15px_rgba(147,51,234,0.3)] text-sm">
                + Tambah Sertifikat
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-3 text-sm text-green-400 mb-6">{{ session('success') }}</div>
        @endif

        @if($certificates->isEmpty())
            <div class="bg-[#16161a] border border-white/10 rounded-2xl p-12 text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 mb-5">
                    <x-admin-icon name="award" class="w-7 h-7" />
                </div>
                <h3 class="text-lg font-bold mb-2">Belum ada sertifikat</h3>
                <p class="text-gray-400 text-sm mb-6">Mulai tambahkan sertifikat pertamamu untuk ditampilkan di halaman utama.</p>
                <a href="{{ route('admin.certificates.create') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg font-semibold transition text-sm">+ Tambah Sertifikat</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($certificates as $certificate)
                    <div class="bg-[#16161a] border border-white/10 rounded-2xl overflow-hidden hover:border-purple-500/50 transition-all duration-500">
                        @if($certificate->image)
                            <img src="{{ asset('storage/' . $certificate->image) }}" class="w-full h-48 object-cover opacity-80">
                        @else
                            <div class="w-full h-48 bg-[#1e1e24] flex items-center justify-center text-gray-500 text-sm">No Image</div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-lg font-bold mb-1">{{ $certificate->title }}</h3>
                            <div class="flex items-center gap-3 mb-2">
                                @if($certificate->issuer)
                                    <span class="text-purple-400 text-xs uppercase tracking-widest">{{ $certificate->issuer }}</span>
                                @endif
                                @if($certificate->issue_date)
                                    <span class="text-gray-400 text-xs">{{ $certificate->issue_date->format('d M Y') }}</span>
                                @endif
                            </div>
                            @if($certificate->description)
                                <p class="text-gray-400 text-sm mb-4">{{ Str::limit($certificate->description, 100) }}</p>
                            @endif
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.certificates.edit', $certificate->id) }}" class="text-purple-400 hover:text-purple-300 text-sm font-semibold transition">Edit</a>
                                <form action="{{ route('admin.certificates.destroy', $certificate->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus sertifikat ini?')">
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
