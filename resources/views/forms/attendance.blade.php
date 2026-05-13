@extends('layouts.mobile')

@section('title', 'Attendance - TMJ')

@section('header')
{{-- ═══ INDUSTRIAL HEADER CARD ═══════════════════════════════════════════ --}}
<div class="w-full max-w-md relative overflow-hidden" style="background: #059669; border-radius: 0 0 20px 20px; border-bottom: 4px solid #047857;">

    {{-- Decorative circles --}}
    <div class="absolute -bottom-6 -left-6 w-32 h-32 rounded-full opacity-10" style="background: #FFFFFF;"></div>

    <div class="relative px-5 pt-8 pb-7">
        {{-- Top row: label + LIVE badge --}}
        <div class="flex items-center gap-4 mb-6">
            <div class="p-1.5 bg-white rounded-lg shadow-md border border-white/20">
                <img src="{{ asset('images/logo-tmj-full.png') }}" alt="Logo" class="h-8 w-auto object-contain">
            </div>
            <div>
                <span class="block text-[10px] font-black uppercase tracking-[0.2em] text-emerald-100/60">Portal</span>
                <span class="block text-sm font-black uppercase tracking-widest text-white leading-none">Absensi</span>
            </div>
        </div>

        {{-- Title block --}}
        <h1 class="text-3xl font-black text-white tracking-tighter leading-tight mb-1 uppercase">Absensi Karyawan</h1>
        <p class="text-[10px] font-black uppercase tracking-[0.2em]" style="color: rgba(255,255,255,0.5);">PT. TRI MACHMUD JAYA</p>

        {{-- Attendance Code + Date --}}
        <div class="mt-4 flex items-center gap-3">
            <div class="flex-1 flex items-center gap-2 rounded-2xl px-3.5 py-2.5" style="background: rgba(255,255,255,0.15);">
                <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: rgba(255,255,255,0.6);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                </svg>
                <span class="text-xs font-semibold" style="color: rgba(255,255,255,0.65);">Kode:</span>
                <span id="attendance_code_display" class="text-white text-xs font-black font-mono tracking-wide">—</span>
            </div>
            <div class="flex items-center gap-1.5 rounded-2xl px-3.5 py-2.5" style="background: rgba(255,255,255,0.15);">
                <svg class="w-3.5 h-3.5" style="color: rgba(255,255,255,0.6);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span id="header_date" class="text-white text-xs font-bold">—</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<form action="{{ route('attendance.store') }}" method="POST" id="attendanceForm">
    @csrf
    <input type="hidden" id="attendance_code_field" name="attendance_code">
    <input type="hidden" id="geo_latitude" name="latitude">
    <input type="hidden" id="geo_longitude" name="longitude">
    <input type="hidden" id="geo_accuracy" name="accuracy">
    <input type="hidden" id="geo_altitude" name="altitude">
    <input type="hidden" id="geo_heading" name="heading">
    <input type="hidden" id="geo_speed" name="speed">
    <input type="hidden" id="geo_device_info" name="device_info">

    {{-- ══════════════════════════════════════════ --}}
    {{-- SECTION 1: Employee Info                  --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="section-card">
        <p class="section-label green">
            <svg class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Employee Info
        </p>

        <div class="mb-4" x-data="{
            search: '',
            open: false,
            selectedName: '{{ old('employee_id') ? $employees->firstWhere('id', old('employee_id'))->name : 'Pilih Nama Karyawan' }}',
            selectedId: '{{ old('employee_id', '') }}',
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
                Nama Karyawan <span class="text-red-400">*</span>
            </label>
            <div class="relative" @click.away="open = false; search = ''">
                {{-- Hidden Real Input --}}
                <input type="hidden" name="employee_id" :value="selectedId" required>

                {{-- Trigger / Search Input --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4.5 h-4.5 text-slate-400" style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        id="employee_search_input"
                        class="form-input-field pl-11 pr-10 cursor-pointer"
                        :placeholder="selectedName"
                        x-model.debounce.300ms="search"
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
                            <p class="text-xs font-bold text-slate-400">Karyawan tidak ditemukan</p>
                        </div>
                    </div>
                </div>
            </div>
            @error('employee_id') <span class="text-xs text-red-500 font-semibold mt-1.5 block flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</span> @enderror
        </div>

        {{-- Shift / Project --}}
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
                
                // Re-calculate distance based on new project if location is already locked or tracking
                const lat = document.getElementById('geo_latitude').value;
                const lng = document.getElementById('geo_longitude').value;
                const acc = document.getElementById('geo_accuracy').value;
                if (lat && lng) {
                    updateGeoPreview(lat, lng, acc);
                }
            }
        }">
            <label for="project_search_input" class="field-label">
                Shift / Project <span class="text-red-400">*</span>
            </label>
            <div class="relative" @click.away="open = false; search = ''">
                {{-- Hidden Real Input --}}
                <input type="hidden" name="project_id" :value="selectedId" required>

                {{-- Trigger / Search Input --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg style="width:18px;height:18px;" class="text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        id="project_search_input"
                        class="form-input-field pl-11 pr-10 cursor-pointer"
                        :placeholder="selectedName"
                        x-model.debounce.300ms="search"
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
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
    {{-- SECTION 2: Health Check                   --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="section-card">
        <p class="section-label green">
            <svg class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            Health Check
        </p>

        {{-- Medical Metrics Grid --}}
        <div class="grid grid-cols-3 gap-2.5 mb-4">
            {{-- Blood Pressure --}}
            <div class="metric-card" id="bp_card">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: #FFF1F2;">
                    <svg class="w-4 h-4" style="color: #F43F5E;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <span class="text-center" style="font-size:0.6rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:0.08em;line-height:1.2;">Tensi<br><span style="color:#CBD5E1;font-weight:400;font-size:0.58rem;">(mmHg)</span></span>
                <input type="text" id="blood_pressure" name="blood_pressure" required
                    placeholder="120/80" value="{{ old('blood_pressure') }}"
                    onblur="checkBP(this)"
                    class="@error('blood_pressure') text-red-500 @enderror"
                    style="font-size:1rem;">
                <div id="bp_warning" class="hidden text-center" style="font-size:0.6rem;font-weight:700;color:#F43F5E;">⚠ Abnormal</div>
                @error('blood_pressure') <span class="text-red-500" style="font-size:0.6rem;font-weight:600;text-align:center;">{{ $message }}</span> @enderror
            </div>

            {{-- SpO2 --}}
            <div class="metric-card">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: #EFF6FF;">
                    <svg class="w-4 h-4" style="color: #3B82F6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="text-center" style="font-size:0.6rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:0.08em;line-height:1.2;">SpO2<br><span style="color:#CBD5E1;font-weight:400;font-size:0.58rem;">(%)</span></span>
                <input type="number" id="spo2" name="spo2" required
                    placeholder="98" min="0" max="100" value="{{ old('spo2') }}"
                    class="@error('spo2') text-red-500 @enderror">
                @error('spo2') <span class="text-red-500" style="font-size:0.6rem;font-weight:600;">{{ $message }}</span> @enderror
            </div>

            {{-- Temperature --}}
            <div class="metric-card">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: #FFFBEB;">
                    <svg class="w-4 h-4" style="color: #F59E0B;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <span class="text-center" style="font-size:0.6rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:0.08em;line-height:1.2;">Temp<br><span style="color:#CBD5E1;font-weight:400;font-size:0.58rem;">(°C)</span></span>
                <input type="number" step="0.1" id="temperature" name="temperature" required
                    placeholder="36.5" value="{{ old('temperature') }}"
                    class="@error('temperature') text-red-500 @enderror">
                @error('temperature') <span class="text-red-500" style="font-size:0.6rem;font-weight:600;">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- TAK Clearance --}}
        <div class="flex items-center gap-4 p-4 rounded-xl" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
            <input type="hidden" id="tak" name="tak" value="{{ old('tak', '1') }}">
            <button type="button" id="tak_toggle" onclick="toggleTAK()"
                class="flex items-center flex-shrink-0 w-12 h-6 rounded-full p-0.5 transition-all duration-300"
                style="background: #22C55E;">
                <span id="tak_knob" class="w-5 h-5 rounded-full bg-white shadow-sm transition-all duration-300" style="transform: translateX(24px);"></span>
            </button>
            <div>
                <p class="font-black text-slate-700 uppercase tracking-widest" style="font-size:0.75rem;">TAK STATUS</p>
                <p class="text-slate-400 font-bold uppercase tracking-tight" style="font-size:0.65rem;">Tidak Ada Kelainan Medis</p>
            </div>
        </div>

        {{-- Fit / Unfit --}}
        <div class="mt-4">
            <label class="field-label">Fit Status <span class="text-red-400">*</span></label>
            <input type="hidden" id="fit_status" name="fit_status" value="{{ old('fit_status', 'Fit') }}">
            <div class="grid grid-cols-2 gap-3">
                <button type="button" onclick="selectFit('Fit')" id="btn_fit"
                    class="toggle-card flex flex-col items-center gap-2 py-5">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center" id="fit_icon_bg" style="background: #F0FDF4;">
                        <svg class="w-5 h-5" id="fit_icon" style="color: #16A34A;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-black" id="fit_label" style="color: #16A34A;">FIT</p>
                        <p class="text-xs font-medium" id="fit_sub" style="color: #86EFAC;">Sehat</p>
                    </div>
                </button>
                <button type="button" onclick="selectFit('Unfit')" id="btn_unfit"
                    class="toggle-card flex flex-col items-center gap-2 py-5">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center" id="unfit_icon_bg" style="background: #F8FAFC;">
                        <svg class="w-5 h-5" id="unfit_icon" style="color: #CBD5E1;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-black" id="unfit_label" style="color: #94A3B8;">UNFIT</p>
                        <p class="text-xs font-medium" id="unfit_sub" style="color: #CBD5E1;">Tidak Sehat</p>
                    </div>
                </button>
            </div>
            @error('fit_status') <span class="text-xs text-red-500 font-semibold mt-2 block">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- SECTION 3: Lokasi Verifikasi              --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="section-card" id="geo_section">
        <p class="section-label green">
            <svg class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Lokasi Verifikasi
        </p>

        {{-- GPS Status Card --}}
        <div id="geo_card" class="rounded-2xl p-4 flex items-center gap-4 transition-all duration-300"
            style="background: #F8FAFC; border: 2px solid #E2E8F0;">

            {{-- Animated Icon --}}
            <div id="geo_icon_wrapper" class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                style="background: #F1F5F9;">
                {{-- Loading spinner (default) --}}
                <svg id="geo_icon_loading" class="w-5 h-5 text-slate-400 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                {{-- Success icon --}}
                <svg id="geo_icon_success" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="color: #16A34A;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                {{-- Warning icon --}}
                <svg id="geo_icon_warning" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="color: #DC2626;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                {{-- Error icon --}}
                <svg id="geo_icon_error" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="color: #94A3B8;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>

            {{-- Text Content --}}
            <div class="flex-1 min-w-0">
                <p id="geo_status_text" class="font-black text-sm text-slate-400">Mengambil lokasi...</p>
                <p id="geo_detail_text" class="text-[10px] font-bold text-slate-300 uppercase tracking-wider mt-0.5">Mohon izinkan akses lokasi</p>
            </div>

        </div>

        {{-- Accuracy Info (shown after location acquired) --}}
        <div id="geo_accuracy_row" class="hidden flex items-center justify-between mt-2 px-1">
            <span class="text-[10px] font-bold text-slate-300 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Akurasi GPS: <span id="geo_accuracy_text">—</span>
            </span>
            <button type="button" id="geo_retry_btn" class="hidden text-[10px] font-bold text-indigo-500 flex items-center gap-1 hover:text-indigo-700 transition-colors"
                onclick="requestGeolocation()">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Ulangi
            </button>
        </div>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- SECTION 4: Status Kehadiran               --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="section-card">
        <p class="section-label green">
            <svg class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            Status Kehadiran
        </p>
        <input type="hidden" id="presence_status" name="presence_status" value="{{ old('presence_status', 'Hadir') }}">
        <div class="grid grid-cols-3 gap-2.5" id="presence_group">
            @foreach([
                'Hadir'            => ['icon'=>'','color'=>'green','bg'=>'#F0FDF4','border'=>'#22C55E','text'=>'#15803D'],
                'Izin'             => ['icon'=>'','color'=>'amber','bg'=>'#FFFBEB','border'=>'#F59E0B','text'=>'#B45309'],
                'Cuti'             => ['icon'=>'','color'=>'sky','bg'=>'#F0F9FF','border'=>'#38BDF8','text'=>'#0369A1'],
                'Tidak Hadir'      => ['icon'=>'','color'=>'red','bg'=>'#FFF1F2','border'=>'#F87171','text'=>'#B91C1C'],
                'Tanpa Keterangan' => ['icon'=>'','color'=>'rose','bg'=>'#FFF1F2','border'=>'#FB7185','text'=>'#BE123C'],
            ] as $status => $cfg)
            <button type="button" onclick="selectPresence('{{ $status }}')"
                data-status="{{ $status }}"
                data-bg="{{ $cfg['bg'] }}"
                data-border="{{ $cfg['border'] }}"
                data-text="{{ $cfg['text'] }}"
                class="presence-btn flex flex-col items-center gap-1 py-3.5">
                <span class="text-xl leading-none">{{ $cfg['icon'] }}</span>
                <span class="text-center font-bold leading-tight" style="font-size:0.65rem;">{{ $status }}</span>
            </button>
            @endforeach
        </div>
        @error('presence_status') <span class="text-xs text-red-500 font-semibold mt-2 block">{{ $message }}</span> @enderror
    </div>

</form>

{{-- ══════════════════════════════════════════ --}}
{{-- STICKY SUBMIT BUTTON                       --}}
{{-- ══════════════════════════════════════════ --}}
<div class="sticky-submit-bar">
    <button type="submit" form="attendanceForm" id="submitBtn" class="submit-btn transition-all duration-300" style="background: #94A3B8; box-shadow: none; cursor: not-allowed;" disabled>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v5m0 0l-2-2m2 2l2-2M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
        </svg>
        <span id="submitBtnText">MENCARI LOKASI...</span>
    </button>
</div>

<script>
// ─── Init ─────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const now = new Date();
    const dd = String(now.getDate()).padStart(2,'0');
    const mm = String(now.getMonth()+1).padStart(2,'0');
    const yy = String(now.getFullYear()).slice(-2);
    const yyyy = now.getFullYear();
    // Show placeholder for sequential ID
    const codeDisplay = `FitToWork-TMJ-${dd}${mm}${yy}-XXXX`;
    document.getElementById('attendance_code_display').textContent = codeDisplay;
    // Backend will regenerate the actual unique code
    document.getElementById('attendance_code_field').value = `FitToWork-TMJ-${dd}${mm}${yy}`;

    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    document.getElementById('header_date').textContent = `${dd} ${months[now.getMonth()]} ${yyyy}`;

    selectPresence('{{ old('presence_status', 'Hadir') }}');
    selectFit('{{ old('fit_status', 'Fit') }}');
    const takVal = '{{ old('tak', '1') }}';
    if (takVal !== '1') setTAK(false);

    // Auto-request geolocation on page load
    requestGeolocation();
});

// ─── Geolocation ──────────────────────────────────────────────────────

function setGeoUI(state, accuracy) {
    const card = document.getElementById('geo_card');
    const iconWrapper = document.getElementById('geo_icon_wrapper');
    const statusText = document.getElementById('geo_status_text');
    const detailText = document.getElementById('geo_detail_text');
    const accRow = document.getElementById('geo_accuracy_row');
    const accText = document.getElementById('geo_accuracy_text');
    const retryBtn = document.getElementById('geo_retry_btn');
    
    // Submit Button
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');

    ['loading','success','error'].forEach(id => {
        const el = document.getElementById('geo_icon_' + id);
        if(el) el.classList.add('hidden');
    });

    // Reset animations
    const loadingIcon = document.getElementById('geo_icon_loading');
    if (loadingIcon) loadingIcon.classList.remove('animate-spin');
    card.classList.remove('animate-pulse');

    if (state === 'loading') {
        if(loadingIcon) {
            loadingIcon.classList.remove('hidden');
            loadingIcon.classList.add('animate-spin'); 
            loadingIcon.style.color = '#2563EB'; 
        }
        
        card.style.background = '#EFF6FF'; 
        card.style.borderColor = '#3B82F6'; 
        iconWrapper.style.background = '#DBEAFE'; 
        
        statusText.textContent = 'Mencari Satelit GPS...';
        statusText.style.color = '#1D4ED8';
        
        if (accuracy) {
            detailText.textContent = `Sedang menstabilkan (Akurasi: ±${Math.round(accuracy)}m)`;
            card.classList.add('animate-pulse');
        } else {
            detailText.textContent = 'Memindai lokasi Anda...';
        }
        detailText.style.color = '#3B82F6';
        
        accRow.classList.add('hidden');
        
        // Lock Button
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.style.background = '#94A3B8';
            submitBtn.style.boxShadow = 'none';
            submitBtn.style.cursor = 'not-allowed';
            if(submitBtnText) submitBtnText.textContent = 'MENCARI LOKASI...';
        }
    } else if (state === 'locked') {
        const successIcon = document.getElementById('geo_icon_success');
        if (successIcon) successIcon.classList.remove('hidden');
        
        card.style.background = '#F0FDF4'; 
        card.style.borderColor = '#22C55E';
        iconWrapper.style.background = '#DCFCE7';
        
        statusText.textContent = 'Lokasi GPS Terkunci';
        statusText.style.color = '#16A34A';
        
        let signalQuality = 'Sinyal Buruk';
        if (accuracy < 10) signalQuality = 'Sangat Baik';
        else if (accuracy <= 20) signalQuality = 'Baik';
        else if (accuracy <= 50) signalQuality = 'Cukup';
        
        detailText.textContent = `Siap dikirim ke server • Sinyal: ${signalQuality}`;
        detailText.style.color = '#4ADE80';
        
        accRow.classList.remove('hidden');
        accText.textContent = '±' + Math.round(accuracy) + 'm';
        retryBtn.classList.remove('hidden');
        
        // Unlock Button
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.style.background = '#059669';
            submitBtn.style.boxShadow = '0 4px 12px rgba(5,150,105,0.2)';
            submitBtn.style.cursor = 'pointer';
            if(submitBtnText) submitBtnText.textContent = 'MASUKAN ABSENSI';
        }
    } else if (state === 'error') {
        const errorIcon = document.getElementById('geo_icon_error');
        if (errorIcon) errorIcon.classList.remove('hidden');
        
        card.style.background = '#F8FAFC'; card.style.borderColor = '#E2E8F0';
        iconWrapper.style.background = '#F1F5F9';
        statusText.textContent = 'Gagal Mengunci Lokasi';
        statusText.style.color = '#64748B';
        detailText.textContent = 'Pastikan GPS & izin lokasi aktif';
        detailText.style.color = '#94A3B8';
        accRow.classList.remove('hidden');
        accText.textContent = 'N/A';
        retryBtn.classList.remove('hidden');
        
        // Error Button (Stay locked)
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.style.background = '#EF4444'; // Red to indicate error
            submitBtn.style.boxShadow = 'none';
            submitBtn.style.cursor = 'not-allowed';
            if(submitBtnText) submitBtnText.textContent = 'LOKASI GAGAL (COBA LAGI)';
        }
    }
}

