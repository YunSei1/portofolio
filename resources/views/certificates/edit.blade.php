<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sertifikat — Admin</title>
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
        <a href="{{ route('admin.certificates.index') }}" class="text-gray-400 hover:text-white text-sm transition mb-6 inline-block">&larr; Kembali ke Sertifikat</a>

        <div class="bg-[#16161a] border border-white/10 rounded-2xl p-6 sm:p-8">
            <h2 class="text-2xl font-bold mb-6 bg-gradient-to-b from-white to-gray-500 bg-clip-text text-transparent">Edit Sertifikat</h2>

            <form action="{{ route('admin.certificates.update', $certificate->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-3 text-sm text-red-400">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Judul Sertifikat</label>
                    <input type="text" name="title" value="{{ old('title', $certificate->title) }}"
                           class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 @error('title') border-red-500 @enderror" required>
                    @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Lembaga Penerbit (Opsional)</label>
                    <input type="text" name="issuer" value="{{ old('issuer', $certificate->issuer) }}"
                           class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Tanggal Terbit (Opsional)</label>
                    <input type="date" name="issue_date" value="{{ old('issue_date', $certificate->issue_date?->format('Y-m-d')) }}"
                           class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition text-gray-300">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="4"
                              class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 resize-none">{{ old('description', $certificate->description) }}</textarea>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Upload Gambar Sertifikat</label>
                    @if($certificate->image)
                        <img src="{{ asset('storage/' . $certificate->image) }}" class="w-full h-40 object-contain bg-[#1e1e24] rounded-lg border border-white/10 mb-2">
                    @endif
                    <input type="file" name="image" accept="image/*"
                           class="text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-600 file:text-white hover:file:bg-purple-700 file:transition @error('image') file:bg-red-600 @enderror">
                    <p class="text-gray-500 text-xs">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                    @error('image') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition shadow-[0_0_15px_rgba(147,51,234,0.3)]">Perbarui Sertifikat</button>
                    <a href="{{ route('admin.certificates.index') }}" class="text-gray-400 hover:text-white text-sm transition">Batal</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
