@extends('layouts.mobile')

@section('title', 'Attendance - TMJ')

@section('header')
{{-- ═══ INDUSTRIAL HEADER CARD ═══════════════════════════════════════════ --}}
<div class="w-full max-w-md relative overflow-hidden" id="header_container" style="background: #059669; border-radius: 0 0 20px 20px; border-bottom: 4px solid #047857;">

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
<form action="{{ route('attendance.store') }}" method="POST" id="attendanceForm" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="attendance_code_field" name="attendance_code">
    <input type="hidden" id="photo_base64" name="photo_base64">
    <input type="hidden" id="geo_latitude" name="latitude">
    <input type="hidden" id="geo_longitude" name="longitude">
    <input type="hidden" id="geo_accuracy" name="accuracy">
    <input type="hidden" id="geo_altitude" name="altitude">
    <input type="hidden" id="geo_heading" name="heading">
    <input type="hidden" id="geo_speed" name="speed">
    <input type="hidden" id="geo_device_info" name="device_info">
    <input type="hidden" id="attendance_type" name="type" value="clock_in">
    <input type="hidden" id="device_fingerprint" name="device_fingerprint">
    <input type="hidden" id="ip_address" name="ip_address">

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
                if (typeof window.checkEmployeeStatus === 'function') {
                    window.checkEmployeeStatus(emp.id);
                }
            },
            init() {
                if (this.selectedId) {
                    this.$nextTick(() => {
                        if (typeof window.checkEmployeeStatus === 'function') {
                            window.checkEmployeeStatus(this.selectedId);
                        }
                    });
                }
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
    {{-- SECTION 3.5: Foto Verifikasi              --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="section-card" id="photo_section">
        <p class="section-label green">
            <svg class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Foto Verifikasi <span class="text-red-400">*</span>
        </p>

        {{-- Camera Live Feed & Preview Box --}}
        <div class="relative overflow-hidden rounded-2xl border-2 border-slate-200 bg-slate-50 flex flex-col items-center justify-center min-h-[220px]" id="camera_container">
            {{-- Video Stream for WebRTC --}}
            <video id="camera_video" autoplay playsinline muted class="w-full h-56 object-cover hidden rounded-2xl"></video>
            
            {{-- Static Preview Image of Captured Photo --}}
            <img id="camera_preview_img" class="w-full h-56 object-cover hidden rounded-2xl" alt="Preview Foto">

            {{-- Placeholder View before camera started --}}
            <div id="camera_placeholder" class="flex flex-col items-center justify-center p-6 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-slate-400 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                    </svg>
                </div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Ambil Foto Selfie</p>
                <p class="text-[10px] text-slate-400 font-medium max-w-[250px] leading-relaxed">Silakan aktifkan kamera depan untuk memvalidasi kehadiran Anda.</p>
            </div>

            {{-- Fallback File Upload Area (if camera blocked or WebRTC unsupported) --}}
            <div id="fallback_container" class="hidden flex flex-col items-center justify-center p-6 text-center w-full">
                <input type="file" id="photo_file" name="photo_file" accept="image/*" capture="user" class="hidden" onchange="handleFallbackFile(this)">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center mb-3 border border-amber-100">
                    <svg class="w-8 h-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <p class="text-xs font-bold text-amber-600 uppercase tracking-wider mb-1">Kamera Web Ditolak / Tidak Didukung</p>
                <p class="text-[10px] text-slate-400 font-medium max-w-[250px] leading-relaxed mb-3">Tekan tombol di bawah untuk mengambil foto selfie menggunakan aplikasi kamera bawaan perangkat Anda.</p>
                <button type="button" onclick="triggerFallbackUpload()" class="px-4 py-2 bg-amber-500 text-white rounded-xl text-xs font-bold shadow-sm hover:bg-amber-600 transition-all">
                    BUKA KAMERA PERANGKAT
                </button>
            </div>
        </div>

        {{-- Camera Action Buttons --}}
        <div class="mt-3 flex gap-2" id="camera_controls">
            <button type="button" id="btn_start_camera" onclick="startCamera()" class="flex-1 py-3 px-4 rounded-xl border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 text-xs font-bold flex items-center justify-center gap-1.5 transition-all">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                AKTIFKAN KAMERA
            </button>
            <button type="button" id="btn_capture_photo" onclick="capturePhoto()" class="flex-1 py-3 px-4 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 text-xs font-bold flex items-center justify-center gap-1.5 transition-all hidden">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                </svg>
                AMBIL FOTO
            </button>
            <button type="button" id="btn_retake_photo" onclick="retakePhoto()" class="flex-1 py-3 px-4 rounded-xl border border-rose-200 text-rose-600 bg-rose-50 hover:bg-rose-100 text-xs font-bold flex items-center justify-center gap-1.5 transition-all hidden">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                FOTO ULANG
            </button>
        </div>
        
        @error('photo_file') 
            <span class="text-xs text-red-500 font-semibold mt-2 block flex items-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </span> 
        @enderror
    </div>

    {{-- Hidden presence status input defaulting to 'Hadir' --}}
    <input type="hidden" id="presence_status" name="presence_status" value="Hadir">

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
// Global States
let gpsLocked = false;
let gpsAccuracy = 0;

// ─── Init ─────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Generate / Retrieve Device Fingerprint UUID
    let fp = localStorage.getItem('tmj_device_fp');
    if (!fp) {
        fp = 'fp-' + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
        localStorage.setItem('tmj_device_fp', fp);
    }
    document.getElementById('device_fingerprint').value = fp;

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
        gpsLocked = false;
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
        validateFormReadyForSubmit();
    } else if (state === 'locked') {
        gpsLocked = true;
        gpsAccuracy = accuracy;
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
        validateFormReadyForSubmit();
    } else if (state === 'error') {
        gpsLocked = false;
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
        validateFormReadyForSubmit();
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
    const input = document.getElementById('presence_status');
    if (input) input.value = val;
}



// ─── Camera / Photo Verification ──────────────────────────────────────
let cameraStream = null;

function startCamera() {
    const video = document.getElementById('camera_video');
    const placeholder = document.getElementById('camera_placeholder');
    const previewImg = document.getElementById('camera_preview_img');
    const fallbackContainer = document.getElementById('fallback_container');
    const btnStart = document.getElementById('btn_start_camera');
    const btnCapture = document.getElementById('btn_capture_photo');
    const btnRetake = document.getElementById('btn_retake_photo');

    // Reset previous states
    previewImg.classList.add('hidden');
    previewImg.src = '';
    document.getElementById('photo_base64').value = '';
    document.getElementById('photo_file').value = '';

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        console.warn("WebRTC tidak didukung oleh browser ini.");
        switchToFallback();
        return;
    }

    navigator.mediaDevices.getUserMedia({ 
        video: { 
            facingMode: 'user',
            width: { ideal: 640 },
            height: { ideal: 480 }
        } 
    })
    .then(function(stream) {
        cameraStream = stream;
        video.srcObject = stream;
        video.classList.remove('hidden');
        placeholder.classList.add('hidden');
        fallbackContainer.classList.add('hidden');
        
        btnStart.classList.add('hidden');
        btnCapture.classList.remove('hidden');
        btnRetake.classList.add('hidden');
    })
    .catch(function(err) {
        console.error("Gagal mengakses kamera: ", err);
        switchToFallback();
    });
}

function capturePhoto() {
    const video = document.getElementById('camera_video');
    const previewImg = document.getElementById('camera_preview_img');
    const btnCapture = document.getElementById('btn_capture_photo');
    const btnRetake = document.getElementById('btn_retake_photo');

    if (!video.srcObject) return;

    // Create canvas dynamically for compression
    const canvas = document.createElement('canvas');
    const maxW = 640;
    const maxH = 480;
    
    let w = video.videoWidth || 640;
    let h = video.videoHeight || 480;

    // Maintain aspect ratio, downscale if too large
    if (w > maxW) {
        h = Math.round((h * maxW) / w);
        w = maxW;
    } else if (h > maxH) {
        w = Math.round((w * maxH) / h);
        h = maxH;
    }

    canvas.width = w;
    canvas.height = h;

    const ctx = canvas.getContext('2d');
    
    // Draw mirrored image if capturing from front camera (looks more natural)
    ctx.translate(w, 0);
    ctx.scale(-1, 1);
    ctx.drawImage(video, 0, 0, w, h);
    
    // Reset translation
    ctx.setTransform(1, 0, 0, 1, 0, 0);

    // Compress to JPEG with 0.8 quality (~50KB-100KB)
    const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
    
    // Store in hidden field
    document.getElementById('photo_base64').value = dataUrl;
    
    // Set preview
    previewImg.src = dataUrl;
    previewImg.classList.remove('hidden');
    video.classList.add('hidden');

    // Controls
    btnCapture.classList.add('hidden');
    btnRetake.classList.remove('hidden');

    // Stop camera stream to save power and battery
    stopCameraStream();
}

function retakePhoto() {
    // Clear inputs
    document.getElementById('photo_base64').value = '';
    document.getElementById('photo_file').value = '';
    document.getElementById('camera_preview_img').src = '';
    document.getElementById('camera_preview_img').classList.add('hidden');

    // If fallback is currently visible or active, keep it visible
    const fallbackContainer = document.getElementById('fallback_container');
    if (!fallbackContainer.classList.contains('hidden')) {
        document.getElementById('camera_placeholder').classList.add('hidden');
        document.getElementById('btn_retake_photo').classList.add('hidden');
        return;
    }

    // Restart camera
    startCamera();
}

function stopCameraStream() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
    }
}

