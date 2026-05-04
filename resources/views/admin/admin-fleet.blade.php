@extends('layouts.admin')

@section('title', 'Monitoring Unit')

@section('content')

    {{-- Page Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Intelijen Armada</h1>
            <p class="text-sm text-slate-500 font-medium mt-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                Monitoring status dan performa unit operasional
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('fleet.management', ['export' => 'csv'] + request()->all()) }}"
               class="inline-flex items-center gap-2 bg-indigo-600 text-white text-xs font-bold px-5 py-2.5 rounded-lg hover:bg-indigo-700 transition-all shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                EXPORT DATA
            </a>
        </div>
    </div>

    {{-- Summary Stats (Synchronized with Dashboard) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        {{-- Total Assets --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-slate-500">
            <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-600 mb-1">ARMADA: TOTAL</p>
            <p class="text-4xl font-black text-slate-900 tabular-nums">{{ $totalUnits }}</p>
            <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase">
                {{ request()->filled('start_date') || request()->filled('end_date') || request()->filled('project') || request()->filled('status') ? 'Berdasarkan Filter' : 'Aset Terdaftar' }}
            </p>
        </div>

        {{-- Ready --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-green-500">
            <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l9 1m1-11h4l3 5v4h-7V5z"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-green-600 mb-1">ARMADA: READY</p>
            <p class="text-4xl font-black text-green-600 tabular-nums">{{ $readyCount }}</p>
            <p class="text-[10px] text-green-400 font-bold mt-2 uppercase">
                {{ request()->filled('start_date') || request()->filled('end_date') || request()->filled('project') || request()->filled('status') ? 'Berdasarkan Filter' : 'Unit Siap Operasi' }}
            </p>
        </div>

        {{-- Standby --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-amber-500">
            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-amber-600 mb-1">ARMADA: STANDBY</p>
            <p class="text-4xl font-black text-amber-600 tabular-nums">{{ $standbyCount }}</p>
            <p class="text-[10px] text-amber-400 font-bold mt-2 uppercase">
                {{ request()->filled('start_date') || request()->filled('end_date') || request()->filled('project') || request()->filled('status') ? 'Berdasarkan Filter' : 'Unit Menunggu Antrian' }}
            </p>
        </div>

        {{-- Down --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-red-500">
            <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-red-600 mb-1">ARMADA: DOWN</p>
            <p class="text-4xl font-black text-red-600 tabular-nums">{{ $downCount }}</p>
            <p class="text-[10px] text-red-400 font-bold mt-2 uppercase">
                {{ request()->filled('start_date') || request()->filled('end_date') || request()->filled('project') || request()->filled('status') ? 'Berdasarkan Filter' : 'Unit di Workshop' }}
            </p>
        </div>
    </div>

    <div class="card-industrial p-4 mb-6">
        <form method="GET" action="{{ route('fleet.management') }}" class="space-y-4">
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
                {{-- Status Unit --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Status Unit</label>
                    <div x-data="{
                        search: '',
                        open: false,
                        selectedName: '{{ request('status') ?: 'Semua Status' }}',
                        selectedId: '{{ request('status', '') }}',
                        options: [
                            { id: '', name: 'Semua Status' },
                            { id: 'Ready', name: 'Ready' },
                            { id: 'Standby', name: 'Standby' },
                            { id: 'Down', name: 'Down' }
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
                    <button type="submit" class="flex-1 bg-indigo-600 text-white text-[10px] font-black px-4 py-2.5 rounded-lg hover:bg-indigo-700 transition-all shadow-sm">TERAPKAN</button>
                    <a href="{{ route('fleet.management') }}" class="flex-1 bg-white text-slate-600 text-[10px] text-center font-bold px-4 py-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition-all">HAPUS</a>
                </div>
            </div>
        </form>
    </div>

    {{-- Data Table + Chart --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Table (2/3) --}}
        <div class="lg:col-span-2 card-industrial overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h2 class="text-xs font-black text-slate-700 uppercase tracking-widest">Status Armada Langsung</h2>
                <span class="px-3 py-1 rounded-md text-[10px] font-black bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase">
                    {{ $unitStatuses->total() }} Unit
                </span>
            </div>
            {{-- MOBILE CARD VIEW (< lg) --}}
            <div class="lg:hidden divide-y divide-slate-100" x-data="{ expanded: null }">
                @forelse($unitStatuses as $row)
                    @php
                        $jenis = strtoupper($row->unit->jenis_alat ?? '');
                        $isHM = Str::contains($jenis, ['EXCAVATOR', 'DOZER', 'COMPACTOR', 'GRADER', 'LOADER']);
                    @endphp
                    <div class="p-4 transition-colors" style="{{ $loop->even ? 'background-color: #EFEFEF;' : '' }}">
                        <div class="flex items-start justify-between gap-3 mb-2" @click="expanded = expanded === {{ $row->id }} ? null : {{ $row->id }}">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 flex-shrink-0">
                                    <svg class="w-4 h-4 transform transition-transform duration-200" :class="{'rotate-90': expanded === {{ $row->id }}}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-black text-slate-900 text-sm leading-tight truncate">{{ $row->unit->no_kendaraan ?? '—' }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $row->unit->jenis_alat ?? '—' }}</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 tabular-nums">
                                @if($isHM)
                                    <p class="text-sm font-black text-slate-800">{{ number_format($row->hm, 1) }} <span class="text-[10px] text-slate-400">HM</span></p>
                                @else
                                    <p class="text-sm font-black text-slate-800">{{ number_format($row->km, 1) }} <span class="text-[10px] text-slate-400">KM</span></p>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-1.5 mb-2">
                            @if($row->status === 'Ready')
                                <span class="status-chip badge-ready">Ready</span>
                            @elseif($row->status === 'Standby')
                                <span class="status-chip badge-standby">Standby</span>
                            @else
                                <span class="status-chip badge-down">Down</span>
                            @endif
                            <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded whitespace-nowrap">📍 {{ $row->project->name ?? '—' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-[10px] font-bold text-slate-500">👤 {{ $row->operator->name ?? '—' }} • {{ $row->location }}</p>
                            @if($row->status === 'Down' && $row->damage_type)
                                <span class="text-[9px] font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded border border-red-100">{{ Str::limit($row->damage_type, 20) }}</span>
                            @endif
                        </div>
                        <div x-show="expanded === {{ $row->id }}" x-transition class="mt-3 pt-3 border-t border-slate-200" style="display:none;">
                            @php $historyLogs = \App\Models\UnitStatus::where('unit_id', $row->unit_id)->where('id', '!=', $row->id)->latest()->take(3)->get(); @endphp
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Riwayat</p>
                            @forelse($historyLogs as $log)
                                <div class="flex items-center justify-between py-1.5 border-b border-slate-100 last:border-0">
                                    <div>
                                        <p class="text-[10px] font-black text-slate-700">{{ $log->created_at->format('d M Y, H:i') }}</p>
                                        <p class="text-[9px] font-bold text-slate-400">{{ $log->status }} • {{ optional($log->project)->name ?? '—' }}</p>
                                    </div>
                                    <p class="text-[10px] font-black text-slate-600 tabular-nums">{{ $isHM ? number_format($log->hm, 1).' HM' : number_format($log->km, 1).' KM' }}</p>
                                </div>
                            @empty
                                <p class="text-[10px] text-slate-400 italic">Belum ada riwayat.</p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center">
                        <p class="text-slate-400 text-sm font-medium">Belum ada data unit yang sesuai filter.</p>
                    </div>
                @endforelse
            </div>

            {{-- DESKTOP TABLE VIEW (lg+) --}}
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100">
                            <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">Identitas Unit</th>
                            <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Status Saat Ini</th>
                            <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Penempatan</th>
                            <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Metrik (HM/KM)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200" x-data="{ expanded: null }">
                        @forelse($unitStatuses as $row)
                            <tr class="hover:bg-indigo-50 transition-colors cursor-pointer group border-b border-slate-200/60" style="{{ $loop->even ? 'background-color: #EFEFEF;' : '' }}" @click="expanded = expanded === {{ $row->id }} ? null : {{ $row->id }}">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                            <svg class="w-4 h-4 transform transition-transform duration-200" :class="{'rotate-90': expanded === {{ $row->id }}}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-black text-slate-900">{{ $row->unit->no_kendaraan ?? '—' }}</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $row->unit->jenis_alat ?? '—' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    @if($row->status === 'Ready')
                                        <span class="status-chip badge-ready">Ready</span>
                                    @elseif($row->status === 'Standby')
                                        <span class="status-chip badge-standby">Standby</span>
                                    @else
                                        <span class="status-chip badge-down">Down</span>
                                        @if($row->damage_type)
                                            <p class="text-[10px] text-red-500 font-bold mt-1 uppercase tracking-tighter">{{ Str::limit($row->damage_type, 30) }}</p>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-sm text-slate-700 font-bold">{{ $row->operator->name ?? '—' }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $row->project->name ?? '—' }} • {{ $row->location }}</p>
                                </td>
                                <td class="px-5 py-4 text-right tabular-nums">
                                    @php
                                        $jenis = strtoupper($row->unit->jenis_alat ?? '');
                                        $isHM = Str::contains($jenis, ['EXCAVATOR', 'DOZER', 'COMPACTOR', 'GRADER', 'LOADER']);
                                    @endphp
                                    
                                    @if($isHM)
                                        <p class="text-sm font-black text-slate-800">{{ number_format($row->hm, 1) }} <span class="text-[10px] text-slate-400 font-bold">HM</span></p>
                                    @else
                                        <p class="text-sm font-black text-slate-800">{{ number_format($row->km, 1) }} <span class="text-[10px] text-slate-400 font-bold">KM</span></p>
                                    @endif
                                </td>
                            </tr>
                            
                            {{-- Expandable History Row --}}
                            <tr x-show="expanded === {{ $row->id }}" x-transition style="display: none;">
                                <td colspan="4" class="px-5 py-6 bg-slate-50/50 border-y border-slate-100">
                                    @php
                                        // Fetch latest history omitting the current row
                                        $historyLogs = \App\Models\UnitStatus::where('unit_id', $row->unit_id)
                                                        ->where('id', '!=', $row->id)
                                                        ->latest()
                                                        ->take(5)
                                                        ->get();
                                    @endphp
                                    
                                    <div class="px-8">
                                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Log Riwayat Aset
                                        </h4>
                                        
                                        @if($historyLogs->isEmpty())
                                            <p class="text-sm text-slate-400 italic">No previous history available for this unit.</p>
                                        @else
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                @foreach($historyLogs as $log)
                                                    <div class="bg-white rounded-lg border border-slate-200 p-3 shadow-sm relative overflow-hidden">
                                                        <div class="absolute top-0 left-0 w-1 h-full 
                                                            {{ $log->status === 'Ready' ? 'bg-green-500' : ($log->status === 'Standby' ? 'bg-amber-500' : 'bg-red-500') }}">
                                                        </div>
                                                        <div class="flex justify-between items-start pl-2">
                                                            <div>
                                                                <p class="text-[10px] font-black text-slate-900">{{ $log->created_at->format('d M Y, H:i') }}</p>
                                                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $log->status }} • {{ optional($log->project)->name ?? '—' }}</p>
                                                            </div>
                                                            <div class="text-right tabular-nums">
                                                                @if($isHM)
                                                                    <p class="text-xs font-black text-slate-700">{{ number_format($log->hm, 1) }} HM</p>
                                                                @else
                                                                    <p class="text-xs font-black text-slate-700">{{ number_format($log->km, 1) }} KM</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @if($log->damage_type)
                                                            <div class="ml-2 mt-2 py-1 px-2 bg-red-50 rounded border border-red-100">
                                                                <p class="text-[9px] text-red-600 font-bold uppercase tracking-tighter">FAULT: {{ $log->damage_type }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-10 h-10 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-slate-400 text-sm font-medium">Belum ada data unit yang sesuai filter.</p>
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
                            Menampilkan <span class="text-slate-900">{{ $unitStatuses->firstItem() }}</span> - <span class="text-slate-900">{{ $unitStatuses->lastItem() }}</span> dari <span class="text-slate-900">{{ $unitStatuses->total() }}</span> unit
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($unitStatuses->onFirstPage())
                            <span class="px-4 py-2 text-[10px] font-black text-slate-300 uppercase tracking-widest bg-white border border-slate-100 rounded-lg cursor-not-allowed">
                                Previous
                            </span>
                        @else
                            <a href="{{ $unitStatuses->previousPageUrl() }}" class="px-4 py-2 text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-white border border-indigo-100 rounded-lg hover:bg-indigo-600 hover:text-white transition-all shadow-sm active:scale-95">
                                Previous
                            </a>
                        @endif

                        @if($unitStatuses->hasMorePages())
                            <a href="{{ $unitStatuses->nextPageUrl() }}" class="px-5 py-2 text-[10px] font-black text-white uppercase tracking-widest bg-gradient-to-r from-indigo-600 to-violet-600 rounded-lg hover:shadow-lg hover:shadow-indigo-200 transition-all flex items-center gap-2 group active:scale-95">
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

        {{-- Sidebar: Chart + Alert (1/3) --}}
        <div class="flex flex-col gap-5">
            {{-- Project Distribution Chart --}}
            <div class="card-industrial p-5">
                <h3 class="text-xs font-black text-slate-700 mb-6 uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                    Alokasi Project
                </h3>
                <div class="relative h-[220px]">
                    <canvas id="projectChart"></canvas>
                </div>
            </div>

            {{-- Critical Alert --}}
            <div class="rounded-xl p-5 border border-red-200 relative overflow-hidden" style="background: linear-gradient(135deg, #FEF2F2, #FFF1F2);">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-20 h-20 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L1 21h22L12 2zm0 3.45l8.27 14.3H3.73L12 5.45zM11 16h2v2h-2v-2zm0-7h2v5h-2V9z"/>
                    </svg>
                </div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
                    </span>
                    <p class="text-[10px] font-black text-red-600 uppercase tracking-widest">TINDAKAN DIPERLUKAN</p>
                </div>
                <p class="text-3xl font-black text-red-700 tabular-nums mb-1">{{ $downCount }}</p>
                <p class="text-xs text-red-600 font-bold uppercase tracking-tight">Unit sedang offline</p>
                @if($downCount > 0)
                <div class="mt-4 pt-4 border-t border-red-100">
                    <p class="text-[10px] text-red-500 font-bold uppercase leading-relaxed tracking-tight">
                        Segera koordinasi dengan tim maintenance untuk percepatan perbaikan unit down.
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('projectChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Main Dev', 'Sorlim', 'Big Fleet'],
                datasets: [{
                    label: 'Laporan',
                    data: [{{ $chartMainDev }}, {{ $chartSorlim }}, {{ $chartBigFleet }}],
                    backgroundColor: ['#4F46E5', '#6366F1', '#A78BFA'],
                    borderRadius: 4,
                    borderSkipped: false,
                    barThickness: 32,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleFont: { size: 10, weight: 'bold' },
                        bodyFont: { size: 12, weight: 'bold' },
                        padding: 10,
                        cornerRadius: 4,
                        displayColors: false
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        ticks: { precision: 0, font: { size: 10, weight: '600' }, color: '#94A3B8' }, 
                        grid: { color: '#F1F5F9', drawBorder: false } 
                    },
                    x: { 
                        ticks: { font: { size: 10, weight: '700' }, color: '#64748B' }, 
                        grid: { display: false } 
                    }
                }
            }
        });
    });
    </script>
    @endpush

@endsection
