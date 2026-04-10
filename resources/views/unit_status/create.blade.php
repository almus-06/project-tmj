@extends('layouts.mobile')

@section('title', 'Unit Monitoring')

@section('header')
    {{-- ═══ GRADIENT HEADER CARD ═══════════════════════════════════════════ --}}
    <div class="w-full max-w-md relative overflow-hidden"
        style="background: linear-gradient(140deg, #1D4ED8 0%, #2563EB 55%, #3B82F6 100%); border-radius: 0 0 28px 28px;">

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
            <h1 class="text-3xl font-black text-white tracking-tight leading-tight mb-1">UNIT MONITORING</h1>
            <p class="text-sm font-medium" style="color: rgba(255,255,255,0.7);">PT. TRI MACHMUD JAYA &mdash; Laporan
                Kendaraan</p>

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
    <form action="{{ route('unit.status.store') }}" method="POST" id="unitForm">
        @csrf

        {{-- ══════════════════════════════════════════ --}}
        {{-- SECTION 1: Vehicle Identity --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="section-card">
            <p class="section-label">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l9 1m1-11h4l3 5v4h-7V5z" />
                </svg>
                Vehicle Identity
            </p>

            <div>
                <label for="unit_id" class="field-label">Nomor Unit <span class="text-red-400">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg style="width:18px;height:18px;" class="text-slate-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l9 1m1-11h4l3 5v4h-7V5z" />
                        </svg>
                    </div>
                    <select id="unit_id" name="unit_id" required
                        class="form-input-field pl-11 pr-10 @error('unit_id') border-red-400 @enderror">
                        <option value="">— Pilih Unit —</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ (old('unit_id') ?? $selectedUnitId) == $unit->id ? 'selected' : '' }}>
                                {{ $unit->unit_number }} — {{ $unit->type }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('unit_id') <span class="text-xs text-red-500 font-semibold mt-1.5 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- SECTION 2: Personnel & Task --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="section-card">
            <p class="section-label">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Personnel &amp; Task
            </p>

            {{-- Operator Name --}}
            <div class="mb-4">
                <label for="operator_name" class="field-label">Nama Operator <span class="text-red-400">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg style="width:18px;height:18px;" class="text-slate-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input type="text" id="operator_name" name="operator_name" required placeholder="Full legal name"
                        value="{{ old('operator_name') }}"
                        class="form-input-field pl-11 @error('operator_name') border-red-400 @enderror">
                </div>
                @error('operator_name') <span class="text-xs text-red-500 font-semibold mt-1.5 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Project --}}
            <div>
                <label for="project" class="field-label">Project <span class="text-red-400">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg style="width:18px;height:18px;" class="text-slate-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <select id="project" name="project" required
                        class="form-input-field pl-11 pr-10 @error('project') border-red-400 @enderror">
                        <option value="Main Dev" {{ old('project', 'Main Dev') == 'Main Dev' ? 'selected' : '' }}>Main Dev
                        </option>
                        <option value="Sorlim" {{ old('project') == 'Sorlim' ? 'selected' : '' }}>Sorlim</option>
                        <option value="Big Fleet" {{ old('project') == 'Big Fleet' ? 'selected' : '' }}>Big Fleet</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('project') <span class="text-xs text-red-500 font-semibold mt-1.5 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- SECTION 3: Condition & Telemetry --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="section-card">
            <p class="section-label">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
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
                <div class="p-4 rounded-2xl" style="background: #FFF1F2; border: 1.5px solid #FECACA;">
                    <label for="damage_type" class="field-label" style="color: #B91C1C;">
                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Deskripsi Kerusakan <span class="text-red-400">*</span>
                    </label>
                    <textarea id="damage_type" name="damage_type" rows="3"
                        placeholder="Deskripsikan kerusakan secara detail..."
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
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
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
                    <input type="text" id="location" name="location" required placeholder="Pit / Bay / Workshop..."
                        value="{{ old('location') }}"
                        class="form-input-field pl-11 @error('location') border-red-400 @enderror">
                </div>
                @error('location') <span class="text-xs text-red-500 font-semibold mt-1.5 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- HM / KM --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="hm" class="field-label">Hour Meter <span class="text-slate-400 normal-case font-normal"
                            style="font-size:0.65rem;">(H/M)</span></label>
                    <div class="relative">
                        <input type="number" step="0.1" id="hm" name="hm" required placeholder="12500"
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
                <div>
                    <label for="km" class="field-label">Kilometer <span class="text-slate-400 normal-case font-normal"
                            style="font-size:0.65rem;">(KM)</span></label>
                    <div class="relative">
                        <input type="number" step="0.1" id="km" name="km" required placeholder="85000"
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
            </div>
        </div>

    </form>

    {{-- ══════════════════════════════════════════ --}}
    {{-- STICKY SUBMIT BUTTON --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="sticky-submit-bar">
        <button type="submit" form="unitForm" id="submitBtn" class="submit-btn blue">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l9 1m1-11h4l3 5v4h-7V5z" />
            </svg>
            Submit Report
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