<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Profil — Admin</title>
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
        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white text-sm transition mb-6 inline-block">&larr; Kembali ke Dashboard</a>

        <div class="bg-[#16161a] border border-white/10 rounded-2xl p-6 sm:p-8">
            <h2 class="text-2xl font-bold mb-2 bg-gradient-to-b from-white to-gray-500 bg-clip-text text-transparent">Informasi Profil</h2>
            <p class="text-gray-400 text-sm mb-6">Perubahan di sini langsung tampil di halaman utama tanpa mengubah kode.</p>

            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
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
                    <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $profile->name) }}"
                           class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition @error('name') border-red-500 @enderror" required>
                    @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Role / Jabatan</label>
                    <input type="text" name="role" value="{{ old('role', $profile->role) }}" placeholder="Misal: Front-End Developer"
                           class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 @error('role') border-red-500 @enderror" required>
                    @error('role') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Teks Badge (Opsional)</label>
                    <input type="text" name="badge_text" value="{{ old('badge_text', $profile->badge_text) }}" placeholder="Misal: Available for New Projects"
                           class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600">
                    <p class="text-gray-500 text-xs">Badge kecil di atas nama pada halaman utama.</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Bio / Deskripsi</label>
                    <textarea name="bio" rows="5" placeholder="Ceritakan tentang kamu..."
                              class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 resize-none">{{ old('bio', $profile->bio) }}</textarea>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Foto Profil</label>
                    @if($profile->photo)
                        <img src="{{ asset('storage/' . $profile->photo) }}" class="w-32 h-40 object-cover rounded-xl border border-white/10 mb-2">
                        <label class="flex items-center gap-2 text-gray-400 text-xs mb-2 cursor-pointer">
                            <input type="checkbox" name="remove_photo" value="1" class="accent-purple-600">
                            Hapus foto profil (kembali ke foto default)
                        </label>
                    @else
                        <img src="{{ asset('assets/img/iki.png') }}" class="w-32 h-40 object-cover rounded-xl border border-white/10 mb-2 opacity-60">
                        <p class="text-gray-500 text-xs mb-2">Sedang memakai foto default (assets/img/iki.png).</p>
                    @endif
                    <input type="file" name="photo" accept="image/*"
                           class="text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-600 file:text-white hover:file:bg-purple-700 file:transition @error('photo') file:bg-red-600 @enderror">
                    @error('photo') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Link Instagram (Opsional)</label>
                    <input type="url" name="instagram_url" value="{{ old('instagram_url', $profile->instagram_url) }}" placeholder="https://www.instagram.com/username/"
                           class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 @error('instagram_url') border-red-500 @enderror">
                    @error('instagram_url') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Nomor WhatsApp (Opsional)</label>
                    <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $profile->whatsapp_number) }}" placeholder="Misal: 6281234567890"
                           class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600">
                    <p class="text-gray-500 text-xs">Gunakan format internasional tanpa tanda + (contoh: 6281234567890).</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Email Kontak (Opsional)</label>
                    <input type="email" name="email" value="{{ old('email', $profile->email) }}" placeholder="email@contoh.com"
                           class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 @error('email') border-red-500 @enderror">
                    <p class="text-gray-500 text-xs">Pesan dari form kontak juga dikirim ke email ini.</p>
                    @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition shadow-[0_0_15px_rgba(147,51,234,0.3)]">Simpan Profil</button>
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white text-sm transition">Batal</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
