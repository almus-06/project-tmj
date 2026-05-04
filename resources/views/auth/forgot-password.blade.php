<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemulihan Akses - PT. TRI MACHMUD JAYA</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vite Custom Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #0F172A;
        }

        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-image: radial-gradient(circle at top left, rgba(30, 58, 138, 0.2), transparent 40%);
        }

        .recovery-card {
            width: 100%;
            max-width: 450px;
            background: #FFFFFF;
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
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

        /* Mobile Adjustments */
        @media (max-width: 640px) {
            .main-container { padding: 1rem; }
            .recovery-card { padding: 2.5rem 1.5rem; border-radius: 20px; }
            .input-field { font-size: 16px !important; }
        }
    </style>
</head>

<body class="antialiased">

    <div class="main-container">
        <div class="recovery-card">
            
            <div class="mb-8">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mb-6 shadow-xl">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Pemulihan Kata Sandi</h2>
                <p class="text-slate-500 text-sm font-medium mt-3 leading-relaxed">
                    Lupa kunci akses? Masukkan alamat email terdaftar Anda, dan kami akan mengirimkan link otorisasi untuk membuat kunci baru.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6 text-sm font-bold text-blue-600 bg-blue-50 p-4 rounded-xl border border-blue-100" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-8">
                    <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Email Terdaftar</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </span>
                        <input id="email" class="input-field block w-full rounded-xl border border-slate-200 bg-slate-50/50 pl-11 pr-4 py-3.5 text-slate-900 font-bold focus:outline-none transition-all text-sm" 
                               type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@tmj.com" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-[10px] text-red-500 font-black uppercase tracking-tight" />
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full btn-industrial font-black text-xs uppercase tracking-[0.2em] px-6 py-4 rounded-xl flex justify-center items-center gap-3">
                        Kirim Link Otorisasi
                    </button>
                    <a href="{{ route('login') }}" class="w-full text-center text-[10px] font-black text-slate-400 hover:text-slate-600 transition uppercase tracking-widest py-2">
                        Kembali ke Login
                    </a>
                </div>
            </form>

            <div class="mt-12 pt-8 border-t border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">&copy; {{ date('Y') }} PT. TRI MACHMUD JAYA</p>
            </div>
        </div>
    </div>

</body>
</html>
