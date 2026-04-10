<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>TMJ | Report Submitted</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #EEF2F7;
            min-height: 100vh;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.85);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes fadeUp {
            from {
                transform: translateY(24px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes ripple {
            0% {
                transform: scale(1);
                opacity: 0.4;
            }

            100% {
                transform: scale(2.4);
                opacity: 0;
            }
        }

        .card-animate {
            animation: scaleIn 0.45s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .content-animate {
            animation: fadeUp 0.4s ease 0.2s forwards;
            opacity: 0;
        }

        .ripple-ring {
            position: absolute;
            inset: 0;
            border-radius: 9999px;
            background: rgba(37, 99, 235, 0.12);
            animation: ripple 1.8s ease-out infinite;
        }

        .ripple-ring:nth-child(2) {
            animation-delay: 0.6s;
        }

        .ripple-ring:nth-child(3) {
            animation-delay: 1.2s;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center p-4">

    {{-- Dot pattern background --}}
    <div class="fixed inset-0 pointer-events-none"
        style="background-image: radial-gradient(#CBD5E1 1px, transparent 1px); background-size: 28px 28px; opacity: 0.4;">
    </div>

    <div class="relative w-full max-w-sm">
        {{-- Success Card --}}
        <div class="bg-white rounded-3xl overflow-hidden card-animate"
            style="box-shadow: 0 8px 40px rgba(15,23,42,0.12);">

            {{-- Top gradient bar --}}
            <div class="h-1.5" style="background: linear-gradient(90deg, #1D4ED8, #3B82F6, #60A5FA);"></div>

            <div class="px-8 pt-8 pb-8 text-center content-animate">

                {{-- Animated icon with ripple rings --}}
                <div class="relative w-24 h-24 mx-auto mb-6 flex items-center justify-center">
                    <div class="ripple-ring"></div>
                    <div class="ripple-ring"></div>
                    <div class="ripple-ring"></div>
                    <div class="relative w-20 h-20 rounded-3xl flex items-center justify-center"
                        style="background: linear-gradient(135deg, #1D4ED8, #3B82F6); box-shadow: 0 8px 20px rgba(37,99,235,0.4);">
                        <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>

                {{-- Title --}}
                <h1 class="text-2xl font-black text-slate-800 mb-2 tracking-tight">Berhasil Tersimpan!</h1>
                <p class="text-slate-500 font-medium mb-6" style="font-size:0.9rem;">Data unit Anda telah tercatat dan
                    tersinkronisasi secara real-time ke dashboard operasional.</p>

                {{-- Details Card --}}
                <div class="rounded-2xl p-4 mb-6 text-left" style="background: #F8FAFC; border: 1.5px solid #E8EDF3;">
                    <div class="flex justify-between items-center py-2.5 border-b" style="border-color: #E8EDF3;">
                        <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Submission ID</span>
                        <span
                            class="text-xs font-black font-mono text-slate-700">{{ session('submission_id', '#—') }}</span>
                    </div>
                    @if(session('unit_number'))
                    <div class="flex justify-between items-center py-2.5 border-b" style="border-color: #E8EDF3;">
                        <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Unit</span>
                        <span class="text-xs font-bold text-slate-700">{{ session('unit_number', '—') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center py-2.5">
                        <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Timestamp</span>
                        <span
                            class="text-xs font-bold text-slate-700">{{ session('submission_time', now()->format('d M Y, H:i')) }}</span>
                    </div>
                </div>

                {{-- Status badge --}}
                <div class="inline-flex items-center gap-2 rounded-full px-4 py-2 mb-6"
                    style="background: #EFF6FF; border: 1.5px solid #BFDBFE;">
                    <span class="w-2 h-2 rounded-full" style="background: #3B82F6;"></span>
                    <span class="text-xs font-bold" style="color: #1D4ED8;">UNIT {{ session('status_label', 'REPORT') }} — Logged</span>
                </div>

                {{-- Actions --}}
                <a href="{{ route('unit.status.create') }}"
                    class="block w-full text-center text-white font-bold py-4 rounded-2xl text-sm transition-all active:scale-[0.98] mb-3"
                    style="background: linear-gradient(135deg, #1D4ED8, #2563EB); box-shadow: 0 6px 18px rgba(37,99,235,0.35);">
                    ＋ Submit Laporan Baru
                </a>

            </div>
        </div>

        <p class="text-center text-xs text-slate-400 mt-5 font-medium">© {{ date('Y') }} PT. TRI MACHMUD JAYA — Sistem
            Manajemen Operasional</p>
    </div>

</body>

</html>