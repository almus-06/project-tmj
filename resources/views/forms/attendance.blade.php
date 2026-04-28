@extends('layouts.mobile')

@section('title', 'Fit To Work')

@section('header')
{{-- ═══ INDUSTRIAL HEADER CARD ═══════════════════════════════════════════ --}}
<div class="w-full max-w-md relative overflow-hidden" style="background: #16A34A; border-radius: 0 0 20px 20px; border-bottom: 4px solid #15803D;">

    {{-- Decorative circles --}}
    <div class="absolute -bottom-6 -left-6 w-32 h-32 rounded-full opacity-10" style="background: #FFFFFF;"></div>

    <div class="relative px-5 pt-8 pb-7">
        {{-- Top row: label + LIVE badge --}}
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <span class="text-xs font-bold uppercase tracking-widest" style="color: rgba(255,255,255,0.75);">Fit To Work</span>
        </div>

        {{-- Title block --}}
        <h1 class="text-3xl font-black text-white tracking-tighter leading-tight mb-1 uppercase">Fit To Work</h1>
        <p class="text-[10px] font-black uppercase tracking-[0.2em]" style="color: rgba(255,255,255,0.5);">PT. TRI MACHMUD JAYA &mdash; Operations Module</p>

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
                <p class="text-slate-400 font-bold uppercase tracking-tight" style="font-size:0.65rem;">No medical abnormality</p>
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
                        <p class="text-xs font-medium" id="fit_sub" style="color: #86EFAC;">Layak Bekerja</p>
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
                        <p class="text-xs font-medium" id="unfit_sub" style="color: #CBD5E1;">Tidak Layak</p>
                    </div>
                </button>
            </div>
            @error('fit_status') <span class="text-xs text-red-500 font-semibold mt-2 block">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- SECTION 3: Status Kehadiran               --}}
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
    <button type="submit" form="attendanceForm" id="submitBtn" class="submit-btn" style="background: #16A34A; box-shadow: 0 4px 12px rgba(22,163,74,0.2);">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v5m0 0l-2-2m2 2l2-2M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
        </svg>
        REGISTER ATTENDANCE
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
});

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
    const wrap = btn.closest('.flex.items-center');
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

// ─── Submit Loading ───────────────────────────────────────────────────
document.getElementById('attendanceForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = `<svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
    </svg> Menyimpan...`;
});
</script>
@endsection
