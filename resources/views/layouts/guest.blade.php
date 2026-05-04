<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TMJ') }} | Otentikasi</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; background: #0F172A; color: #1E293B; }
        
        .guest-container { 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 1.5rem;
            background-image: radial-gradient(circle at top left, rgba(30, 58, 138, 0.2), transparent 40%);
        }

        .guest-card {
            width: 100%; 
            max-width: 480px; 
            background: #FFFFFF; 
            border-radius: 24px; 
            padding: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }

        /* Mobile Adjustments */
        @media (max-width: 640px) {
            .guest-container { padding: 1rem; }
            .guest-card { padding: 2.5rem 1.5rem; border-radius: 20px; }
            input { font-size: 16px !important; }
        }

        /* Shared Component Styles */
        .industrial-input {
            width: 100%;
            border: 1px solid #E2E8F0;
            background: #F8FAFC;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-weight: 700;
            font-size: 0.875rem;
            transition: all 0.2s;
            color: #0F172A;
        }
        .industrial-input:focus {
            outline: none;
            border-color: #2563EB;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            background: #FFFFFF;
        }
    </style>
</head>
<body class="antialiased">
    <div class="guest-container">
        <div class="guest-card">
            {{-- Logo Header --}}
            <div class="flex items-center justify-center gap-3 mb-8">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="font-black text-slate-800 tracking-tight text-xl uppercase">TMJ <span class="text-blue-600">Secure</span></span>
            </div>

            <div class="relative z-10">
                {{ $slot }}
            </div>

            <div class="mt-10 pt-6 border-t border-slate-100 text-center relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">&copy; {{ date('Y') }} PT. TRI MACHMUD JAYA</p>
            </div>
        </div>
    </div>
</body>
</html>