const geoTracker = {
    watchId: null,
    bestPos: null,
    stableCount: 0,
    startTime: 0,
    MAX_WAIT_TIME: 30000, // 30 detik waktu tunggu maksimal (sebelumnya 15s)
    DESIRED_ACCURACY: 20 // Akurasi 20 meter dianggap sangat baik
};

function processLocationSample(pos) {
    const acc = pos.coords.accuracy;
    const timeElapsed = Date.now() - geoTracker.startTime;
    
    // Update loading UI dengan akurasi sementara
    if (geoTracker.stableCount === 0) {
        setGeoUI('loading', 0, acc);
    }

    // Simpan posisi terbaik sejauh ini
    if (!geoTracker.bestPos || acc < geoTracker.bestPos.coords.accuracy) {
        geoTracker.bestPos = pos;
    }

    // Cek stabilitas (akurasi < 20m)
    if (acc <= geoTracker.DESIRED_ACCURACY) {
        geoTracker.stableCount++;
    } else {
        // Reset jika melompat ke akurasi buruk lagi
        if (acc > geoTracker.DESIRED_ACCURACY * 2) {
            geoTracker.stableCount = 0; 
        }
    }

    // Lock jika sudah stabil 3x ATAU waktu maksimal terlampaui
    if (geoTracker.stableCount >= 3 || timeElapsed >= geoTracker.MAX_WAIT_TIME) {
        lockLocation(geoTracker.bestPos);
    }
}

