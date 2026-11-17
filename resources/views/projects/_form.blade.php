@php
    $project = $project ?? null;
@endphp

@if($errors->any())
    <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-3 text-sm text-red-400">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

{{-- Informasi Utama --}}
<div class="flex flex-col gap-5">
    <p class="text-xs uppercase tracking-widest text-purple-400 font-bold border-b border-white/10 pb-2">Informasi Utama</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="flex flex-col gap-1.5">
            <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Nama Project <span class="text-red-400">*</span></label>
            <input type="text" name="title" placeholder="Misal: Website E-commerce" value="{{ old('title', $project?->title) }}"
                   class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 @error('title') border-red-500 @enderror" required>
            @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Peran dalam Project</label>
            <input type="text" name="role" placeholder="Misal: Front-End Developer" value="{{ old('role', $project?->role) }}"
                   class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 @error('role') border-red-500 @enderror">
            @error('role') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    @php
        $isPublished = (bool) old('is_published', $project?->is_published ?? true);
    @endphp

    <div class="flex flex-col gap-2">
        <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Status Publikasi Project</label>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <label class="flex items-start gap-3 bg-transparent border border-white/10 hover:border-purple-500/50 has-[:checked]:border-purple-500 has-[:checked]:bg-purple-500/5 rounded-lg p-4 cursor-pointer transition">
                <input type="radio" name="is_published" value="1" @checked($isPublished)
                       class="mt-0.5 w-4 h-4 accent-purple-600 shrink-0">
                <span class="flex flex-col gap-1">
                    <span class="text-sm font-semibold text-white">Publikasi</span>
                    <span class="text-xs text-gray-500">Project tampil di halaman utama dan bisa diakses publik.</span>
                </span>
            </label>

            <label class="flex items-start gap-3 bg-transparent border border-white/10 hover:border-purple-500/50 has-[:checked]:border-purple-500 has-[:checked]:bg-purple-500/5 rounded-lg p-4 cursor-pointer transition">
                <input type="radio" name="is_published" value="0" @checked(! $isPublished)
                       class="mt-0.5 w-4 h-4 accent-purple-600 shrink-0">
                <span class="flex flex-col gap-1">
                    <span class="text-sm font-semibold text-white">Private</span>
                    <span class="text-xs text-gray-500">Project disembunyikan dari publik, hanya terlihat di dashboard admin.</span>
                </span>
            </label>
        </div>

        @error('is_published') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-col gap-1.5">
        <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Deskripsi Project <span class="text-red-400">*</span></label>
        <textarea name="description" rows="4" placeholder="Ringkasan singkat tentang project ini..."
                  class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 resize-none @error('description') border-red-500 @enderror" required>{{ old('description', $project?->description) }}</textarea>
        @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-col gap-1.5">
        <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Gambar / Screenshot Aplikasi</label>

        @if($project?->image)
            <div class="flex items-center gap-4 mb-2">
                <img src="{{ $project->imageUrl() }}" alt="{{ $project->title }}" class="w-32 h-20 object-cover rounded-lg border border-white/10">
                <p class="text-gray-500 text-xs">Gambar saat ini. Upload gambar baru untuk menggantinya.</p>
            </div>
        @endif

        <input type="file" name="image" accept="image/*"
               class="text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-600 file:text-white hover:file:bg-purple-700 file:transition @error('image') file:bg-red-600 @enderror">
        <p class="text-xs text-gray-500">Format JPG, PNG, atau WEBP. Maksimal 5 MB.</p>
        @error('image') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Permasalahan & Tujuan --}}
<div class="flex flex-col gap-5">
    <p class="text-xs uppercase tracking-widest text-purple-400 font-bold border-b border-white/10 pb-2">Permasalahan & Tujuan</p>

    <div class="flex flex-col gap-1.5">
        <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Permasalahan yang Ingin Diselesaikan</label>
        <textarea name="problem" rows="4" placeholder="Masalah apa yang melatarbelakangi pembuatan project ini?"
                  class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 resize-none @error('problem') border-red-500 @enderror">{{ old('problem', $project?->problem) }}</textarea>
        @error('problem') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-col gap-1.5">
        <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Tujuan Dibuatnya Project</label>
        <textarea name="goal" rows="4" placeholder="Apa tujuan dan hasil yang ingin dicapai dari project ini?"
                  class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 resize-none @error('goal') border-red-500 @enderror">{{ old('goal', $project?->goal) }}</textarea>
        @error('goal') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Teknologi & Fitur --}}
