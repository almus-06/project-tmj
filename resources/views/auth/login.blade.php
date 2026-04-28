<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Terintegrasi - PT. TRI MACHMUD JAYA</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vite Custom Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #0F172A; /* Slate 900 */
        }

        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-image: radial-gradient(circle at top left, rgba(30, 58, 138, 0.2), transparent 40%),
                              radial-gradient(circle at bottom right, rgba(30, 58, 138, 0.1), transparent 40%);
        }

        .login-wrapper {
            width: 100%;
            max-width: 1050px;
            display: flex;
            background: #FFFFFF;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .banner-side {
            width: 55%;
            position: relative;
            background-image: url('/industrial_mining_fleet_login_bg_1777353092157.png');
            background-size: cover;
            background-position: center;
            display: none;
        }

        @media (min-width: 768px) {
            .banner-side { display: block; }
        }

        .banner-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom right, rgba(15, 23, 42, 0.8), rgba(30, 58, 138, 0.4));
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .login-side {
            width: 100%;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        @media (min-width: 768px) {
            .login-side { width: 45%; }
        }

        .input-field {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-field:focus {
            border-color: #2563EB;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            background: #FFFFFF;
        }

        .btn-industrial {
            background: #0F172A;
            color: #FFFFFF;
            transition: all 0.3s ease;
        }

        .btn-industrial:hover {
            background: #1E293B;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.3);
        }

        .btn-industrial:active {
            transform: translateY(0);
        }
    </style>
</head>

<body class="antialiased">

    <div class="main-container">
        <div class="login-wrapper">
            
            {{-- Banner Side --}}
            <div class="banner-side">
                <div class="banner-overlay">
                    <div class="mb-8">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mb-6 shadow-xl">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h1 class="text-4xl font-black text-white leading-tight tracking-tight uppercase">
                            Operational<br>Control Center
                        </h1>
                        <p class="text-blue-200 mt-4 font-medium max-w-xs">
                            Manajemen armada, logistik, dan dinamika tenaga kerja PT. Tri Machmud Jaya.
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-4 text-[10px] font-black text-blue-300 uppercase tracking-[0.3em]">
                        <span class="w-12 h-px bg-blue-500/50"></span>
                        INDUSTRIAL SECURITY STANDARD
                    </div>
                </div>
            </div>

            {{-- Login Form Side --}}
            <div class="login-side">
                <div class="mb-10">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Otentikasi User</h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Silakan masuk untuk akses kontrol penuh.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-6 text-sm font-bold text-green-600 bg-green-50 p-4 rounded-xl border border-green-100" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-5">
                        <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Identitas Digital (Email)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                </svg>
                            </span>
                            <input id="email" class="input-field block w-full rounded-xl border border-slate-200 bg-slate-50/50 pl-11 pr-4 py-3.5 text-slate-900 font-bold focus:outline-none transition-all text-sm" 
                                   type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="username@tmj.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-[10px] text-red-500 font-black uppercase tracking-tight" />
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Kunci Keamanan</label>
                            @if (Route::has('password.request'))
                                <a class="text-[10px] font-black text-blue-600 hover:text-blue-800 transition uppercase tracking-widest" href="{{ route('password.request') }}">Lupa Sandi?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input id="password" class="input-field block w-full rounded-xl border border-slate-200 bg-slate-50/50 pl-11 pr-4 py-3.5 text-slate-900 font-bold focus:outline-none transition-all text-sm" 
                                   type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-[10px] text-red-500 font-black uppercase tracking-tight" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center mb-8 group cursor-pointer">
                        <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 bg-slate-50 cursor-pointer" name="remember">
                        <label for="remember_me" class="ml-2 text-xs font-bold text-slate-600 cursor-pointer select-none">Biarkan tetap masuk</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full btn-industrial font-black text-xs uppercase tracking-[0.2em] px-6 py-4 rounded-xl flex justify-center items-center gap-3">
                        Otorisasi Akses
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </button>
                </form>

                <div class="mt-12 pt-8 border-t border-slate-100 flex justify-between items-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">&copy; {{ date('Y') }} PT. TMJ</p>
                    <div class="flex gap-4">
                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Server Secure</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>