function lockLocation(pos) {
    if (geoTracker.watchId) {
        navigator.geolocation.clearWatch(geoTracker.watchId);
        geoTracker.watchId = null;
    }
    
    if (!pos) {
        setGeoUI('error');
        return;
    }

    // Set Data ke Hidden Inputs
    document.getElementById('geo_latitude').value = pos.coords.latitude;
    document.getElementById('geo_longitude').value = pos.coords.longitude;
    document.getElementById('geo_accuracy').value = pos.coords.accuracy;
    document.getElementById('geo_altitude').value = pos.coords.altitude || '';
    document.getElementById('geo_heading').value = pos.coords.heading || '';
    document.getElementById('geo_speed').value = pos.coords.speed || '';
    document.getElementById('geo_device_info').value = navigator.userAgent;

    setGeoUI('locked', pos.coords.accuracy);
}

function requestGeolocation() {
    if (!navigator.geolocation) { 
        setGeoUI('error'); 
        return; 
    }
    
    if (geoTracker.watchId) navigator.geolocation.clearWatch(geoTracker.watchId);
    
    geoTracker.bestPos = null;
    geoTracker.stableCount = 0;
    geoTracker.startTime = Date.now();
    
    setGeoUI('loading');

    // Gunakan watchPosition untuk sampling berkali-kali (High Accuracy)
    geoTracker.watchId = navigator.geolocation.watchPosition(
        processLocationSample,
        function (err) { 
            console.warn("High accuracy Geo error: ", err);
            if (geoTracker.watchId) {
                navigator.geolocation.clearWatch(geoTracker.watchId);
                geoTracker.watchId = null;
            }

            // FALLBACK: Jika gagal mengunci GPS presisi tinggi (misal di dalam gedung/baterai hemat),
            // coba satu kali tembakan cepat dengan akurasi rendah (WiFi/BTS)
            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    console.log("Fallback location found.");
                    lockLocation(pos);
                },
                function(fallbackErr) {
                    console.error("Fallback error: ", fallbackErr);
                    // Jika ada bestPos dari sampel sebelumnya, pakai itu. Jika tidak, pasrah (error).
                    if (geoTracker.bestPos) {
                        lockLocation(geoTracker.bestPos);
                    } else {
                        setGeoUI('error'); 
                    }
                },
                { enableHighAccuracy: false, timeout: 10000, maximumAge: 60000 }
            );
        },
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
    );
    
    // Safety Fallback: paksa berhenti setelah waktu maksimal + buffer
    setTimeout(() => {
        if (geoTracker.watchId) {
            lockLocation(geoTracker.bestPos);
        }
    }, geoTracker.MAX_WAIT_TIME + 2000);
}

