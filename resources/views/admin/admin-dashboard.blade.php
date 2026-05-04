@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    {{-- Page Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pusat Komando Logistik</h1>
            <p class="text-sm text-slate-500 font-medium mt-1">PT. TRI MACHMUD JAYA — Monitoring Terpadu | {{ now()->format('d M Y') }}</p>
        </div>
        <div class="flex items-center gap-2 bg-slate-100 px-4 py-2 rounded-lg border border-slate-200">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
            </span>
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">UPDATE LANGSUNG</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    @php
        $statCols = in_array(Auth::user()->role, ['admin', 'supervisor']) ? 'lg:grid-cols-4' : 'lg:grid-cols-2';
    @endphp
    <div class="grid grid-cols-2 {{ $statCols }} gap-5 mb-8">
        @if(in_array(Auth::user()->role, ['admin', 'supervisor', 'hrd']))
        {{-- Fit --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-green-500">
            <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-green-600 mb-1">FTW: LAYAK (FIT)</p>
            <p class="text-4xl font-black text-green-600 tabular-nums">{{ $fitCount }}</p>
            <p class="text-[10px] text-green-400 font-bold mt-2 uppercase">Personel Siap Kerja</p>
        </div>

        {{-- Unfit --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-amber-500">
            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-amber-600 mb-1">FTW: TIDAK LAYAK</p>
            <p class="text-4xl font-black text-amber-600 tabular-nums">{{ $unfitCount }}</p>
            <p class="text-[10px] text-amber-400 font-bold mt-2 uppercase">Peringatan Personel</p>
        </div>
        @endif

        @if(in_array(Auth::user()->role, ['admin', 'supervisor', 'workshop']))
        {{-- Unit Ready --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-green-500 overflow-hidden relative group">
            <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center mb-4 relative z-10">
                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l9 1m1-11h4l3 5v4h-7V5z"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-green-600 mb-1 relative z-10">ARMADA: AKTIF</p>
            <p class="text-4xl font-black text-green-600 tabular-nums relative z-10">{{ $readyCount }}</p>
            <p class="text-[10px] text-green-400 font-bold mt-2 uppercase relative z-10">Unit Beroperasi</p>
        </div>

        {{-- Unit Down --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-red-500">
            <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-red-600 mb-1">ARMADA: PERBAIKAN</p>
            <p class="text-4xl font-black text-red-600 tabular-nums">{{ $downCount }}</p>
            <p class="text-[10px] text-red-400 font-bold mt-2 uppercase">Unit di Workshop</p>
        </div>
        @endif
    </div>

    {{-- Quick Navigation --}}
    @php
        $navCols = in_array(Auth::user()->role, ['admin', 'supervisor']) ? 'md:grid-cols-2' : 'md:grid-cols-1';
    @endphp
    <div class="grid grid-cols-1 {{ $navCols }} gap-4 sm:gap-5">
        @if(in_array(Auth::user()->role, ['admin', 'supervisor', 'hrd']))
        <a href="{{ route('workforce.attendance') }}"
           class="card-industrial p-4 sm:p-6 flex items-center gap-4 sm:gap-5 hover:border-indigo-300 transition-all group overflow-hidden relative">
            <div class="absolute right-0 bottom-0 p-4 opacity-5 translate-y-4 translate-x-4 group-hover:translate-y-0 group-hover:translate-x-0 transition-transform hidden sm:block">
                <svg class="w-32 h-32 text-slate-900" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl flex items-center justify-center bg-indigo-950 text-white flex-shrink-0 transition-transform group-hover:scale-110">
                <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="flex-1 relative z-10">
                <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-0.5">Modul: Personalia</p>
                <p class="text-base sm:text-lg font-black text-slate-900">Dinamika Tenaga Kerja</p>
                <p class="text-[10px] sm:text-xs text-slate-500 font-medium mt-0.5">Pantau kesehatan & log kehadiran</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border border-slate-100 flex items-center justify-center group-hover:bg-indigo-500 group-hover:text-white transition-colors relative z-10">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </div>
        </a>
        @endif

        @if(in_array(Auth::user()->role, ['admin', 'supervisor', 'workshop']))
        <a href="{{ route('fleet.management') }}"
           class="card-industrial p-4 sm:p-6 flex items-center gap-4 sm:gap-5 hover:border-indigo-300 transition-all group overflow-hidden relative">
            <div class="absolute right-0 bottom-0 p-4 opacity-5 translate-y-4 translate-x-4 group-hover:translate-y-0 group-hover:translate-x-0 transition-transform hidden sm:block">
                <svg class="w-32 h-32 text-slate-900" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l9 1m1-11h4l3 5v4h-7V5z"/>
                </svg>
            </div>
            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl flex items-center justify-center bg-indigo-950 text-white flex-shrink-0 transition-transform group-hover:scale-110">
                <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l9 1m1-11h4l3 5v4h-7V5z"/>
                </svg>
            </div>
            <div class="flex-1 relative z-10">
                <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-0.5">Modul: Aset</p>
                <p class="text-base sm:text-lg font-black text-slate-900">Intelijen Armada</p>
                <p class="text-[10px] sm:text-xs text-slate-500 font-medium mt-0.5">Status unit & pelacakan pemeliharaan</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border border-slate-100 flex items-center justify-center group-hover:bg-indigo-500 group-hover:text-white transition-colors relative z-10">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </div>
        </a>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => window.location.reload(), 30000);
        });
    </script>
@endsection
