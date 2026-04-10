@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800">Logistics Overview</h1>
            <p class="text-sm text-slate-500 mt-0.5">PT. TRI MACHMUD JAYA — Monitoring Harian | {{ now()->format('d M Y') }}</p>
        </div>
        <div class="flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold" style="background:#EFF6FF; color:#1D4ED8;">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
            Auto-refresh 30s
        </div>
    </div>

    {{-- Stat Cards --}}
    @php
        $statCols = in_array(Auth::user()->role, ['admin', 'supervisor']) ? 'lg:grid-cols-4' : 'lg:grid-cols-2';
    @endphp
    <div class="grid grid-cols-2 {{ $statCols }} gap-4 mb-6">
        @if(in_array(Auth::user()->role, ['admin', 'supervisor', 'hrd']))
        {{-- Fit --}}
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #DCFCE7;">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="#16A34A" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Fit Hari Ini</p>
                <p class="text-2xl font-extrabold text-slate-800">{{ $fitCount }}</p>
                <p class="text-xs text-slate-400">karyawan dinyatakan fit</p>
            </div>
        </div>

        {{-- Unfit --}}
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #FEE2E2;">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="#DC2626" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Unfit Hari Ini</p>
                <p class="text-2xl font-extrabold text-slate-800">{{ $unfitCount }}</p>
                <p class="text-xs text-slate-400">karyawan perlu perhatian</p>
            </div>
        </div>
        @endif

        @if(in_array(Auth::user()->role, ['admin', 'supervisor', 'workshop']))
        {{-- Unit Ready --}}
        <div class="stat-card" style="background: linear-gradient(135deg, #1D4ED8, #2563EB); border: none;">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold uppercase tracking-widest" style="color: rgba(255,255,255,0.7);">Active Fleet</p>
                <svg class="w-5 h-5 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l9 1m1-11h4l3 5v4h-7V5z"/>
                </svg>
            </div>
            <p class="text-3xl font-extrabold text-white">{{ $readyUnit }}</p>
            <p class="text-xs font-medium mt-1" style="color: rgba(255,255,255,0.65);">kendaraan siap operasi</p>
        </div>

        {{-- Unit Down --}}
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #FEE2E2;">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="#DC2626" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Unit Down</p>
                <p class="text-2xl font-extrabold text-slate-800">{{ $downUnit }}</p>
                <p class="text-xs text-slate-400">kendaraan dalam perbaikan</p>
            </div>
        </div>
        @endif
    </div>

    {{-- Quick Navigation --}}
    @php
        $navCols = in_array(Auth::user()->role, ['admin', 'supervisor']) ? 'md:grid-cols-2' : 'md:grid-cols-1';
    @endphp
    <div class="grid grid-cols-1 {{ $navCols }} gap-4">
        @if(in_array(Auth::user()->role, ['admin', 'supervisor', 'hrd']))
        <a href="{{ route('dashboard.attendance') }}"
           class="bg-white rounded-2xl border border-slate-200 p-5 flex items-center gap-4 hover:shadow-md hover:border-blue-300 transition-all group">
            <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0 transition-all group-hover:scale-105" style="background: #EFF6FF;">
                <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="font-bold text-slate-800">Data Absensi Karyawan</p>
                <p class="text-xs text-slate-500 mt-0.5">Filter, lihat, dan export data Fit To Work</p>
            </div>
            <svg class="w-5 h-5 text-slate-300 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif

        @if(in_array(Auth::user()->role, ['admin', 'supervisor', 'workshop']))
        <a href="{{ route('dashboard.fleet') }}"
           class="bg-white rounded-2xl border border-slate-200 p-5 flex items-center gap-4 hover:shadow-md hover:border-blue-300 transition-all group">
            <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0 transition-all group-hover:scale-105" style="background: #EFF6FF;">
                <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l9 1m1-11h4l3 5v4h-7V5z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="font-bold text-slate-800">Data Status Unit / Fleet</p>
                <p class="text-xs text-slate-500 mt-0.5">Monitor status dan kondisi unit kendaraan</p>
            </div>
            <svg class="w-5 h-5 text-slate-300 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif
    </div>

    <script>
        setTimeout(() => window.location.reload(), 30000);
    </script>
@endsection