// ─── Presence Toggle ──────────────────────────────────────────────────
function selectPresence(val) {
    document.getElementById('presence_status').value = val;
    document.querySelectorAll('.presence-btn').forEach(btn => {
        btn.style.background = '#F8FAFC';
        btn.style.borderColor = '#E2E8F0';
        btn.style.color = '#94A3B8';
        btn.querySelector('span:last-child').style.color = '#94A3B8';
    });
    const active = document.querySelector(`.presence-btn[data-status="${val}"]`);
    if (active) {
        active.style.background = active.dataset.bg;
        active.style.borderColor = active.dataset.border;
        active.style.color = active.dataset.text;
        active.querySelector('span:last-child').style.color = active.dataset.text;
    }
}

// ─── Fit Toggle ───────────────────────────────────────────────────────
function selectFit(val) {
    document.getElementById('fit_status').value = val;

    const fitBtn    = document.getElementById('btn_fit');
    const unfitBtn  = document.getElementById('btn_unfit');

    if (val === 'Fit') {
        // Active FIT
        fitBtn.style.background     = '#F0FDF4';
        fitBtn.style.borderColor    = '#22C55E';
        document.getElementById('fit_icon_bg').style.background = '#DCFCE7';
        document.getElementById('fit_icon').style.color   = '#16A34A';
        document.getElementById('fit_label').style.color  = '#16A34A';
        document.getElementById('fit_sub').style.color    = '#4ADE80';
        // Inactive UNFIT
        unfitBtn.style.background    = '#F8FAFC';
        unfitBtn.style.borderColor   = '#E2E8F0';
        document.getElementById('unfit_icon_bg').style.background = '#F8FAFC';
        document.getElementById('unfit_icon').style.color   = '#CBD5E1';
        document.getElementById('unfit_label').style.color  = '#94A3B8';
        document.getElementById('unfit_sub').style.color    = '#CBD5E1';
    } else {
        // Inactive FIT
        fitBtn.style.background     = '#F8FAFC';
        fitBtn.style.borderColor    = '#E2E8F0';
        document.getElementById('fit_icon_bg').style.background = '#F8FAFC';
        document.getElementById('fit_icon').style.color   = '#CBD5E1';
        document.getElementById('fit_label').style.color  = '#94A3B8';
        document.getElementById('fit_sub').style.color    = '#CBD5E1';
        // Active UNFIT
        unfitBtn.style.background    = '#FFF1F2';
        unfitBtn.style.borderColor   = '#F87171';
        document.getElementById('unfit_icon_bg').style.background = '#FFE4E6';
        document.getElementById('unfit_icon').style.color   = '#EF4444';
        document.getElementById('unfit_label').style.color  = '#DC2626';
        document.getElementById('unfit_sub').style.color    = '#FCA5A5';
    }
}

