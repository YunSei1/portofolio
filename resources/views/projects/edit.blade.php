<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project — Admin</title>
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
        <a href="{{ route('admin.projects.index') }}" class="text-gray-400 hover:text-white text-sm transition mb-6 inline-block">&larr; Kembali ke Kelola Proyek</a>

        <div class="bg-[#16161a] border border-white/10 rounded-2xl p-6 md:p-8">
            <h2 class="text-2xl font-bold mb-1 bg-gradient-to-b from-white to-gray-500 bg-clip-text text-transparent">Edit Project</h2>
            <p class="text-gray-400 text-sm mb-8">Perbarui detail project <span class="text-gray-200">{{ $project->title }}</span>.</p>

            <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-8">
                @csrf
                @method('PUT')

                @include('projects._form')

                <div class="flex flex-wrap items-center gap-3 pt-2 border-t border-white/10">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition shadow-[0_0_15px_rgba(147,51,234,0.3)]">Perbarui Project</button>
                    <a href="{{ route('admin.projects.index') }}" class="text-gray-400 hover:text-white text-sm transition">Batal</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
