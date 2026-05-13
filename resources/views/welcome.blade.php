<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 min-h-screen flex flex-col relative">
    <!-- Animated background blobs to match the rest of the application theme -->
    <div class="blob" style="top: -15%; left: -10%;"></div>
    <div class="blob" style="bottom: -20%; right: -15%; animation-delay: -5s;"></div>

    <header class="relative z-10 bg-white/80 backdrop-blur-md shadow-sm border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between gap-4">
            <span class="text-xl font-bold tracking-tight text-blue-900 flex items-center gap-2">
                {{ config('app.name', 'ICU Virtual Visit') }}
            </span>
            <nav class="flex items-center gap-3 text-sm font-medium">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-slate-700 hover:text-blue-600">Dashboard</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="relative z-10 flex-grow flex items-center justify-center p-4 sm:p-6">
        <div class="max-w-3xl w-full text-center bg-white/80 backdrop-blur-lg p-10 sm:p-16 rounded-3xl shadow-xl border border-white/50">
            <h1 class="text-4xl sm:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight mb-4">
                Virtual ICU Visits
            </h1>
            <p class="text-xl text-slate-600 leading-relaxed mb-10 max-w-2xl mx-auto font-medium">
                Secure, real-time video communication bridging the gap between critical care patients, their families, and medical professionals.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @guest
                    <a href="{{ route('login') }}" class="btn-primary py-4 px-10 text-lg w-full sm:w-auto shadow-lg hover:shadow-xl transition-all border-0">Sign In to Portal</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-slate-50 border-2 border-blue-100 py-4 px-10 text-lg rounded-xl font-bold w-full sm:w-auto transition-all shadow-sm hover:shadow-md">Create Account</a>
                    @endif
                @else
                    <a href="{{ route('dashboard') }}" class="btn-primary py-4 px-10 text-lg w-full sm:w-auto shadow-lg hover:shadow-xl transition-all border-0">Go to Dashboard</a>
                @endguest
            </div>
        </div>
    </main>
</body>
</html>
