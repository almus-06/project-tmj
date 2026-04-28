<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembaruan Kata Sandi - PT. TRI MACHMUD JAYA</title>

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

        .reset-card {
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
    </style>
</head>

<body class="antialiased">

    <div class="main-container">
        <div class="reset-card">
            
            <div class="mb-8">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mb-6 shadow-xl">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Atur Ulang Sandi</h2>
                <p class="text-slate-500 text-sm font-medium mt-3 leading-relaxed">
                    Link otorisasi valid. Silakan tentukan kata sandi baru yang kuat untuk akun Anda.
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="mb-5">
                    <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Konfirmasi Email</label>
                    <input id="email" class="input-field block w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-900 font-bold focus:outline-none transition-all text-sm" 
                           type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus readonly />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-[10px] text-red-500 font-black uppercase tracking-tight" />
                </div>

                <!-- Password -->
                <div class="mb-5">
                    <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kata Sandi Baru</label>
                    <input id="password" class="input-field block w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-900 font-bold focus:outline-none transition-all text-sm" 
                           type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-[10px] text-red-500 font-black uppercase tracking-tight" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-8">
                    <label for="password_confirmation" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Ulangi Kata Sandi Baru</label>
                    <input id="password_confirmation" class="input-field block w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-900 font-bold focus:outline-none transition-all text-sm" 
                           type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-[10px] text-red-500 font-black uppercase tracking-tight" />
                </div>

                <button type="submit" class="w-full btn-industrial font-black text-xs uppercase tracking-[0.2em] px-6 py-4 rounded-xl flex justify-center items-center gap-3">
                    Perbarui Kata Sandi
                </button>
            </form>

            <div class="mt-12 pt-8 border-t border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">&copy; {{ date('Y') }} PT. TRI MACHMUD JAYA</p>
            </div>
        </div>
    </div>

</body>
</html>
