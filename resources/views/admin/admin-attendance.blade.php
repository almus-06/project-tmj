@extends('layouts.admin')

@section('title', 'Absensi Karyawan')

@section('content')

    {{-- Page Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dinamika Tenaga Kerja</h1>
            <p class="text-sm text-slate-500 font-medium mt-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                Manajemen kehadiran dan Fit To Work (FTW) karyawan
            </p>
        </div>
        <a href="{{ route('workforce.attendance', ['export' => 'csv'] + request()->all()) }}"
            class="inline-flex items-center gap-2 bg-indigo-600 text-white text-xs font-bold px-5 py-2.5 rounded-lg hover:bg-indigo-700 transition-all shadow-sm">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            EXPORT DATA
        </a>
    </div>

    {{-- Summary Cards (Synchronized with Dashboard) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        {{-- Hadir --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-green-500">
            <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-green-600 mb-1">PRESENSI: HADIR</p>
            <p class="text-4xl font-black text-green-600 tabular-nums">{{ $hadirCount }}</p>
            <p class="text-[10px] text-green-400 font-bold mt-2 uppercase">
                {{ request()->filled('start_date') || request()->filled('end_date') || request()->filled('project') ? 'Berdasarkan Filter' : 'Karyawan Aktif Hari Ini' }}
            </p>
        </div>

        {{-- Unfit --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-amber-500">
            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-amber-600 mb-1">KESEHATAN: UNFIT</p>
            <p class="text-4xl font-black text-amber-600 tabular-nums">{{ $unfitCount }}</p>
            <p class="text-[10px] text-amber-400 font-bold mt-2 uppercase">
                {{ request()->filled('start_date') || request()->filled('end_date') || request()->filled('project') ? 'Berdasarkan Filter' : 'Perlu Tindak Lanjut' }}
            </p>
        </div>

        {{-- Leave --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-sky-500">
            <div class="w-10 h-10 rounded-lg bg-sky-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002-2z"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-sky-600 mb-1">STATUS: CUTI / IZIN</p>
            <p class="text-4xl font-black text-sky-600 tabular-nums">{{ $leaveCount }}</p>
            <p class="text-[10px] text-sky-400 font-bold mt-2 uppercase">
                {{ request()->filled('start_date') || request()->filled('end_date') || request()->filled('project') ? 'Berdasarkan Filter' : 'Karyawan Tidak Bertugas' }}
            </p>
        </div>

        {{-- Alpha --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-red-600">
            <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-red-600 mb-1">PRESENSI: ALPHA</p>
            <p class="text-4xl font-black text-red-600 tabular-nums">{{ $alphaCount }}</p>
            <p class="text-[10px] text-red-400 font-bold mt-2 uppercase">
                {{ request()->filled('start_date') || request()->filled('end_date') || request()->filled('project') ? 'Berdasarkan Filter' : 'Tanpa Keterangan Hari Ini' }}
            </p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card-industrial p-4 mb-6">
        <form method="GET" action="{{ route('workforce.attendance') }}" class="space-y-4">
            {{-- Row 1: Date Range (full width) --}}
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Rentang Tanggal</label>
                <div class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm pl-9 pr-3 py-2 text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition date-input" title="Mulai Tanggal">
                    </div>
                    <svg class="w-4 h-4 text-slate-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm pl-9 pr-3 py-2 text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition date-input" title="Sampai Tanggal">
                    </div>
                </div>
            </div>

            {{-- Row 2: Dropdowns + Buttons --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                {{-- Status Kehadiran --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Status Kehadiran</label>
                    <div x-data="{
                        search: '',
                        open: false,
                        selectedName: '{{ request('status') ?: 'Semua Status' }}',
                        selectedId: '{{ request('status', '') }}',
                        options: [
                            { id: '', name: 'Semua Status' },
                            { id: 'Hadir', name: 'Hadir' },
                            { id: 'Tidak Hadir', name: 'Tidak Hadir' },
                            { id: 'Izin', name: 'Izin' },
                            { id: 'Cuti', name: 'Cuti' },
                            { id: 'Tanpa Keterangan', name: 'Tanpa Keterangan' }
                        ],
                        get filteredOptions() {
                            if (this.search === '') return this.options;
                            return this.options.filter(o => o.name.toLowerCase().includes(this.search.toLowerCase()));
                        },
                        selectOption(o) {
                            this.selectedId = o.id;
                            this.selectedName = o.name;
                            this.search = '';
                            this.open = false;
                        }
                    }" class="relative" @click.away="open = false; search = ''">
                        <input type="hidden" name="status" :value="selectedId">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <input type="text" class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm pl-9 pr-10 py-2 text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition cursor-pointer"
                                :placeholder="selectedName" x-model="search" @click="open = true" @keydown.escape="open = false; search = ''" autocomplete="off">
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center">
                                <button type="button" @click="open = !open" tabindex="-1" class="text-slate-400 hover:text-slate-600 focus:outline-none p-1.5 rounded-full transition-colors">
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </div>
                        </div>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-1 scale-95" class="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden" style="display: none;">
                            <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                <template x-for="opt in filteredOptions" :key="opt.id">
                                    <div @click="selectOption(opt)" class="px-4 py-2.5 cursor-pointer hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0 text-sm font-bold text-slate-700" :class="selectedId === opt.id ? 'bg-indigo-50/50 text-indigo-700' : ''" x-text="opt.name"></div>
                                </template>
                                <div x-show="filteredOptions.length === 0" class="px-4 py-4 text-center"><p class="text-xs font-bold text-slate-400">Tidak ditemukan</p></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Area Project --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Area Project</label>
                    <div x-data="{
                        search: '',
                        open: false,
                        selectedName: '{{ request('project') ?: 'Semua Project' }}',
                        selectedId: '{{ request('project', '') }}',
                        options: [
                            { id: '', name: 'Semua Project' },
                            { id: 'Main Dev', name: 'Main Dev' },
                            { id: 'Sorlim', name: 'Sorlim' },
                            { id: 'Big Fleet', name: 'Big Fleet' }
                        ],
                        get filteredOptions() {
                            if (this.search === '') return this.options;
                            return this.options.filter(o => o.name.toLowerCase().includes(this.search.toLowerCase()));
                        },
                        selectOption(o) {
                            this.selectedId = o.id;
                            this.selectedName = o.name;
                            this.search = '';
                            this.open = false;
                        }
                    }" class="relative" @click.away="open = false; search = ''">
                        <input type="hidden" name="project" :value="selectedId">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <input type="text" class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm pl-9 pr-10 py-2 text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition cursor-pointer"
                                :placeholder="selectedName" x-model="search" @click="open = true" @keydown.escape="open = false; search = ''" autocomplete="off">
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center">
                                <button type="button" @click="open = !open" tabindex="-1" class="text-slate-400 hover:text-slate-600 focus:outline-none p-1.5 rounded-full transition-colors">
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </div>
                        </div>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-1 scale-95" class="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden" style="display: none;">
                            <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                <template x-for="opt in filteredOptions" :key="opt.id">
                                    <div @click="selectOption(opt)" class="px-4 py-2.5 cursor-pointer hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0 text-sm font-bold text-slate-700" :class="selectedId === opt.id ? 'bg-indigo-50/50 text-indigo-700' : ''" x-text="opt.name"></div>
                                </template>
                                <div x-show="filteredOptions.length === 0" class="px-4 py-4 text-center"><p class="text-xs font-bold text-slate-400">Tidak ditemukan</p></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-indigo-600 text-white text-[10px] font-black px-4 py-2.5 rounded-lg hover:bg-indigo-700 transition-all shadow-sm">TERAPKAN</button>
                    <a href="{{ route('workforce.attendance') }}"
                        class="flex-1 bg-white text-slate-600 text-[10px] text-center font-bold px-4 py-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition-all">HAPUS</a>
                </div>
            </div>
        </form>
    </div>

    {{-- Data Section --}}
    <div class="card-industrial overflow-hidden">
        {{-- Section Header --}}
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h2 class="text-xs font-black text-slate-700 uppercase tracking-widest">Catatan Kehadiran</h2>
            <span
                class="px-3 py-1 rounded-md text-[10px] font-black bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase">
                {{ $attendances->total() }} Entri
            </span>
        </div>

        {{-- ═══════════════════════════════════════ --}}
        {{-- MOBILE CARD VIEW (visible < lg)        --}}
        {{-- ═══════════════════════════════════════ --}}
        <div class="lg:hidden divide-y divide-slate-100">
            @forelse($attendances as $row)
                @php
                    $name = $row->employee->name ?? '—';
                    $initials = strtoupper(implode('', array_map(fn($w) => $w[0], array_filter(explode(' ', trim($name))))));
                    $initials = substr($initials, 0, 2);
                    $avatarColors = ['#DBEAFE,#1D4ED8', '#DCF7E6,#16A34A', '#FEF3C7,#92400E', '#EDE9FE,#5B21B6', '#FCE7F3,#9D174D'];
                    $colorPair = explode(',', $avatarColors[abs(crc32($name)) % count($avatarColors)]);
                @endphp
                <div class="p-4 hover:bg-indigo-50/50 transition-colors" style="{{ $loop->even ? 'background-color: #EFEFEF;' : '' }}">
                    {{-- Row 1: Avatar + Name + Time --}}
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="avatar text-[10px] font-black flex-shrink-0"
                                style="background: {{ $colorPair[0] }}; color: {{ $colorPair[1] }}; border-radius: 6px;">
                                {{ $initials }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-black text-slate-900 text-sm leading-tight truncate">{{ $name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                                    {{ $row->employee->position ?? '—' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-[11px] font-black text-slate-800 tabular-nums">{{ $row->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-slate-400 font-bold tabular-nums">{{ $row->created_at->format('H:i') }}</p>
                        </div>
                    </div>

                    {{-- Row 2: Status Badges --}}
                    <div class="flex flex-wrap items-center gap-1.5 mb-3">
                        {{-- Kehadiran --}}
                        @if($row->presence_status === 'Hadir')
                            <span class="status-chip badge-hadir">✓ Hadir</span>
                        @elseif($row->presence_status === 'Tidak Hadir' || $row->presence_status === 'Tanpa Keterangan')
                            <span class="status-chip badge-alpha">✗ Alpha</span>
                        @elseif(in_array($row->presence_status, ['Izin', 'Cuti']))
                            <span class="status-chip badge-izin">{{ $row->presence_status }}</span>
                        @else
                            <span class="status-chip badge-absent">{{ $row->presence_status }}</span>
                        @endif

                        {{-- Fit Status --}}
                        @if($row->fit_status === 'Fit')
                            <span class="status-chip badge-fit">✓ Fit</span>
                        @else
                            <span class="status-chip badge-unfit">✗ Unfit</span>
                        @endif

                        {{-- Shift --}}
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border whitespace-nowrap {{ $row->shift === 'Shift Pagi' ? 'bg-amber-50 text-amber-700 border-amber-200' : ($row->shift === 'Shift Malam' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-slate-50 text-slate-500 border-slate-200') }}">{{ $row->shift ?? '—' }}</span>
                    </div>

                    {{-- Row 3: Project + Metrics --}}
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded">
                            📍 {{ $row->project->name ?? '—' }}
                        </span>
                        <div class="flex gap-1 tabular-nums">
                            <span class="text-[9px] bg-slate-100 px-1.5 py-0.5 rounded font-bold text-slate-500">BP: {{ $row->blood_pressure }}</span>
                            <span class="text-[9px] bg-slate-100 px-1.5 py-0.5 rounded font-bold text-slate-500">SpO2: {{ $row->spo2 }}%</span>
                            <span class="text-[9px] bg-slate-100 px-1.5 py-0.5 rounded font-bold text-slate-500">T: {{ $row->temperature }}°C</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-5 py-12 text-center">
                    <div class="flex flex-col items-center gap-2">
                        <svg class="w-10 h-10 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-slate-400 text-sm font-medium">Belum ada data absensi yang sesuai filter.</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- ═══════════════════════════════════════ --}}
        {{-- DESKTOP TABLE VIEW (visible lg+)       --}}
        {{-- ═══════════════════════════════════════ --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Personel</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Penempatan</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Shift</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Kehadiran</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Metrik FTW</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Hasil FTW</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($attendances as $row)
                        @php
                            $name = $row->employee->name ?? '—';
                            $initials = strtoupper(implode('', array_map(fn($w) => $w[0], array_filter(explode(' ', trim($name))))));
                            $initials = substr($initials, 0, 2);
                            $avatarColors = ['#DBEAFE,#1D4ED8', '#DCF7E6,#16A34A', '#FEF3C7,#92400E', '#EDE9FE,#5B21B6', '#FCE7F3,#9D174D'];
                            $colorPair = explode(',', $avatarColors[abs(crc32($name)) % count($avatarColors)]);
                        @endphp
                        <tr class="hover:bg-indigo-50 transition-colors border-b border-slate-200/60" style="{{ $loop->even ? 'background-color: #EFEFEF;' : '' }}">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="avatar text-[10px] font-black"
                                        style="background: {{ $colorPair[0] }}; color: {{ $colorPair[1] }}; border-radius: 6px;">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-900 leading-tight">{{ $name }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                                            {{ $row->employee->position ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-xs font-bold text-slate-700">{{ $row->project->name ?? '—' }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2 py-1 rounded text-[10px] font-bold border whitespace-nowrap {{ $row->shift === 'Shift Pagi' ? 'bg-amber-50 text-amber-700 border-amber-200' : ($row->shift === 'Shift Malam' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-slate-50 text-slate-500 border-slate-200') }}">{{ $row->shift ?? '—' }}</span>
                            </td>
                            <td class="px-5 py-4">
                                @if($row->presence_status === 'Hadir')
                                    <span class="status-chip badge-hadir">{{ $row->presence_status }}</span>
                                @elseif(in_array($row->presence_status, ['Izin', 'Cuti']))
                                    <span class="status-chip badge-izin">{{ $row->presence_status }}</span>
                                @else
                                    <span class="status-chip badge-absent">{{ $row->presence_status }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2 tabular-nums">
                                    <span class="text-[10px] bg-slate-100 px-1.5 py-0.5 rounded font-bold text-slate-600">BP: {{ $row->blood_pressure }}</span>
                                    <span class="text-[10px] bg-slate-100 px-1.5 py-0.5 rounded font-bold text-slate-600">SpO2: {{ $row->spo2 }}%</span>
                                    <span class="text-[10px] bg-slate-100 px-1.5 py-0.5 rounded font-bold text-slate-600">T: {{ $row->temperature }}°C</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                @if($row->fit_status === 'Fit')
                                    <span class="status-chip badge-fit">✓ Fit</span>
                                @else
                                    <span class="status-chip badge-unfit">✗ Unfit</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right tabular-nums">
                                <p class="text-xs font-black text-slate-800">{{ $row->created_at->format('d M Y') }}</p>
                                <p class="text-[10px] text-slate-400 font-bold">{{ $row->created_at->format('H:i') }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-slate-400 text-sm font-medium">Belum ada data absensi yang sesuai filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-6 border-t border-slate-100 bg-slate-50/30 rounded-b-2xl">
            <div class="flex items-center justify-between">
                <div class="hidden sm:block">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        Menampilkan <span class="text-slate-900">{{ $attendances->firstItem() }}</span> - <span class="text-slate-900">{{ $attendances->lastItem() }}</span> dari <span class="text-slate-900">{{ $attendances->total() }}</span> entri
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    @if($attendances->onFirstPage())
                        <span class="px-4 py-2 text-[10px] font-black text-slate-300 uppercase tracking-widest bg-white border border-slate-100 rounded-lg cursor-not-allowed">
                            Previous
                        </span>
                    @else
                        <a href="{{ $attendances->previousPageUrl() }}" class="px-4 py-2 text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-white border border-indigo-100 rounded-lg hover:bg-indigo-600 hover:text-white transition-all shadow-sm active:scale-95">
                            Previous
                        </a>
                    @endif

                    @if($attendances->hasMorePages())
                        <a href="{{ $attendances->nextPageUrl() }}" class="px-5 py-2 text-[10px] font-black text-white uppercase tracking-widest bg-gradient-to-r from-indigo-600 to-violet-600 rounded-lg hover:shadow-lg hover:shadow-indigo-200 transition-all flex items-center gap-2 group active:scale-95">
                            Next
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span class="px-5 py-2 text-[10px] font-black text-slate-300 uppercase tracking-widest bg-slate-100 border border-slate-200 rounded-lg cursor-not-allowed flex items-center gap-2">
                            Next
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                            </svg>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection