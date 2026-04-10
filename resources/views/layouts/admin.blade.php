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
        body { font-family: 'Inter', sans-serif; background: #F1F5F9; }
        .nav-link { position: relative; padding: 4px 2px; font-size: 0.875rem; font-weight: 500; color: #64748B; transition: color 0.2s; }
        .nav-link:hover { color: #1E293B; }
        .nav-link.active { color: #2563EB; font-weight: 600; }
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0; right: 0;
            height: 2px;
            background: #2563EB;
            border-radius: 2px;
        }
        .stat-card { background: #fff; border-radius: 14px; border: 1px solid #E2E8F0; padding: 20px 24px; }
        .badge-ready   { background: #DCFCE7; color: #15803D; font-size: 0.7rem; font-weight: 700; padding: 2px 10px; border-radius: 20px; text-transform: uppercase; }
        .badge-standby { background: #FEF9C3; color: #A16207; font-size: 0.7rem; font-weight: 700; padding: 2px 10px; border-radius: 20px; text-transform: uppercase; }
        .badge-down    { background: #FEE2E2; color: #B91C1C; font-size: 0.7rem; font-weight: 700; padding: 2px 10px; border-radius: 20px; text-transform: uppercase; }
        .badge-hadir   { background: #DCFCE7; color: #15803D; font-size: 0.7rem; font-weight: 700; padding: 2px 10px; border-radius: 20px; text-transform: uppercase; }
        .badge-absent  { background: #FEE2E2; color: #B91C1C; font-size: 0.7rem; font-weight: 700; padding: 2px 10px; border-radius: 20px; text-transform: uppercase; }
        .badge-izin    { background: #FEF3C7; color: #92400E; font-size: 0.7rem; font-weight: 700; padding: 2px 10px; border-radius: 20px; text-transform: uppercase; }
        .badge-fit     { background: #DCFCE7; color: #15803D; font-size: 0.7rem; font-weight: 700; padding: 2px 10px; border-radius: 20px; }
        .badge-unfit   { background: #FEE2E2; color: #B91C1C; font-size: 0.7rem; font-weight: 700; padding: 2px 10px; border-radius: 20px; }
        .avatar { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; flex-shrink: 0; }
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
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                    @if(in_array(Auth::user()->role, ['admin', 'supervisor', 'hrd']))
                    <a href="{{ route('dashboard.attendance') }}"
                       class="nav-link {{ request()->routeIs('dashboard.attendance') ? 'active' : '' }}">
                        Attendance
                    </a>
                    @endif
                    @if(in_array(Auth::user()->role, ['admin', 'supervisor', 'workshop']))
                    <a href="{{ route('dashboard.fleet') }}"
                       class="nav-link {{ request()->routeIs('dashboard.fleet') ? 'active' : '' }}">
                        Fleet
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
                            Logout
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

</body>
</html>