function switchToFallback() {
    stopCameraStream();
    document.getElementById('camera_video').classList.add('hidden');
    document.getElementById('camera_preview_img').classList.add('hidden');
    document.getElementById('camera_placeholder').classList.add('hidden');
    document.getElementById('fallback_container').classList.remove('hidden');
    
    document.getElementById('btn_start_camera').classList.add('hidden');
    document.getElementById('btn_capture_photo').classList.add('hidden');
    document.getElementById('btn_retake_photo').classList.add('hidden');
}

function triggerFallbackUpload() {
    document.getElementById('photo_file').click();
}

function handleFallbackFile(input) {
    const file = input.files[0];
    if (file) {
        if (file.size > 5 * 1024 * 1024) {
            alert("File terlalu besar. Maksimum ukuran file foto adalah 5MB.");
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('camera_preview_img');
            previewImg.src = e.target.result;
            previewImg.classList.remove('hidden');
            document.getElementById('camera_placeholder').classList.add('hidden');
            document.getElementById('photo_base64').value = '';

            document.getElementById('btn_start_camera').classList.add('hidden');
            document.getElementById('btn_capture_photo').classList.add('hidden');
            document.getElementById('btn_retake_photo').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}



document.getElementById('attendanceForm').addEventListener('submit', function(e) {
    // Validate Hidden Fields
    const employeeId = this.querySelector('input[name="employee_id"]').value;
    const projectId = this.querySelector('input[name="project_id"]').value;

    if (!employeeId || !projectId) {
        e.preventDefault();
        alert('PERINGATAN: Mohon pastikan Anda telah memilih Nama Karyawan dan Project dari daftar yang tersedia.');
        return false;
    }

    const attendanceType = document.getElementById('attendance_type').value;

    // Photo validation for presence status 'Hadir' (Only required for Clock-In)
    if (attendanceType !== 'clock_out') {
        const presenceStatus = document.getElementById('presence_status').value;
        const photoBase64 = document.getElementById('photo_base64').value;
        const photoFileEl = document.getElementById('photo_file');
        const photoFile = photoFileEl ? photoFileEl.files.length : 0;
        
        if (presenceStatus === 'Hadir' && !photoBase64 && !photoFile) {
            e.preventDefault();
            alert('PERINGATAN: Foto verifikasi selfie wajib diambil sebelum mengirim absensi.');
            document.getElementById('photo_section').scrollIntoView({ behavior: 'smooth' });
            return false;
        }
    }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = `<svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
    </svg> Menyimpan...`;
});

// ─── Attendance Status & Theme Control ───────────────────────────────────
function checkEmployeeStatus(employeeId) {
    const fp = localStorage.getItem('tmj_device_fp') || '';
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const headerContainer = document.getElementById('header_container');
    const photoSection = document.getElementById('photo_section');
    const attendanceType = document.getElementById('attendance_type');
    
    // Clear any existing alert/overlay messages
    const existingMsg = document.getElementById('status_alert_msg');
    if (existingMsg) {
        existingMsg.remove();
    }

    if (!employeeId) {
        resetFormState();
        return;
    }

    // Set loading state
    submitBtn.disabled = true;
    submitBtn.style.background = '#94A3B8';
    submitBtn.style.boxShadow = 'none';
    submitBtn.style.cursor = 'wait';
    submitBtnText.textContent = 'MEMERIKSA STATUS ABSENSI...';

    fetch(`/attendance/check-status?employee_id=${employeeId}&device_fingerprint=${fp}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'device_blocked') {
                showStatusAlert(data.message, 'error');
                photoSection.style.display = 'none';
                validateFormReadyForSubmit();
            } else if (data.status === 'already_completed') {
                showStatusAlert(data.message, 'info');
                photoSection.style.display = 'none';
                validateFormReadyForSubmit();
            } else if (data.status === 'can_clock_out') {
                attendanceType.value = 'clock_out';
                
                // Ubah Tampilan ke Tema Amber
                headerContainer.style.background = '#D97706'; // Amber 600
                headerContainer.style.borderBottomColor = '#B45309'; // Amber 700
                
                photoSection.style.display = 'none';
                
                validateFormReadyForSubmit();
            } else {
                attendanceType.value = 'clock_in';
                
                // Reset ke Tema Hijau
                headerContainer.style.background = '#059669'; // Green 600
                headerContainer.style.borderBottomColor = '#047857'; // Green 700
                
                photoSection.style.display = 'block';
                
                validateFormReadyForSubmit();
            }
        })
        .catch(err => {
            console.error('Error checking attendance status:', err);
            submitBtnText.textContent = 'GAGAL MEMERIKSA STATUS';
        });
}

function showStatusAlert(message, type) {
    const parent = document.getElementById('attendanceForm');
    const alertDiv = document.createElement('div');
    alertDiv.id = 'status_alert_msg';
    alertDiv.className = 'section-card p-4 mb-4 rounded-2xl border flex items-start gap-3 transition-all duration-300';
    
    if (type === 'error') {
        alertDiv.style.background = '#FEF2F2';
        alertDiv.style.borderColor = '#FCA5A5';
        alertDiv.innerHTML = `
            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 text-red-500" style="display:flex;align-items:center;justify-content:center;">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <p class="font-black text-red-700 uppercase tracking-widest text-[11px]">KEAMANAN ABSENSI</p>
                <p class="text-red-500 font-bold text-[10px] mt-0.5">${message}</p>
            </div>
        `;
    } else {
        alertDiv.style.background = '#EFF6FF';
        alertDiv.style.borderColor = '#BFDBFE';
        alertDiv.innerHTML = `
            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 text-blue-500" style="display:flex;align-items:center;justify-content:center;">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="font-black text-blue-700 uppercase tracking-widest text-[11px]">ABSENSI SELESAI</p>
                <p class="text-blue-500 font-bold text-[10px] mt-0.5">${message}</p>
            </div>
        `;
    }
    
    // Insert after the first child (Employee Info card)
    const firstSection = parent.querySelector('.section-card');
    if (firstSection) {
        firstSection.after(alertDiv);
    } else {
        parent.prepend(alertDiv);
    }
    alertDiv.scrollIntoView({ behavior: 'smooth' });
}

function resetFormState() {
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const headerContainer = document.getElementById('header_container');
    const photoSection = document.getElementById('photo_section');
    const attendanceType = document.getElementById('attendance_type');

    const existingMsg = document.getElementById('status_alert_msg');
    if (existingMsg) {
        existingMsg.remove();
    }

    attendanceType.value = 'clock_in';
    headerContainer.style.background = '#059669';
    headerContainer.style.borderBottomColor = '#047857';
    photoSection.style.display = 'block';

    submitBtn.disabled = true;
    submitBtn.style.background = '#94A3B8';
    submitBtn.style.boxShadow = 'none';
    submitBtn.style.cursor = 'not-allowed';
    submitBtnText.textContent = 'PILIH KARYAWAN...';
}

function validateFormReadyForSubmit() {
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const attendanceType = document.getElementById('attendance_type');
    const employeeId = document.querySelector('input[name="employee_id"]').value;
    
    // Cek status alert (apakah terblokir atau sudah selesai)
    const existingMsg = document.getElementById('status_alert_msg');
    const isBlockedOrCompleted = existingMsg !== null;

    if (!employeeId) {
        submitBtn.disabled = true;
        submitBtn.style.background = '#94A3B8';
        submitBtn.style.boxShadow = 'none';
        submitBtn.style.cursor = 'not-allowed';
        submitBtnText.textContent = 'PILIH KARYAWAN...';
        return;
    }

    if (isBlockedOrCompleted) {
        submitBtn.disabled = true;
        submitBtn.style.cursor = 'not-allowed';
        if (existingMsg.innerHTML.includes('KEAMANAN')) {
            submitBtn.style.background = '#EF4444';
            submitBtnText.textContent = 'ABSENSI DIBATASI (HP SUDAH DIPAKAI)';
        } else {
            submitBtn.style.background = '#94A3B8';
            submitBtnText.textContent = 'ABSENSI HARI INI SELESAI';
        }
        return;
    }

    if (!gpsLocked) {
        submitBtn.disabled = true;
        submitBtn.style.background = '#94A3B8';
        submitBtn.style.boxShadow = 'none';
        submitBtn.style.cursor = 'not-allowed';
        submitBtnText.textContent = 'MENCARI LOKASI...';
        return;
    }

    // Jika gps locked dan tidak terblokir
    submitBtn.disabled = false;
    submitBtn.style.cursor = 'pointer';
    
    if (attendanceType.value === 'clock_out') {
        // Tema Amber
        submitBtn.style.background = '#D97706'; // Amber 600
        submitBtn.style.boxShadow = '0 4px 12px rgba(217,119,6,0.2)';
        submitBtnText.textContent = 'KIRIM ABSEN PULANG (CLOCK-OUT)';
    } else {
        // Tema Hijau
        submitBtn.style.background = '#059669'; // Green 600
        submitBtn.style.boxShadow = '0 4px 12px rgba(5,150,105,0.2)';
        submitBtnText.textContent = 'MASUKAN ABSENSI (CLOCK-IN)';
    }
}
</script>
@endsection
