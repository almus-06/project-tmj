@extends('layouts.mobile')

@section('title', 'Unit Monitoring')

@section('header')
    {{-- ═══ INDUSTRIAL HEADER CARD ═══════════════════════════════════════════ --}}
    <div class="w-full max-w-md relative overflow-hidden"
        style="background: #1E1B4B; border-radius: 0 0 20px 20px; border-bottom: 4px solid #6366F1;">

        {{-- Decorative circles --}}
        <div class="absolute -bottom-6 -left-6 w-32 h-32 rounded-full opacity-10" style="background: #FFFFFF;"></div>

        <div class="relative px-5 pt-8 pb-7">
            {{-- Top row: label + LIVE badge --}}
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l9 1m1-11h4l3 5v4h-7V5z" />
                    </svg>
                </div>
                <span class="text-xs font-bold uppercase tracking-widest" style="color: rgba(255,255,255,0.75);">Fleet
                    Management</span>
            </div>

            {{-- Title block --}}
            <h1 class="text-3xl font-black text-white tracking-tighter leading-tight mb-1 uppercase">Unit Monitoring</h1>
            <p class="text-[10px] font-black uppercase tracking-[0.2em]" style="color: rgba(255,255,255,0.5);">PT. TRI
                MACHMUD JAYA &mdash; Fleet Module</p>

            {{-- Date badge --}}
            <div class="mt-4">
                <div class="inline-flex items-center gap-1.5 rounded-2xl px-3.5 py-2.5"
                    style="background: rgba(255,255,255,0.15);">
                    <svg class="w-3.5 h-3.5" style="color: rgba(255,255,255,0.6);" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span id="header_date" class="text-white text-xs font-bold">—</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('fleet.store') }}" method="POST" id="unitForm" x-data="{
        telemetryType: '', 
        
        getTelemetry(jenis) {
            if (!jenis) return '';
            const j = jenis.toLowerCase();
            const kmList = ['wt', 'dt', 'dw', 'fuel truck', 'bus', 'low boy', 'lv'];
            const hmList = ['exca', 'grader', 'compactor', 'dozer'];
            
            if (kmList.some(k => j.includes(k))) return 'km';
            if (hmList.some(h => j.includes(h))) return 'hm';
            return 'km';
        },

        init() {
            @if($selectedUnitId || old('unit_id'))
                @php
                    $initUnit = $units->firstWhere('id', old('unit_id') ?? $selectedUnitId);
                @endphp
                @if($initUnit)
                    this.telemetryType = this.getTelemetry('{{ $initUnit->jenis_alat }}');
                @endif
            @endif
        }
    }">
        @csrf

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200">
                <div class="flex items-center gap-3 mb-2 text-red-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-bold text-sm">Gagal Menyimpan Data</span>
                </div>
                <ul class="list-disc list-inside text-xs text-red-500 font-medium space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ══════════════════════════════════════════ --}}
        {{-- SECTION 1: Vehicle Identity --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="section-card">
            <p class="section-label">
                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l9 1m1-11h4l3 5v4h-7V5z" />
                </svg>
                Vehicle Identity
            </p>

            <div x-data="{
                                                    search: '',
                                                    open: false,
                                                    isLocked: {{ $isLocked ? 'true' : 'false' }},
                                                    selectedName: '{{ (old('unit_id') ?? $selectedUnitId) ? $units->firstWhere('id', old('unit_id') ?? $selectedUnitId)->no_kendaraan . ' — ' . $units->firstWhere('id', old('unit_id') ?? $selectedUnitId)->jenis_alat : 'Pilih Nomor Unit' }}',
                                                    selectedId: '{{ old('unit_id') ?? $selectedUnitId ?? '' }}',
                                                    units: {{ $units->sortBy('no_kendaraan')->values()->map(function ($u) {
        return ['id' => $u->id, 'name' => $u->no_kendaraan . ' — ' . $u->jenis_alat, 'raw_no' => $u->no_kendaraan, 'jenis' => $u->jenis_alat]; })->toJson() }},
                                                    get filteredUnits() {
                                                        if (this.search === '') return this.units;
                                                        return this.units.filter(u => u.name.toLowerCase().includes(this.search.toLowerCase()));
                                                    },
                                                    selectUnit(u) {
                                                        if (this.isLocked) return;
                                                        this.selectedId = u.id;
                                                        this.selectedName = u.name;
                                                        this.search = '';
                                                        this.open = false;
                                                        this.telemetryType = this.getTelemetry(u.jenis);
                                                    }
                                                }">
                <label for="unit_search_input" class="field-label">Nomor Unit <span class="text-red-400">*</span></label>
                <div class="relative" @click.away="open = false; search = ''">
                    {{-- Hidden Real Input --}}
                    @if($isLocked)
                        <input type="hidden" name="unit_id" :value="selectedId">
                    @else
                        <input type="hidden" name="unit_id" :value="selectedId" required>
                    @endif

                    {{-- Trigger / Search Input --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg style="width:18px;height:18px;" :class="isLocked ? 'text-indigo-500' : 'text-slate-400'"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l9 1m1-11h4l3 5v4h-7V5z" />
                            </svg>
                        </div>
                        <input type="text" id="unit_search_input"
                            class="form-input-field pl-11 pr-10 cursor-pointer transition-colors"
                            :class="isLocked ? 'bg-indigo-50 border-indigo-200 text-indigo-700 font-bold' : ''"
                            :placeholder="selectedName" x-model="search" :disabled="isLocked"
                            @click="if(!isLocked){ open = true; }" @keydown.escape="open = false; search = ''"
                            autocomplete="off">
                        <div class="absolute inset-y-0 right-0 pr-2 flex items-center">
                            <template x-if="isLocked">
                                <div class="pr-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </template>
                            <template x-if="!isLocked">
                                <button type="button" @click="open = !open" tabindex="-1"
                                    class="text-slate-400 hover:text-slate-600 focus:outline-none p-2 rounded-full transition-colors">
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Dropdown Results --}}
                    <div x-show="open && !isLocked" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                        class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden"
                        style="max-height: 480px; display: none;">
                        <div class="custom-scrollbar" style="max-height: 380px; overflow-y: auto; overflow-x: hidden;">
                            <template x-for="u in filteredUnits" :key="u.id">
                                <div @click="selectUnit(u)"
                                    class="px-4 py-3 cursor-pointer hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0 flex flex-col">
                                    <span class="text-sm font-bold text-slate-700" x-text="u.raw_no"></span>
                                    <span class="text-[0.65rem] font-semibold text-slate-400 uppercase tracking-tight"
                                        x-text="u.jenis"></span>
                                </div>
                            </template>

                            <div x-show="filteredUnits.length === 0" class="px-4 py-8 text-center">
                                <svg class="w-10 h-10 text-slate-200 mx-auto mb-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l9 1m1-11h4l3 5v4h-7V5z" />
                                </svg>
                                <p class="text-xs font-bold text-slate-400">Unit tidak ditemukan</p>
                            </div>
                        </div>
                    </div>
                </div>
                @if($isLocked)
                    <p class="text-[10px] text-indigo-500 font-bold mt-1.5 flex items-center gap-1 uppercase tracking-wider">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        Auto-detected via QR Scan
                    </p>
                @endif
                @error('unit_id') <span class="text-xs text-red-500 font-semibold mt-1.5 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- SECTION 2: Personnel & Task --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="section-card">
            <p class="section-label">
                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Personnel &amp; Task
            </p>

            {{-- Operator Name (Employee) --}}
            <div class="mb-4" x-data="{
                search: '',
                open: false,
                selectedName: '{{ old('operator_id') ? $employees->firstWhere('id', old('operator_id'))->name : 'Pilih Nama Operator' }}',
                selectedId: '{{ old('operator_id', '') }}',
                employees: {{ $employees->map(function($emp) { return ['id' => $emp->id, 'name' => $emp->name, 'pos' => $emp->position]; })->toJson() }},
                get filteredEmployees() {
                    if (this.search === '') return this.employees;
                    return this.employees.filter(emp => emp.name.toLowerCase().startsWith(this.search.toLowerCase()));
                },
                selectEmployee(emp) {
                    this.selectedId = emp.id;
                    this.selectedName = emp.name;
                    this.search = '';
                    this.open = false;
                }
            }">
                <label for="employee_search_input" class="field-label">
                    Nama Operator <span class="text-red-400">*</span>
                </label>
                <div class="relative" @click.away="open = false; search = ''">
                    {{-- Hidden Real Input --}}
                    <input type="hidden" name="operator_id" :value="selectedId" required>

                    {{-- Trigger / Search Input --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4.5 h-4.5 text-slate-400" style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            id="employee_search_input"
                            class="form-input-field pl-11 pr-10 cursor-pointer"
                            :placeholder="selectedName"
                            x-model="search"
                            @click="open = true"
                            @keydown.escape="open = false; search = ''"
                            autocomplete="off"
                        >
                        <div class="absolute inset-y-0 right-0 pr-2 flex items-center">
                            <button type="button" @click="open = !open" tabindex="-1" class="text-slate-400 hover:text-slate-600 focus:outline-none p-2 rounded-full transition-colors">
                                <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Dropdown Results --}}
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                        class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden"
                        style="max-height: 480px; display: none;"
                    >
                        <div class="custom-scrollbar" style="max-height: 380px; overflow-y: auto; overflow-x: hidden;">
                            <template x-for="emp in filteredEmployees" :key="emp.id">
                                <div
                                    @click="selectEmployee(emp)"
                                    class="px-4 py-3 cursor-pointer hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0 flex flex-col"
                                >
                                    <span class="text-sm font-bold text-slate-700" x-text="emp.name"></span>
                                    <span class="text-[0.65rem] font-semibold text-slate-400 uppercase tracking-tight" x-text="emp.pos"></span>
                                </div>
                            </template>

                            <div x-show="filteredEmployees.length === 0" class="px-4 py-8 text-center">
                                <svg class="w-10 h-10 text-slate-200 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <p class="text-xs font-bold text-slate-400">Operator tidak ditemukan</p>
                            </div>
                        </div>
                    </div>
                </div>
                @error('operator_id') <span class="text-xs text-red-500 font-semibold mt-1.5 block flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</span> @enderror
            </div>

            {{-- Project --}}
            <div x-data="{
                search: '',
                open: false,
                selectedName: '{{ old('project_id') ? $projects->firstWhere('id', old('project_id'))->name : 'Pilih Nama Project' }}',
                selectedId: '{{ old('project_id', '') }}',
                projects: {{ $projects->map(function($p) { return ['id' => $p->id, 'name' => $p->name]; })->toJson() }},
                get filteredProjects() {
                    if (this.search === '') return this.projects;
                    return this.projects.filter(p => p.name.toLowerCase().includes(this.search.toLowerCase()));
                },
                selectProject(p) {
                    this.selectedId = p.id;
                    this.selectedName = p.name;
                    this.search = '';
                    this.open = false;
                }
            }">
                <label for="project_search_input" class="field-label">Project <span class="text-red-400">*</span></label>
                <div class="relative" @click.away="open = false; search = ''">
                    {{-- Hidden Real Input --}}
                    <input type="hidden" name="project_id" :value="selectedId" required>

                    {{-- Trigger / Search Input --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg style="width:18px;height:18px;" class="text-slate-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            id="project_search_input"
                            class="form-input-field pl-11 pr-10 cursor-pointer"
                            :placeholder="selectedName"
                            x-model="search"
                            @click="open = true"
                            @keydown.escape="open = false; search = ''"
                            autocomplete="off"
                        >
                        <div class="absolute inset-y-0 right-0 pr-2 flex items-center">
                            <button type="button" @click="open = !open" tabindex="-1" class="text-slate-400 hover:text-slate-600 focus:outline-none p-2 rounded-full transition-colors">
                                <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Dropdown Results --}}
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                        class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden"
                        style="max-height: 300px; display: none;"
                    >
                        <div class="custom-scrollbar" style="max-height: 250px; overflow-y: auto; overflow-x: hidden;">
                            <template x-for="p in filteredProjects" :key="p.id">
                                <div
                                    @click="selectProject(p)"
                                    class="px-4 py-3 cursor-pointer hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0 flex flex-col"
                                >
                                    <span class="text-sm font-bold text-slate-700" x-text="p.name"></span>
                                </div>
                            </template>

                            <div x-show="filteredProjects.length === 0" class="px-4 py-8 text-center">
                                <svg class="w-10 h-10 text-slate-200 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                <p class="text-xs font-bold text-slate-400">Project tidak ditemukan</p>
                            </div>
                        </div>
                    </div>
                </div>
                @error('project_id') <span class="text-xs text-red-500 font-semibold mt-1.5 block flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- SECTION 3: Condition & Telemetry --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="section-card">
            <p class="section-label">
                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                </svg>
                Condition &amp; Telemetry
            </p>

            {{-- Status Unit Selector --}}
            <div class="mb-4">
                <label class="field-label">Status Unit <span class="text-red-400">*</span></label>
                <input type="hidden" id="status" name="status" value="{{ old('status', 'Ready') }}">
                <div class="grid grid-cols-3 gap-3">
                    {{-- READY --}}
                    <button type="button" onclick="selectStatus('Ready')" id="btn_ready"
                        class="status-btn toggle-card flex flex-col items-center gap-2 py-4">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center" id="ready_icon_bg"
                            style="background: #F0FDF4;">
                            <svg class="w-4.5 h-4.5" id="ready_icon" style="width:18px;height:18px;color:#16A34A;"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="font-black text-xs" id="ready_label" style="color: #16A34A;">READY</p>
                    </button>
                    {{-- STANDBY --}}
                    <button type="button" onclick="selectStatus('Standby')" id="btn_standby"
                        class="status-btn toggle-card flex flex-col items-center gap-2 py-4">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center" id="standby_icon_bg"
                            style="background: #F8FAFC;">
                            <svg class="w-4.5 h-4.5" id="standby_icon" style="width:18px;height:18px;color:#CBD5E1;"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="font-black text-xs" id="standby_label" style="color: #94A3B8;">STANDBY</p>
                    </button>
                    {{-- DOWN --}}
                    <button type="button" onclick="selectStatus('Down')" id="btn_down"
                        class="status-btn toggle-card flex flex-col items-center gap-2 py-4">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center" id="down_icon_bg"
                            style="background: #F8FAFC;">
                            <svg class="w-4.5 h-4.5" id="down_icon" style="width:18px;height:18px;color:#CBD5E1;"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                        <p class="font-black text-xs" id="down_label" style="color: #94A3B8;">DOWN</p>
                    </button>
                </div>
            </div>

            {{-- Damage Description (conditionally shown) --}}
            <div id="damage_block" class="hidden">
                <div class="p-4 rounded-xl" style="background: #FFF1F2; border: 1px solid #FECACA;">
                    <label for="damage_type" class="field-label" style="color: #B91C1C;">
                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        REPAIR LOG / DAMAGE DESCRIPTION <span class="text-red-400">*</span>
                    </label>
                    <textarea id="damage_type" name="damage_type" rows="3" placeholder="Detail the issue..."
                        class="form-input-field @error('damage_type') border-red-400 @enderror"
                        style="background: #FFF1F2; border-color: #FECACA;">{{ old('damage_type') }}</textarea>
                    @error('damage_type') <span
                    class="text-xs text-red-500 font-semibold mt-1.5 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- SECTION 4: Location & Details --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="section-card">
            <p class="section-label">
                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Location &amp; Details
            </p>

            {{-- Lokasi --}}
            <div class="mb-4">
                <label for="location" class="field-label">Lokasi Terkini <span class="text-red-400">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg style="width:18px;height:18px;" class="text-slate-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <input type="text" id="location" name="location" required placeholder="Lokasi keandaraan saat ini"
                        value="{{ old('location') }}"
                        class="form-input-field pl-11 @error('location') border-red-400 @enderror">
                </div>
                @error('location') <span class="text-xs text-red-500 font-semibold mt-1.5 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- HM / KM --}}
            <div class="grid grid-cols-1 gap-3">
                <div x-show="telemetryType === 'hm'">
                    <label for="hm" class="field-label">Hour Meter <span class="text-slate-400 normal-case font-normal"
                            style="font-size:0.65rem;">(H/M)</span> <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <input type="number" step="0.1" id="hm" name="hm" :required="telemetryType === 'hm'" placeholder="H/M saat ini"
                            value="{{ old('hm') }}"
                            class="form-input-field font-black @error('hm') border-red-400 @enderror"
                            style="padding-right: 40px;">
                        <span
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 font-semibold pointer-events-none"
                            style="font-size:0.7rem;">H/M</span>
                    </div>
                    @error('hm') <span class="text-xs text-red-500 font-semibold mt-1.5 block">{{ $message }}</span>
                    @enderror
                </div>
                <div x-show="telemetryType === 'km'">
                    <label for="km" class="field-label">Kilometer <span class="text-slate-400 normal-case font-normal"
                            style="font-size:0.65rem;">(KM)</span> <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <input type="number" step="0.1" id="km" name="km" :required="telemetryType === 'km'" placeholder="KM saat ini"
                            value="{{ old('km') }}"
                            class="form-input-field font-black @error('km') border-red-400 @enderror"
                            style="padding-right: 40px;">
                        <span
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 font-semibold pointer-events-none"
                            style="font-size:0.7rem;">KM</span>
                    </div>
                    @error('km') <span class="text-xs text-red-500 font-semibold mt-1.5 block">{{ $message }}</span>
                    @enderror
                </div>
                {{-- Fallback message if no unit selected --}}
                <div x-show="telemetryType === ''" class="p-4 text-center border-2 border-dashed border-slate-100 rounded-xl">
                    <p class="text-xs font-bold text-slate-300 italic">Pilih nomor unit terlebih dahulu</p>
                </div>
            </div>
        </div>

    </form>

    {{-- ══════════════════════════════════════════ --}}
    {{-- STICKY SUBMIT BUTTON --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="sticky-submit-bar">
        <button type="submit" form="unitForm" id="submitBtn" class="submit-btn"
            style="background: #2563EB; box-shadow: 0 4px 12px rgba(37,99,235,0.2);">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            SUBMIT UNIT REPORT
        </button>
    </div>

    <script>
        // ─── Date Header ──────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            const now = new Date();
            const dd = String(now.getDate()).padStart(2, '0');
            const yyyy = now.getFullYear();
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            document.getElementById('header_date').textContent = `${dd} ${months[now.getMonth()]} ${yyyy}`;

            selectStatus('{{ old('status', 'Ready') }}');
        });

        // ─── Status Toggle ────────────────────────────────────────────────────
        const statusConfig = {
            Ready: { bg: '#F0FDF4', border: '#22C55E', iconBg: '#DCFCE7', iconColor: '#16A34A', labelColor: '#16A34A' },
            Standby: { bg: '#FFFBEB', border: '#F59E0B', iconBg: '#FEF3C7', iconColor: '#D97706', labelColor: '#B45309' },
            Down: { bg: '#FFF1F2', border: '#F87171', iconBg: '#FFE4E6', iconColor: '#EF4444', labelColor: '#DC2626' },
        };
        const statusInactive = { bg: '#F8FAFC', border: '#E2E8F0', iconBg: '#F8FAFC', iconColor: '#CBD5E1', labelColor: '#94A3B8' };

        function selectStatus(val) {
            document.getElementById('status').value = val;

            ['Ready', 'Standby', 'Down'].forEach(s => {
                const key = s.toLowerCase();
                const btn = document.getElementById('btn_' + key);
                const cfg = statusInactive;
                btn.style.background = cfg.bg;
                btn.style.borderColor = cfg.border;
                document.getElementById(key + '_icon_bg').style.background = cfg.iconBg;
                document.getElementById(key + '_icon').style.color = cfg.iconColor;
                document.getElementById(key + '_label').style.color = cfg.labelColor;
            });

            const key = val.toLowerCase();
            const cfg = statusConfig[val];
            const btn = document.getElementById('btn_' + key);
            btn.style.background = cfg.bg;
            btn.style.borderColor = cfg.border;
            document.getElementById(key + '_icon_bg').style.background = cfg.iconBg;
            document.getElementById(key + '_icon').style.color = cfg.iconColor;
            document.getElementById(key + '_label').style.color = cfg.labelColor;

            const block = document.getElementById('damage_block');
            const input = document.getElementById('damage_type');
            if (val === 'Down') {
                block.classList.remove('hidden');
                block.classList.add('slide-in');
                input.setAttribute('required', 'true');
            } else {
                block.classList.add('hidden');
                block.classList.remove('slide-in');
                input.removeAttribute('required');
            }
        }

        // ─── Submit Loading ───────────────────────────────────────────────────
        document.getElementById('unitForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = `<svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg> Menyimpan...`;
        });
    </script>
@endsection