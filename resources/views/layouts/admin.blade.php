<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'TMJ') }} | @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Libraries for Smooth Transitions --}}
    <script src="https://unpkg.com/swup@4/dist/Swup.umd.js"></script>
    <script src="https://unpkg.com/@swup/head-plugin@2"></script>
    <script src="https://unpkg.com/@swup/scripts-plugin@2"></script>
    <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #F5F3FF;
            color: #1E293B;
        }

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

        .badge-ready {
            background: #DCFCE7;
            color: #166534;
            border-color: #BBF7D0;
        }

        .badge-standby {
            background: #FEF3C7;
            color: #92400E;
            border-color: #FDE68A;
        }

        .badge-down {
            background: #FEE2E2;
            color: #991B1B;
            border-color: #FECACA;
        }

        .badge-hadir {
            background: #DCFCE7;
            color: #166534;
            border-color: #BBF7D0;
        }

        .badge-absent {
            background: #FEE2E2;
            color: #991B1B;
            border-left-color: #FECACA;
        }

        .badge-izin {
            background: #E0F2FE;
            color: #0284C7;
            border-color: #BAE6FD;
        }

        .badge-fit {
            background: #DCFCE7;
            color: #166534;
            border-color: #BBF7D0;
        }

        .badge-unfit {
            background: #FEF3C7;
            color: #92400E;
            border-color: #FDE68A;
        }

        /* Tonal Layering & Components */
        .nav-link {
            position: relative;
            padding: 6px 4px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #64748B;
            transition: all 0.2s;
        }

        .nav-link:hover {
            color: #0F172A;
        }

        .nav-link.active {
            color: #6366F1;
            font-weight: 700;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -14px;
            left: 0;
            right: 0;
            height: 2px;
            background: #6366F1;
            border-radius: 2px;
        }

        .card-industrial {
            background: #FFFFFF;
            border-radius: 10px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card-industrial:hover {
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
        }

        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }

        .font-black {
            font-weight: 900;
        }

        .tracking-tighter {
            letter-spacing: -0.05em;
        }

        .text-xxs {
            font-size: 0.65rem;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.7rem;
            flex-shrink: 0;
        }

        /* Table Headers */
        .table-header {
            background: #F8FAFC;
            border-bottom: 2px solid #E2E8F0;
        }

        .table-header th {
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748B;
            padding: 12px 16px;
        }

        /* Swup Transition */
        .transition-fade {
            transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 1;
        }

        html.is-leaving .transition-fade {
            opacity: 0;
            transform: translateY(10px);
        }

        html.is-rendering .transition-fade {
            opacity: 0;
            transform: translateY(-10px);
        }

        /* NProgress Customization */
        #nprogress .bar {
            background: #6366f1 !important;
            height: 3px !important;
        }
        #nprogress .peg {
            box-shadow: 0 0 10px #6366f1, 0 0 5px #6366f1 !important;
        }
    </style>
</head>

<body class="min-h-screen">

    {{-- Top Navigation --}}
    <nav style="background: #fff; border-bottom: 1px solid #E0E7FF;" class="shadow-sm sticky top-0 z-50"
        x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">

                {{-- Left Side: Logo & Menu Toggle --}}
                <div class="flex items-center gap-4">
                    {{-- Mobile Hamburger --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 sm:hidden">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }"
                                class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    {{-- Logo --}}
                    <div class="flex items-center gap-2.5">
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/logo-tmj.png') }}" alt="TMJ Logo" class="h-9 w-auto object-contain">
                        </div>
                        <span class="font-bold text-slate-800" style="font-size: 0.95rem; letter-spacing: -0.01em;">TMJ
                            <span class="text-indigo-500">Dashboard</span></span>
                    </div>
                </div>

                {{-- Desktop Nav Links --}}
                <div class="hidden sm:flex items-center gap-6">
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
                    @if(in_array(Auth::user()->role, ['admin', 'hrd']))
                        <a href="{{ route('workforce.employees') }}"
                            class="nav-link {{ request()->routeIs('workforce.employees') ? 'active' : '' }}">
                            Karyawan
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
                        <div class="avatar" style="background: #E0E7FF; color: #4F46E5;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <span
                            class="text-sm font-semibold text-slate-700 hidden md:block">{{ Auth::user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="text-xs font-semibold text-slate-500 hover:text-red-600 transition border border-slate-200 rounded-lg px-3 py-1.5">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Mobile Menu Dropdown --}}
        <div x-show="mobileMenuOpen" class="sm:hidden bg-white border-b border-indigo-100 px-4 py-3 space-y-2"
            x-transition>
            <a href="{{ route('operations.dashboard') }}"
                class="block px-3 py-2 rounded-md text-sm font-bold {{ request()->routeIs('operations.dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                Beranda
            </a>
            @if(in_array(Auth::user()->role, ['admin', 'supervisor', 'hrd']))
                <a href="{{ route('workforce.attendance') }}"
                    class="block px-3 py-2 rounded-md text-sm font-bold {{ request()->routeIs('workforce.attendance') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    Absensi
                </a>
            @endif
            @if(in_array(Auth::user()->role, ['admin', 'hrd']))
                <a href="{{ route('workforce.employees') }}"
                    class="block px-3 py-2 rounded-md text-sm font-bold {{ request()->routeIs('workforce.employees') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    Karyawan
                </a>
            @endif
            @if(in_array(Auth::user()->role, ['admin', 'supervisor', 'workshop']))
                <a href="{{ route('fleet.management') }}"
                    class="block px-3 py-2 rounded-md text-sm font-bold {{ request()->routeIs('fleet.management') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    Armada
                </a>
            @endif
        </div>
    </nav>

    {{-- Main Content with Swup --}}
    <main id="swup" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 overflow-x-hidden transition-fade">
        @yield('content')
        @stack('scripts')
    </main>

    <script>
        // Configure NProgress
        NProgress.configure({ showSpinner: false });

        // Initialize Swup
        const swup = new Swup({
            plugins: [
                new SwupHeadPlugin(),
                new SwupScriptsPlugin({
                    head: false,
                    body: true
                })
            ]
        });

        // NProgress Integration
        swup.hooks.on('visit:start', () => NProgress.start());
        swup.hooks.on('visit:end', () => NProgress.done());
        
        // Function to update active menu indicators
        function updateActiveMenu() {
            const currentPath = window.location.pathname;
            
            // Desktop Links
            document.querySelectorAll('.nav-link').forEach(link => {
                const linkPath = new URL(link.href).pathname;
                if (linkPath === currentPath) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });

            // Mobile Links
            document.querySelectorAll('.sm\\:hidden a').forEach(link => {
                const linkPath = new URL(link.href).pathname;
                const isActive = linkPath === currentPath;
                if (isActive) {
                    link.classList.add('bg-indigo-50', 'text-indigo-600');
                    link.classList.remove('text-slate-600', 'hover:bg-slate-50');
                } else {
                    link.classList.remove('bg-indigo-50', 'text-indigo-600');
                    link.classList.add('text-slate-600', 'hover:bg-slate-50');
                }
            });
        }

        // Run on replace
        swup.hooks.on('content:replace', () => {
            updateActiveMenu();
            if (window.Alpine) {
                window.Alpine.discover();
            }
        });

        // Also run once on initial load just in case
        updateActiveMenu();
    </script>
</body>

</html>