<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | ICU Virtual Visit</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 min-h-screen p-4 sm:p-8 overflow-x-hidden">
    <div class="blob" style="top: -10%; left: -10%;"></div>
    <div class="blob" style="bottom: -10%; right: -10%; animation-delay: -5s;"></div>

    <div class="max-w-5xl mx-auto grid lg:grid-cols-2 gap-10 lg:gap-14 items-start lg:items-center min-h-[calc(100vh-4rem)]">
        <div class="hidden lg:flex flex-col justify-center order-2 lg:order-1">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight mb-6">ICU Virtual Visit</h2>
            <img src="{{ asset('images/icu-hero.png') }}" alt="" width="640" height="480" class="w-full max-w-lg rounded-2xl shadow-2xl object-cover aspect-[4/3] border border-slate-200/80" loading="lazy" decoding="async">
        </div>

        <div class="w-full max-w-lg mx-auto lg:max-w-none order-1 lg:order-2">
        <div class="text-center mb-8 lg:text-left">
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Create account</h1>
        </div>

        <div class="glass-card p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-1">Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full input-glass" placeholder="example" autocomplete="name">
                        @error('name') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-1">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full input-glass" placeholder="example@gmail.com" autocomplete="email">
                        @error('email') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-bold text-slate-700 mb-1">Phone</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required class="w-full input-glass" placeholder="+1 555 0100" autocomplete="tel">
                        @error('phone') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="role" class="block text-sm font-bold text-slate-700 mb-1">Role</label>
                        <select id="role" name="role" required class="w-full input-glass">
                            <option value="family">Family</option>
                            <option value="doctor">Doctor</option>
                            <option value="nurse">Nurse</option>
                        </select>
                        @error('role') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-1">Password</label>
                        <input id="password" type="password" name="password" required class="w-full input-glass" placeholder="••••••••" autocomplete="new-password">
                        @error('password') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-1">Confirm password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full input-glass" placeholder="••••••••" autocomplete="new-password">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full btn-primary py-3">Register</button>
                </div>

                <div class="text-center pt-4 border-t border-slate-100 mt-4">
                    <p class="text-sm text-slate-500">Already registered? <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:underline">Sign in</a></p>
                </div>
            </form>
        </div>
        </div>
    </div>
</body>
</html>
