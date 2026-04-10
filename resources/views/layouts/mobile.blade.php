<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <title>{{ config('app.name', 'TMJ') }} | @yield('title')</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            * { -webkit-tap-highlight-color: transparent; }
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background: #EEF2F7;
                min-height: 100vh;
            }

            /* ─── Form Input Fields ─────────────────────────────────── */
            .form-input-field {
                width: 100%;
                background: #FFFFFF;
                border: 2px solid #E8EDF3;
                color: #1E293B;
                font-family: 'Plus Jakarta Sans', sans-serif;
                font-size: 0.9375rem;
                font-weight: 600;
                border-radius: 14px;
                padding: 15px 14px;
                min-height: 56px;
                line-height: 1.4;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
                outline: none;
                display: block;
                box-sizing: border-box;
                box-shadow: 0 1px 3px rgba(15,23,42,0.04);
            }
            input.form-input-field {
                height: 56px;
                padding-top: 0;
                padding-bottom: 0;
                padding-left: 14px;
                padding-right: 14px;
                line-height: 56px;
            }
            /* Icon-padded inputs: Tailwind pl-9 and pl-11 overrides */
            input.form-input-field[class*="pl-9"],
            select.form-input-field[class*="pl-9"] { padding-left: 2.25rem !important; }
            input.form-input-field[class*="pl-11"],
            select.form-input-field[class*="pl-11"] { padding-left: 2.75rem !important; }
            select.form-input-field {
                height: 56px;
                padding-top: 0;
                padding-bottom: 0;
                line-height: 56px;
                appearance: none;
                -webkit-appearance: none;
                cursor: pointer;
            }
            textarea.form-input-field {
                height: auto;
                min-height: 100px;
                line-height: 1.6;
                padding: 14px;
                resize: none;
            }
            .form-input-field:focus {
                border-color: #3B82F6;
                box-shadow: 0 0 0 4px rgba(59,130,246,0.12), 0 1px 3px rgba(15,23,42,0.04);
                background: #FFFFFF;
            }
            .form-input-field.border-red-400 {
                border-color: #F87171 !important;
                box-shadow: 0 0 0 4px rgba(248,113,113,0.1) !important;
            }

            /* ─── Section Labels ────────────────────────────────────── */
            .section-label {
                font-size: 0.7rem;
                font-weight: 800;
                letter-spacing: 0.12em;
                text-transform: uppercase;
                color: #94A3B8;
                margin-bottom: 14px;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .section-label::before {
                content: '';
                display: inline-block;
                width: 4px;
                height: 16px;
                border-radius: 4px;
                background: linear-gradient(180deg, #3B82F6, #2563EB);
                flex-shrink: 0;
            }
            .section-label.green::before {
                background: linear-gradient(180deg, #22C55E, #16A34A);
            }

            /* ─── Form Field Label ──────────────────────────────────── */
            .field-label {
                display: block;
                font-size: 0.72rem;
                font-weight: 700;
                color: #64748B;
                text-transform: uppercase;
                letter-spacing: 0.09em;
                margin-bottom: 7px;
            }

            /* ─── Section Cards ─────────────────────────────────────── */
            .section-card {
                background: #FFFFFF;
                border-radius: 20px;
                padding: 20px;
                margin-bottom: 12px;
                box-shadow: 0 1px 4px rgba(15,23,42,0.05), 0 4px 16px rgba(15,23,42,0.04);
                border: 1px solid rgba(226,232,240,0.8);
            }

            /* ─── Metric Cards (Health) ─────────────────────────────── */
            .metric-card {
                background: #FFFFFF;
                border: 2px solid #E8EDF3;
                border-radius: 16px;
                padding: 12px 10px;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 6px;
                transition: border-color 0.2s;
            }
            .metric-card input {
                width: 100%;
                border: none;
                outline: none;
                background: transparent;
                text-align: center;
                font-size: 1.1rem;
                font-weight: 800;
                color: #1E293B;
                font-family: 'Plus Jakarta Sans', sans-serif;
                padding: 0;
                height: auto;
                line-height: 1;
            }

            /* ─── Toggle Cards (Fit/Status) ─────────────────────────── */
            .toggle-card {
                border: 2px solid #E2E8F0;
                border-radius: 16px;
                background: #F8FAFC;
                color: #94A3B8;
                cursor: pointer;
                transition: all 0.18s ease;
                position: relative;
                overflow: hidden;
            }
            .toggle-card:active { transform: scale(0.97); }

            /* ─── Presence / Status Grid Buttons ────────────────────── */
            .presence-btn, .status-btn {
                border: 2px solid #E2E8F0;
                border-radius: 14px;
                background: #F8FAFC;
                color: #94A3B8;
                cursor: pointer;
                transition: all 0.18s ease;
                padding: 10px 6px;
            }
            .presence-btn:active, .status-btn:active {
                transform: scale(0.96);
            }

            /* ─── Alerts ────────────────────────────────────────────── */
            .alert-box {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                padding: 14px 16px;
                border-radius: 14px;
                border-left: 4px solid;
                margin-bottom: 16px;
                font-size: 0.875rem;
                font-weight: 600;
            }
            .alert-success { background: #F0FDF4; border-color: #22C55E; color: #15803D; }
            .alert-error   { background: #FFF1F2; border-color: #F43F5E; color: #BE123C; }
            .alert-warning { background: #FFFBEB; border-color: #F59E0B; color: #B45309; }

            /* ─── Sticky Submit ─────────────────────────────────────── */
            .sticky-submit-bar {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 50;
                padding: 12px 16px max(12px, env(safe-area-inset-bottom));
                background: rgba(238,242,247,0.9);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border-top: 1px solid rgba(226,232,240,0.8);
            }
            .submit-btn {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                color: white;
                font-family: 'Plus Jakarta Sans', sans-serif;
                font-size: 1rem;
                font-weight: 800;
                border-radius: 16px;
                padding: 17px 24px;
                border: none;
                cursor: pointer;
                transition: all 0.2s ease;
                box-shadow: 0 8px 24px rgba(37,99,235,0.35);
                letter-spacing: 0.01em;
            }
            .submit-btn:active { transform: scale(0.98); }
            .submit-btn:disabled { opacity: 0.65; cursor: not-allowed; }
            .submit-btn.green { background: linear-gradient(135deg, #16A34A 0%, #059669 100%); box-shadow: 0 8px 24px rgba(22,163,74,0.35); }
            .submit-btn.blue  { background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); box-shadow: 0 8px 24px rgba(37,99,235,0.35); }

            /* ─── Animations ────────────────────────────────────────── */
            @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.6;transform:scale(1.3)} }
            .pulse-dot { animation: pulse-dot 1.5s ease-in-out infinite; }
            @keyframes slideDown {
                from { opacity: 0; transform: translateY(-10px); max-height: 0; }
                to   { opacity: 1; transform: translateY(0);   max-height: 300px; }
            }
            @keyframes slideUp {
                from { opacity: 1; transform: translateY(0);   max-height: 300px; }
                to   { opacity: 0; transform: translateY(-10px); max-height: 0; }
            }
            .slide-in  { animation: slideDown 0.28s ease forwards; overflow: hidden; }
            .slide-out { animation: slideUp   0.22s ease forwards; overflow: hidden; }
            /* ─── Dropdown Scrollbar ─────────────────────────────────── */
            .custom-scrollbar::-webkit-scrollbar {
                width: 5px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: #F8FAFC;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #E2E8F0;
                border-radius: 10px;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #CBD5E1;
            }
        </style>
    </head>
    <body>
        <div class="min-h-screen flex flex-col items-center justify-start" style="padding-bottom: 110px;">

            {{-- Colored Header Card (yielded per page) --}}
            @yield('header')

            {{-- Main Form Card --}}
            <div class="w-full max-w-md px-4 mt-4">

                {{-- Alerts --}}
                @if(session('success'))
                    <div class="alert-box alert-success" role="alert">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert-box alert-error" role="alert">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert-box alert-warning">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                        <span>Harap perbaiki <strong>{{ $errors->count() }}</strong> kesalahan di bawah ini.</span>
                    </div>
                @endif

                @yield('content')
            </div>

            <p class="mt-6 mb-4 text-xs text-slate-400 font-medium text-center">© {{ date('Y') }} PT. TRI MACHMUD JAYA — Sistem Manajemen Operasional</p>
        </div>
    </body>
</html>
