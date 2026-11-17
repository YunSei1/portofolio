<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Portofolio</title>
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
<body class="bg-[#0b0b0f] text-white min-h-screen flex items-center justify-center px-4">

    <div class="glow"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold tracking-tighter bg-gradient-to-b from-white to-gray-500 bg-clip-text text-transparent">
                PORTOFOLIO.
            </h1>
            <p class="text-gray-400 text-sm mt-2">Sign in to your account</p>
        </div>

        <div class="bg-[#16161a] border border-white/10 rounded-2xl p-6 sm:p-8">
            <form action="{{ route('login') }}" method="POST" class="flex flex-col gap-5">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-3 text-sm text-red-400">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Username</label>
                    <input type="text" name="username" placeholder="Masukkan username" value="{{ old('username') }}"
                           class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 @error('username') border-red-500/50 @enderror" required>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs text-gray-400 font-medium uppercase tracking-widest ml-1">Password</label>
                    <input type="password" name="password" placeholder="••••••••"
                           class="bg-transparent border border-white/10 p-3.5 rounded-lg focus:border-purple-500 outline-none transition placeholder:text-gray-600 @error('password') border-red-500/50 @enderror" required>
                </div>

                <button type="submit"
                        class="bg-purple-600 hover:bg-purple-700 text-white p-3.5 rounded-lg font-semibold transition shadow-[0_0_20px_rgba(147,51,234,0.3)] hover:shadow-[0_0_30px_rgba(147,51,234,0.5)]">
                    Sign In
                </button>
            </form>
        </div>

        <p class="text-center text-gray-500 text-sm mt-6">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-purple-400 hover:text-purple-300 font-semibold transition">Register</a>
        </p>
    </div>

</body>
</html>
