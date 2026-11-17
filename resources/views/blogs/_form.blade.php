@php
    $blog = $blog ?? null;
@endphp

@if($errors->any())
    <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-3 text-sm text-red-400">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

{{-- Informasi Artikel --}}
<div class="flex flex-col gap-5">
    <p class="text-xs uppercase tracking-widest text-purple-400 font-bold border-b border-white/10 pb-2">Informasi Artikel</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="flex flex-col gap-1.5">
            <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Judul Artikel <span class="text-red-400">*</span></label>
            <input type="text" name="title" placeholder="Misal: Tips Membuat Layout Responsif dengan Tailwind" value="{{ old('title', $blog?->title) }}"
                   class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 @error('title') border-red-500 @enderror" required>
            @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Slug URL</label>
            <input type="text" name="slug" placeholder="tips-layout-responsif" value="{{ old('slug', $blog?->slug) }}"
                   class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 @error('slug') border-red-500 @enderror">
            <p class="text-xs text-gray-500">Kosongkan untuk dibuat otomatis dari judul.</p>
            @error('slug') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex flex-col gap-1.5">
        <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Ringkasan Singkat</label>
        <textarea name="excerpt" rows="3" maxlength="500" placeholder="Ringkasan singkat yang tampil di halaman daftar blog..."
                  class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 resize-none @error('excerpt') border-red-500 @enderror">{{ old('excerpt', $blog?->excerpt) }}</textarea>
        <p class="text-xs text-gray-500">Opsional. Jika kosong, ringkasan diambil otomatis dari isi artikel.</p>
        @error('excerpt') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-col gap-1.5">
        <label for="tags" class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Tag Artikel</label>
        <input type="text" id="tags" name="tags"
               placeholder="Contoh: Web Development, Anime, Tutorial"
               value="{{ old('tags', implode(', ', $blog?->tagList() ?? [])) }}"
               class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 @error('tags') border-red-500 @enderror">
        <p class="text-xs text-gray-500">Pisahkan beberapa tag dengan koma. Maksimal 8 tag, masing-masing 30 karakter.</p>
        @error('tags') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-col gap-1.5">
        <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Isi Artikel <span class="text-red-400">*</span></label>
        <textarea name="content" rows="16" placeholder="Tulis artikel lengkap di sini...&#10;&#10;Gunakan baris baru untuk memisahkan paragraf."
                  class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 resize-y font-mono text-sm leading-relaxed @error('content') border-red-500 @enderror" required>{{ old('content', $blog?->content) }}</textarea>
        <p class="text-xs text-gray-500">Mendukung teks biasa. Baris baru akan ditampilkan sebagai paragraf di halaman publik.</p>
        @error('content') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-col gap-1.5">
        <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Gambar Cover</label>

        @if($blog?->cover_image)
            <div class="flex items-center gap-4 mb-2">
                <img src="{{ asset('storage/' . $blog->cover_image) }}" alt="Cover {{ $blog->title }}" class="w-40 h-24 object-cover rounded-lg border border-white/10">
                <p class="text-gray-500 text-xs">Cover saat ini. Upload gambar baru untuk menggantinya.</p>
            </div>
        @endif

        <input type="file" name="cover_image" accept="image/*"
               class="text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-600 file:text-white hover:file:bg-purple-700 file:transition @error('cover_image') file:bg-red-600 @enderror">
        <p class="text-xs text-gray-500">Format JPG, PNG, atau WEBP. Maksimal 5 MB.</p>
        @error('cover_image') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Publikasi --}}
<div class="flex flex-col gap-5">
    <p class="text-xs uppercase tracking-widest text-purple-400 font-bold border-b border-white/10 pb-2">Publikasi</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="flex flex-col gap-1.5">
            <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Tanggal Publikasi</label>
            <input type="datetime-local" name="published_at" value="{{ old('published_at', $blog?->published_at?->format('Y-m-d\TH:i')) }}"
                   class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition text-gray-300 @error('published_at') border-red-500 @enderror">
            <p class="text-xs text-gray-500">Kosongkan untuk menggunakan waktu sekarang saat artikel dipublikasikan. Isi tanggal di masa depan untuk menjadwalkan.</p>
            @error('published_at') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Status</label>
            <input type="hidden" name="is_published" value="0">
            <label class="flex items-start gap-3 bg-[#0b0b0f] border border-white/10 rounded-lg p-4 cursor-pointer hover:border-purple-500/40 transition">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $blog?->is_published ?? true))
                       class="mt-0.5 w-4 h-4 accent-purple-600">
                <span>
                    <span class="block text-sm font-medium text-gray-200">Publikasikan artikel</span>
                    <span class="block text-xs text-gray-500 mt-1">Jika tidak dicentang, artikel disimpan sebagai draf dan tidak tampil di halaman blog.</span>
                </span>
            </label>
            @error('is_published') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    </div>
</div>
