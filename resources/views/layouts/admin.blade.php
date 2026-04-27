<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'TMJ') }} | @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background: #F8FAFC; color: #1E293B; }
        
        /* Industrial Status Chips */
        .status-chip {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px 4px 8px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            border-radius: 4px;
            border-left: 3px solid transparent;
            line-height: 1;
        }
        .badge-ready   { background: rgba(34, 197, 94, 0.1); color: #15803D; border-left-color: #22C55E; }
        .badge-standby { background: rgba(234, 179, 8, 0.1); color: #A16207; border-left-color: #EAB308; }
        .badge-down    { background: rgba(239, 68, 68, 0.1); color: #B91C1C; border-left-color: #EF4444; }
        
        .badge-hadir   { background: rgba(34, 197, 94, 0.1); color: #15803D; border-left-color: #22C55E; }
        .badge-absent  { background: rgba(239, 68, 68, 0.1); color: #B91C1C; border-left-color: #EF4444; }
        .badge-izin    { background: rgba(245, 158, 11, 0.1); color: #92400E; border-left-color: #F59E0B; }
        
        .badge-fit     { background: rgba(34, 197, 94, 0.1); color: #15803D; border-left-color: #22C55E; }
        .badge-unfit   { background: rgba(245, 158, 11, 0.1); color: #92400E; border-left-color: #F59E0B; }

        /* Tonal Layering & Components */
        .nav-link { position: relative; padding: 6px 4px; font-size: 0.85rem; font-weight: 500; color: #64748B; transition: all 0.2s; }
        .nav-link:hover { color: #0F172A; }
        .nav-link.active { color: #2563EB; font-weight: 700; }
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -14px;
            left: 0; right: 0;
            height: 2px;
            background: #2563EB;
            border-radius: 2px;
        }

        .card-industrial {
            background: #FFFFFF;
            border-radius: 10px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card-industrial:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .tabular-nums { font-variant-numeric: tabular-nums; }
        .font-black { font-weight: 900; }
        .tracking-tighter { letter-spacing: -0.05em; }
        .text-xxs { font-size: 0.65rem; }
        
        .avatar { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.7rem; flex-shrink: 0; }
        
        /* Table Headers */
        .table-header { background: #F8FAFC; border-bottom: 2px solid #E2E8F0; }
        .table-header th { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B; padding: 12px 16px; }
    </style>
</head>
<body class="min-h-screen">

    {{-- Top Navigation --}}
    <nav style="background: #fff; border-bottom: 1px solid #E2E8F0;" class="shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                {{-- Logo --}}
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-sm" style="background: linear-gradient(135deg, #1D4ED8, #2563EB);">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-slate-800" style="font-size: 0.95rem; letter-spacing: -0.01em;">TMJ <span class="text-blue-600">Admin</span></span>
                </div>

                {{-- Nav Links --}}
                <div class="flex items-center gap-6">
                    <a href="{{ route('operations.dashboard') }}"
                       class="nav-link {{ request()->routeIs('operations.dashboard') ? 'active' : '' }}">
                        Beranda
                    </a>
                    @if(in_array(Auth::user()->role, ['admin', 'supervisor', 'hrd']))
                    <a href="{{ route('workforce.attendance') }}"
                       class="nav-link {{ request()->routeIs('workforce.attendance') ? 'active' : '' }}">
                        Absensi
                    </a>
                    @endif
                    @if(in_array(Auth::user()->role, ['admin', 'supervisor', 'workshop']))
                    <a href="{{ route('fleet.management') }}"
                       class="nav-link {{ request()->routeIs('fleet.management') ? 'active' : '' }}">
                        Armada
                    </a>
                    @endif
                </div>

                {{-- User & Logout --}}
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <div class="avatar" style="background: #DBEAFE; color: #1D4ED8;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <span class="text-sm font-semibold text-slate-700 hidden sm:block">{{ Auth::user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-semibold text-slate-500 hover:text-red-600 transition border border-slate-200 rounded-lg px-3 py-1.5">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
