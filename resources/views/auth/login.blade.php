<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - PT. TRI MACHMUD JAYA</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />

    <!-- Vite Custom Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #F8FAFC;
        }

        .main-container {
            min-height: 100vh;
            display: flex;
            background: linear-gradient(135deg, rgba(29, 78, 216, 0.05) 0%, rgba(29, 78, 216, 0) 100%);
        }

        .login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
        }

        .banner-gradient {
            background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
        }

        .input-field {
            transition: all 0.2s ease;
        }

        .input-field:focus {
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }
    </style>
</head>

<body class="antialiased text-slate-800">

    <div class="main-container relative overflow-hidden">
        {{-- Background Decorations --}}
        <div
            class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-[100px] opacity-30 animate-pulse">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-5%] w-[30rem] h-[30rem] bg-indigo-500 rounded-full mix-blend-multiply filter blur-[120px] opacity-20 animation-delay-2000">
        </div>

        <div
            class="m-auto w-full max-w-[1200px] flex flex-col md:flex-row items-center justify-center p-6 gap-0 lg:gap-16 z-10 relative">

            {{-- Left Banner (Company Info) --}}
            <div
                class="hidden md:flex flex-col justify-center w-full md:w-1/2 p-8 lg:p-12 banner-gradient rounded-3xl text-white shadow-2xl relative overflow-hidden h-[600px]">
                <div class="absolute inset-0 bg-black opacity-10"></div>
                <div
                    class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10">
                </div>

                <div class="relative z-10">
                    <div
                        class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-8 border border-white/30 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>

                    <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight mb-4 leading-tight">
                        Logistics &<br>Fleet Control
                    </h1>
                    <p class="text-lg text-blue-100 font-medium mb-10 max-w-sm leading-relaxed">
                        Sistem manajemen absensi dan pemantauan status unit logistik secara real-time.
                    </p>

                    <div class="flex items-center gap-4 text-sm font-semibold text-blue-200 uppercase tracking-widest">
                        <span class="w-8 h-px bg-blue-300"></span>
                        PT. TRI MACHMUD JAYA
                    </div>
                </div>
            </div>

            {{-- Right Form (Login) --}}
            <div class="w-full md:w-1/2 max-w-md">
                <div class="login-card rounded-3xl p-8 sm:p-12 relative overflow-hidden">

                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Selamat Datang!</h2>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status
                        class="mb-5 text-sm font-bold text-green-600 bg-green-50 p-4 rounded-xl border border-green-200"
                        :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-5">
                            <label for="email"
                                class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Alamat
                                Email</label>
                            <input id="email"
                                class="input-field block w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-800 font-medium focus:border-blue-500 focus:bg-white focus:outline-none placeholder-slate-400 transition-all text-sm"
                                type="email" name="email" value="{{ old('email') }}" required autofocus
                                placeholder="nama@tmj.com" />
                            <x-input-error :messages="$errors->get('email')"
                                class="mt-2 text-xs text-red-500 font-semibold" />
                        </div>

                        <!-- Password -->
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <label for="password"
                                    class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Kata
                                    Sandi</label>
                                @if (Route::has('password.request'))
                                    <a class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition"
                                        href="{{ route('password.request') }}">
                                        Lupa Sandi?
                                    </a>
                                @endif
                            </div>
                            <input id="password"
                                class="input-field block w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-800 font-medium focus:border-blue-500 focus:bg-white focus:outline-none placeholder-slate-400 transition-all text-sm"
                                type="password" name="password" required autocomplete="current-password"
                                placeholder="••••••••" />
                            <x-input-error :messages="$errors->get('password')"
                                class="mt-2 text-xs text-red-500 font-semibold" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center mb-8">
                            <input id="remember_me" type="checkbox"
                                class="w-4 h-4 rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 bg-slate-50 cursor-pointer"
                                name="remember">
                            <label for="remember_me"
                                class="ml-2 text-sm font-medium text-slate-600 cursor-pointer">Ingat saya di perangkat
                                ini</label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm px-6 py-4 rounded-xl transition-all shadow-lg shadow-blue-600/30 active:scale-[0.98] flex justify-center items-center gap-2 group">
                            Masuk Dashboard
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </form>

                </div>

                {{-- Footer Info --}}
                <div class="text-center mt-8">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-widest">&copy; {{ date('Y') }} PT.
                        TRI MACHMUD JAYA</p>
                </div>
            </div>

        </div>
    </div>

</body>

</html>