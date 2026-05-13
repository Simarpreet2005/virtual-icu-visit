<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | ICU Virtual Visit</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 min-h-screen p-4 sm:p-8 overflow-x-hidden">
    <div class="blob" style="top: -10%; left: -10%;"></div>
    <div class="blob" style="bottom: -10%; right: -10%; animation-delay: -5s;"></div>

    <div class="max-w-5xl mx-auto grid lg:grid-cols-2 gap-10 lg:gap-14 items-center min-h-[calc(100vh-4rem)]">
        <div class="hidden lg:flex flex-col justify-center order-2 lg:order-1">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight mb-6">ICU Virtual Visit</h2>
            <img src="{{ asset('images/icu-hero.png') }}" alt="" width="640" height="480" class="w-full max-w-lg rounded-2xl shadow-2xl object-cover aspect-[4/3] border border-slate-200/80" loading="lazy" decoding="async">
        </div>

        <div class="w-full max-w-md mx-auto lg:max-w-none order-1 lg:order-2">
        <div class="text-center mb-8 lg:text-left">
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Sign in</h1>
        </div>

        <div class="glass-card p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full input-glass" placeholder="example@gmail.com" autocomplete="username">
                    @error('email') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="text-sm font-bold text-slate-700">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-600 hover:underline">Forgot?</a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required class="w-full input-glass" placeholder="••••••••" autocomplete="current-password">
                    @error('password') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <label for="remember_me" class="ml-2 text-sm text-slate-600">Remember this device</label>
                </div>

                <button type="submit" class="w-full btn-primary py-3">Sign in</button>

                <div class="text-center pt-4">
                    <p class="text-sm text-slate-500">No account? <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:underline">Register</a></p>
                </div>
            </form>
        </div>
        </div>
    </div>
</body>
</html>