<div class="flex flex-col gap-5">
    <p class="text-xs uppercase tracking-widest text-purple-400 font-bold border-b border-white/10 pb-2">Teknologi & Fitur</p>

    <div class="flex flex-col gap-1.5">
        <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Teknologi yang Digunakan</label>
        <input type="text" name="technologies" placeholder="HTML, CSS, Bootstrap, PHP, MySQL" value="{{ old('technologies', $project?->technologies) }}"
               class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 @error('technologies') border-red-500 @enderror">
        <p class="text-xs text-gray-500">Pisahkan dengan koma. Contoh: HTML, CSS, Bootstrap, PHP.</p>
        @error('technologies') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-col gap-1.5">
        <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Fitur-Fitur Project</label>
        <textarea name="features" rows="5" placeholder="Satu fitur per baris, contoh:&#10;Login dan registrasi pengguna&#10;Dashboard admin&#10;Pencarian dan filter data"
                  class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 resize-none @error('features') border-red-500 @enderror">{{ old('features', $project?->features) }}</textarea>
        <p class="text-xs text-gray-500">Tulis satu fitur per baris agar tampil sebagai daftar.</p>
        @error('features') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Dokumentasi & Detail --}}
<div class="flex flex-col gap-5">
    <p class="text-xs uppercase tracking-widest text-purple-400 font-bold border-b border-white/10 pb-2">Dokumentasi & Detail</p>

    <div class="flex flex-col gap-1.5">
        <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Dokumentasi Proses Pekerjaan</label>
        <textarea name="documentation" rows="6" placeholder="Satu langkah per baris, contoh:&#10;Analisis kebutuhan pengguna&#10;Membuat wireframe dan desain UI&#10;Implementasi front-end&#10;Pengujian dan perbaikan"
                  class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 resize-none @error('documentation') border-red-500 @enderror">{{ old('documentation', $project?->documentation) }}</textarea>
        <p class="text-xs text-gray-500">Tulis tahapan pengerjaan satu langkah per baris.</p>
        @error('documentation') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-col gap-1.5">
        <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Detail Project</label>
        <textarea name="detail" rows="7" placeholder="Ceritakan proses, tantangan, solusi, dan hasil akhir project secara lebih lengkap..."
                  class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 resize-none @error('detail') border-red-500 @enderror">{{ old('detail', $project?->detail) }}</textarea>
        @error('detail') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Link Proyek --}}
<div class="flex flex-col gap-5">
    <p class="text-xs uppercase tracking-widest text-purple-400 font-bold border-b border-white/10 pb-2">Link Project</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="flex flex-col gap-1.5">
            <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Link Demo Project</label>
            <input type="url" name="demo_url" placeholder="https://demo-project.com" value="{{ old('demo_url', $project?->demo_url) }}"
                   class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 @error('demo_url') border-red-500 @enderror">
            @error('demo_url') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Link Source Code GitHub</label>
            <input type="url" name="source_url" placeholder="https://github.com/username/repository" value="{{ old('source_url', $project?->source_url) }}"
                   class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 @error('source_url') border-red-500 @enderror">
            @error('source_url') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

{{-- Feedback User --}}
<div class="flex flex-col gap-5">
    <p class="text-xs uppercase tracking-widest text-purple-400 font-bold border-b border-white/10 pb-2">Feedback User</p>

    <div class="flex flex-col gap-1.5">
        <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Feedback dari Pengguna</label>
        <textarea name="feedback" rows="4" placeholder="Tulis testimoni atau masukan dari pengguna project..."
                  class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 resize-none @error('feedback') border-red-500 @enderror">{{ old('feedback', $project?->feedback) }}</textarea>
        @error('feedback') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-col gap-1.5">
        <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Nama Pemberi Feedback</label>
        <input type="text" name="feedback_author" placeholder="Misal: Guru Pembimbing / Pengguna Aplikasi" value="{{ old('feedback_author', $project?->feedback_author) }}"
               class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 @error('feedback_author') border-red-500 @enderror">
        @error('feedback_author') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>
