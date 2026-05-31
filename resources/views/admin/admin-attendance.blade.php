@extends('layouts.admin')

@section('title', 'Absensi Karyawan')

@section('content')

    {{-- Page Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Safety Number 01</h1>
            <p class="text-sm text-slate-500 font-medium mt-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                Manajemen kehadiran dan Fit To Work karyawan
            </p>
        </div>
        <a href="{{ route('workforce.attendance', ['export' => 'csv'] + request()->all()) }}"
            class="inline-flex items-center gap-2 bg-emerald-600 text-white text-xs font-bold px-5 py-2.5 rounded-lg hover:bg-emerald-700 transition-all shadow-sm">
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
            <p class="text-[10px] font-black uppercase tracking-widest text-green-600 mb-1">Fit To Work</p>
            <p class="text-4xl font-black text-green-600 tabular-nums">{{ $hadirCount }}</p>
            <p class="text-[10px] text-green-400 font-bold mt-2 uppercase">
                {{ request()->filled('start_date') || request()->filled('end_date') || request()->filled('project') ? 'Berdasarkan Filter' : 'Personel Sehat' }}
            </p>
        </div>

        {{-- Unfit --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-amber-500">
            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-amber-600 mb-1">Unfit</p>
            <p class="text-4xl font-black text-amber-600 tabular-nums">{{ $unfitCount }}</p>
            <p class="text-[10px] text-amber-400 font-bold mt-2 uppercase">
                {{ request()->filled('start_date') || request()->filled('end_date') || request()->filled('project') ? 'Berdasarkan Filter' : 'Personel Kurang Sehat' }}
            </p>
        </div>

        {{-- Leave --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-sky-500">
            <div class="w-10 h-10 rounded-lg bg-sky-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-sky-600 mb-1">CUTI / IZIN</p>
            <p class="text-4xl font-black text-sky-600 tabular-nums">{{ $leaveCount }}</p>
            <p class="text-[10px] text-sky-400 font-bold mt-2 uppercase">
                {{ request()->filled('start_date') || request()->filled('end_date') || request()->filled('project') ? 'Berdasarkan Filter' : 'Personel Tidak Bertugas' }}
            </p>
        </div>

        {{-- Alpha --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-red-600">
            <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-red-600 mb-1">Tanpa Keterangan</p>
            <p class="text-4xl font-black text-red-600 tabular-nums">{{ $alphaCount }}</p>
            <p class="text-[10px] text-red-400 font-bold mt-2 uppercase">
                {{ request()->filled('start_date') || request()->filled('end_date') || request()->filled('project') ? 'Berdasarkan Filter' : 'Personel Tidak Hadir' }}
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
                            @if($row->photo_path)
                                <button type="button" onclick="showPhotoModal('{{ asset('storage/' . $row->photo_path) }}', '{{ $name }}', '{{ $row->created_at->format('d M Y H:i') }}', '{{ $row->project->name ?? '—' }}', '{{ $row->distance_from_project }}', '{{ $row->is_inside_radius }}')" class="relative group cursor-pointer overflow-hidden rounded-lg w-10 h-10 border border-slate-200 flex-shrink-0 bg-slate-100">
                                    <img src="{{ asset('storage/' . $row->photo_path) }}" class="w-full h-full object-cover" alt="Selfie">
                                </button>
                            @else
                                <div class="avatar text-[10px] font-black flex-shrink-0"
                                    style="background: {{ $colorPair[0] }}; color: {{ $colorPair[1] }}; border-radius: 6px;">
                                    {{ $initials }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <a href="{{ route('workforce.attendance.employee', $row->employee_id) }}" class="font-black text-slate-900 hover:text-indigo-600 transition-colors text-sm leading-tight truncate block">
                                    {{ $name }}
                                </a>
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
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-black tracking-wide border whitespace-nowrap {{ $row->shift === 'Shift Pagi' ? 'bg-amber-50 text-amber-700 border-amber-200' : ($row->shift === 'Shift Malam' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-slate-50 text-slate-500 border-slate-200') }}">{{ $row->shift ?? '—' }}</span>

                        {{-- Location --}}
                        @if($row->is_fake_gps_suspected)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border whitespace-nowrap bg-purple-50 text-purple-700 border-purple-200">🚩 Fake GPS</span>
                        @endif
                        @if($row->is_inside_radius === true)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border whitespace-nowrap bg-emerald-50 text-emerald-700 border-emerald-200">📍 Dalam Area</span>
                        @elseif($row->is_inside_radius === false)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border whitespace-nowrap bg-red-50 text-red-600 border-red-200">⚠ Luar Area ({{ $row->distance_from_project >= 1000 ? round($row->distance_from_project / 1000, 1) . 'km' : round($row->distance_from_project) . 'm' }})</span>
                        @elseif($row->latitude)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border whitespace-nowrap bg-slate-50 text-slate-400 border-slate-200">📍 —</span>
                        @endif
                    </div>

                    {{-- Row 3: Project + Metrics --}}
                    <div class="flex flex-col gap-2">
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
                        
                        @if($row->accuracy || $row->speed > 0)
                        <div class="flex flex-wrap gap-1 tabular-nums">
                            @if($row->accuracy)
                                <span class="text-[9px] bg-sky-50 px-1.5 py-0.5 rounded border border-sky-100 font-bold text-sky-600">🎯 Akurasi: ±{{ round($row->accuracy) }}m</span>
                            @endif
                            @if($row->speed > 0)
                                <span class="text-[9px] bg-amber-50 px-1.5 py-0.5 rounded border border-amber-100 font-bold text-amber-600">⚡ Kecepatan: {{ round($row->speed, 1) }}m/s</span>
                            @endif
                        </div>
                        @endif
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
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Foto</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Penempatan</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Shift</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Kehadiran</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Metrik FTW</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Hasil FTW</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Lokasi</th>
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
                            <td class="px-5 py-2">
                                <div class="flex items-center gap-3">
                                    <div class="avatar text-[10px] font-black"
                                        style="background: {{ $colorPair[0] }}; color: {{ $colorPair[1] }}; border-radius: 6px;">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <a href="{{ route('workforce.attendance.employee', $row->employee_id) }}" class="font-black text-slate-900 hover:text-indigo-600 transition-colors leading-tight block text-xs">
                                            {{ $name }}
                                        </a>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">
                                            {{ $row->employee->position ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-2">
                                @if($row->photo_path)
                                    <button type="button" onclick="showPhotoModal('{{ asset('storage/' . $row->photo_path) }}', '{{ $name }}', '{{ $row->created_at->format('d M Y H:i') }}', '{{ $row->project->name ?? '—' }}', '{{ $row->distance_from_project }}', '{{ $row->is_inside_radius }}')" class="relative group cursor-pointer overflow-hidden rounded-lg w-8 h-8 border border-slate-200 hover:border-indigo-400 transition-all flex items-center justify-center bg-slate-100">
                                        <img src="{{ asset('storage/' . $row->photo_path) }}" class="w-full h-full object-cover transition-transform group-hover:scale-110" alt="Selfie">
                                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </button>
                                @else
                                    <span class="text-[10px] text-slate-400 font-bold">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-2">
                                <p class="text-xs font-bold text-slate-700">{{ $row->project->name ?? '—' }}</p>
                            </td>
                            <td class="px-5 py-2">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-black tracking-wide border whitespace-nowrap {{ $row->shift === 'Shift Pagi' ? 'bg-amber-50 text-amber-700 border-amber-200' : ($row->shift === 'Shift Malam' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-slate-50 text-slate-500 border-slate-200') }}">{{ $row->shift ?? '—' }}</span>
                            </td>
                            <td class="px-5 py-2">
                                @if($row->presence_status === 'Hadir')
                                    <span class="status-chip badge-hadir" style="padding-top: 2px; padding-bottom: 2px;">{{ $row->presence_status }}</span>
                                @elseif(in_array($row->presence_status, ['Izin', 'Cuti']))
                                    <span class="status-chip badge-izin" style="padding-top: 2px; padding-bottom: 2px;">{{ $row->presence_status }}</span>
                                @else
                                    <span class="status-chip badge-absent" style="padding-top: 2px; padding-bottom: 2px;">{{ $row->presence_status }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-2">
                                <div class="flex items-center gap-1 tabular-nums">
                                    <span class="text-[9px] bg-slate-100 px-1 py-0.5 rounded font-bold text-slate-600 border border-slate-200/60 whitespace-nowrap">BP: {{ $row->blood_pressure }}</span>
                                    <span class="text-[9px] bg-slate-100 px-1 py-0.5 rounded font-bold text-slate-600 border border-slate-200/60 whitespace-nowrap">SpO2: {{ $row->spo2 }}%</span>
                                    <span class="text-[9px] bg-slate-100 px-1 py-0.5 rounded font-bold text-slate-600 border border-slate-200/60 whitespace-nowrap">T: {{ $row->temperature }}°C</span>
                                </div>
                            </td>
                            <td class="px-5 py-2">
                                @if($row->fit_status === 'Fit')
                                    <span class="status-chip badge-fit" style="padding-top: 2px; padding-bottom: 2px;">✓ Fit</span>
                                @else
                                    <span class="status-chip badge-unfit" style="padding-top: 2px; padding-bottom: 2px;">✗ Unfit</span>
                                @endif
                            </td>
                            <td class="px-5 py-2">
                                <div class="flex flex-col gap-0.5 items-start">
                                    <div class="flex flex-wrap gap-1">
                                        @if($row->is_fake_gps_suspected)
                                            <span class="status-chip bg-purple-50 text-purple-700 border-purple-200" style="padding-top: 1px; padding-bottom: 1px; font-size: 0.65rem;">🚩 Fake GPS</span>
                                        @endif
                                        @if($row->is_inside_radius === true)
                                            <span class="status-chip badge-hadir" style="padding-top: 1px; padding-bottom: 1px; font-size: 0.65rem;">📍 Dalam Area</span>
                                        @elseif($row->is_inside_radius === false)
                                            <span class="status-chip badge-absent" style="padding-top: 1px; padding-bottom: 1px; font-size: 0.65rem;">⚠ Luar Area ({{ $row->distance_from_project >= 1000 ? round($row->distance_from_project / 1000, 1) . 'km' : round($row->distance_from_project) . 'm' }})</span>
                                        @else
                                            <span class="text-[9px] text-slate-300 font-bold">—</span>
                                        @endif
                                    </div>

                                    @if($row->accuracy || $row->speed > 0)
                                        <div class="flex flex-wrap gap-1 tabular-nums">
                                            @if($row->accuracy)
                                                <span class="text-[8px] bg-sky-50 px-1 py-0.2 rounded font-bold text-sky-600 border border-sky-100" title="Akurasi GPS">🎯 ±{{ round($row->accuracy) }}m</span>
                                            @endif
                                            @if($row->speed > 0)
                                                <span class="text-[8px] bg-amber-50 px-1 py-0.2 rounded font-bold text-amber-600 border border-amber-100" title="Kecepatan saat absen">⚡ {{ round($row->speed, 1) }}m/s</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-2 text-right tabular-nums">
                                <p class="text-xs font-black text-slate-800">{{ $row->created_at->format('d M Y') }}</p>
                                <p class="text-[9px] text-slate-400 font-bold">{{ $row->created_at->format('H:i') }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
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

    {{-- Premium Photo View Modal --}}
    <div id="photo_modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0 pointer-events-none" onclick="hidePhotoModal()">
        <div class="relative bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-sm w-full overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col" onclick="event.stopPropagation()">
            {{-- Modal Header --}}
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h3 id="modal_employee_name" class="font-black text-slate-900 text-sm leading-tight">Nama Karyawan</h3>
                    <p id="modal_attendance_time" class="text-[10px] text-slate-400 font-semibold uppercase mt-0.5">Tanggal & Waktu</p>
                </div>
                <button type="button" onclick="hidePhotoModal()" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            {{-- Photo Body --}}
            <div class="relative bg-slate-950 aspect-square flex items-center justify-center overflow-hidden">
                <img id="modal_photo_img" src="" class="w-full h-full object-cover" alt="Foto Verifikasi Full">
            </div>

            {{-- Metadata Info Footer --}}
            <div class="p-4 bg-slate-50 border-t border-slate-100 text-xs text-slate-600 space-y-2">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-slate-400 uppercase text-[9px] tracking-wider">Project Area</span>
                    <span id="modal_project_name" class="font-black text-slate-800 bg-slate-200 px-2 py-0.5 rounded text-[10px]">Project</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-slate-400 uppercase text-[9px] tracking-wider">Status Jarak</span>
                    <span id="modal_distance_status" class="font-bold text-[10px] px-2 py-0.5 rounded">Status Jarak</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showPhotoModal(imgUrl, employeeName, timeStr, projectName, distance, isInsideRadius) {
            const modal = document.getElementById('photo_modal');
            const modalImg = document.getElementById('modal_photo_img');
            const modalName = document.getElementById('modal_employee_name');
            const modalTime = document.getElementById('modal_attendance_time');
            const modalProject = document.getElementById('modal_project_name');
            const modalDistance = document.getElementById('modal_distance_status');

            modalImg.src = imgUrl;
            modalName.innerText = employeeName;
            modalTime.innerText = timeStr;
            modalProject.innerText = projectName;

            // Parse isInsideRadius and distance
            const radiusInside = isInsideRadius === '1' || isInsideRadius === 'true' || isInsideRadius === true;
            if (radiusInside) {
                modalDistance.innerText = '📍 Dalam Radius Area';
                modalDistance.className = 'font-bold text-[10px] px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200';
            } else if (distance && distance !== '') {
                const distVal = parseFloat(distance);
                const formattedDist = distVal >= 1000 ? (distVal / 1000).toFixed(1) + 'km' : Math.round(distVal) + 'm';
                modalDistance.innerText = '⚠ Luar Radius (' + formattedDist + ')';
                modalDistance.className = 'font-bold text-[10px] px-2 py-0.5 rounded bg-red-50 text-red-600 border border-red-200';
            } else {
                modalDistance.innerText = '📍 —';
                modalDistance.className = 'font-bold text-[10px] px-2 py-0.5 rounded bg-slate-100 text-slate-500';
            }

            modal.classList.remove('pointer-events-none', 'opacity-0');
            modal.firstElementChild.classList.remove('scale-95');
            modal.firstElementChild.classList.add('scale-100');
        }

        function hidePhotoModal() {
            const modal = document.getElementById('photo_modal');
            modal.classList.add('pointer-events-none', 'opacity-0');
            modal.firstElementChild.classList.remove('scale-100');
            modal.firstElementChild.classList.add('scale-95');
        }

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hidePhotoModal();
            }
        });
    </script>

@endsection