// ─── TAK Toggle ───────────────────────────────────────────────────────
function setTAK(checked) {
    document.getElementById('tak').value = checked ? '1' : '0';
    const btn  = document.getElementById('tak_toggle');
    const knob = document.getElementById('tak_knob');
    const wrap = btn.parentElement; // Ambil parent langsung (div kartu)
    
    if (checked) {
        btn.style.background = '#22C55E';
        knob.style.transform = 'translateX(24px)';
        wrap.style.background   = '#F8FAFC';
        wrap.style.borderColor  = '#E2E8F0';
    } else {
        btn.style.background = '#94A3B8';
        knob.style.transform = 'translateX(2px)';
        wrap.style.background  = '#F8FAFC';
        wrap.style.borderColor = '#E2E8F0';
    }
}
function toggleTAK() {
    setTAK(document.getElementById('tak').value !== '1');
}

// ─── Blood Pressure Validator ─────────────────────────────────────────
function checkBP(input) {
    const val = input.value.trim();
    const warning = document.getElementById('bp_warning');
    const card    = document.getElementById('bp_card');
    card.style.borderColor = '#E8EDF3';
    warning.classList.add('hidden');
    if (val.includes('/')) {
        const [s,d] = val.split('/').map(Number);
        if (!isNaN(s) && !isNaN(d)) {
            if (s < 90 || s > 130 || d < 60 || d > 85) {
                card.style.borderColor = '#F87171';
                input.style.color = '#EF4444';
                warning.classList.remove('hidden');
            } else {
                card.style.borderColor = '#4ADE80';
                input.style.color = '#16A34A';
            }
        }
    }
}

// ─── Submit Loading & Validation ──────────────────────────────────────
document.getElementById('attendanceForm').addEventListener('submit', function(e) {
    // Validate Hidden Fields
    const employeeId = this.querySelector('input[name="employee_id"]').value;
    const projectId = this.querySelector('input[name="project_id"]').value;

    if (!employeeId || !projectId) {
        e.preventDefault();
        alert('PERINGATAN: Mohon pastikan Anda telah memilih Nama Karyawan dan Project dari daftar yang tersedia.');
        return false;
    }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = `<svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
    </svg> Menyimpan...`;
});
</script>
@endsection
