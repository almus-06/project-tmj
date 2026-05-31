@extends('layouts.admin')

@section('title', 'Riwayat Absensi - ' . $employee->name)

@section('content')
    @php
        $name = $employee->name ?? '—';
        $initials = strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', array_filter(explode(' ', trim($name))))));
        $initials = substr($initials, 0, 2);
        $avatarColors = ['#DBEAFE,#1D4ED8', '#DCF7E6,#16A34A', '#FEF3C7,#92400E', '#EDE9FE,#5B21B6', '#FCE7F3,#9D174D'];
        $colorPair = explode(',', $avatarColors[abs(crc32($name)) % count($avatarColors)]);
    @endphp

    {{-- Breadcrumb & Back --}}
    <div class="mb-6 flex items-center gap-2">
        <a href="{{ route('workforce.attendance') }}" class="inline-flex items-center gap-1.5 text-xs font-black text-indigo-600 hover:text-indigo-800 transition-colors uppercase tracking-widest">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Absensi
        </a>
    </div>

    <style>
        .profile-card-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 24px;
            background: #ffffff;
            padding: 24px;
            border-radius: 12px;
            border: 1px solid #E2E8F0;
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .profile-main-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 20px;
            width: 100%;
            flex: 1;
        }
        
        .profile-details-wrapper {
            flex: 1;
            width: 100%;
        }

        .profile-meta-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
            width: 100%;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #F1F5F9;
            text-align: left;
        }

        .profile-health-widget {
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 8px;
        }

        @media (min-width: 1024px) {
            .profile-card-container {
                flex-direction: row;
                align-items: stretch;
            }
            .profile-main-section {
                flex-direction: row;
                align-items: flex-start;
                text-align: left;
            }
            .profile-meta-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .profile-health-widget {
                width: auto;
                min-width: 240px;
                border-left: 1px solid #F1F5F9;
                padding-left: 32px;
            }
        }
    </style>

    {{-- Profile Header Card --}}
    <div class="profile-card-container mb-8">
        {{-- Decorative accent gradient lines --}}
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-indigo-500 via-violet-500 to-purple-600"></div>

        {{-- Left: Profile (Avatar + Details) --}}
        <div class="profile-main-section">
            {{-- Big Premium Avatar Frame --}}
            <div class="relative flex-shrink-0">
                <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-2xl font-black shadow-lg relative border-4 border-white ring-1 ring-slate-100"
                     style="background: linear-gradient(135deg, {{ $colorPair[0] }} 0%, #ffffff 100%); color: {{ $colorPair[1] }};">
                    {{ $initials }}
                </div>
                <span class="absolute -bottom-1.5 -right-1.5 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-white"></span>
                </span>
            </div>

            {{-- Profile Details Grid --}}
            <div class="profile-details-wrapper">
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2.5">
                    <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">{{ $employee->name }}</h1>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Aktif
                    </span>
                </div>
                
                <p class="text-xs font-semibold text-indigo-600 mt-1 uppercase tracking-wider">
                    ID Karyawan: #{{ $employee->id }}
                </p>

                <div class="profile-meta-grid">
                    <div class="flex items-center gap-2.5 text-xs text-slate-600 font-medium bg-slate-50/50 p-2 rounded-lg border border-slate-100/40">
                        <div class="w-7 h-7 rounded-lg bg-white flex items-center justify-center border border-slate-100 text-indigo-500 flex-shrink-0 shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 block uppercase tracking-wider">Jabatan</span>
                            <span class="font-black text-slate-800">{{ $employee->position ?? 'Staff' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 text-xs text-slate-600 font-medium bg-slate-50/50 p-2 rounded-lg border border-slate-100/40">
                        <div class="w-7 h-7 rounded-lg bg-white flex items-center justify-center border border-slate-100 text-indigo-500 flex-shrink-0 shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 block uppercase tracking-wider">Penempatan Terakhir</span>
                            <span class="font-black text-slate-800">{{ $attendances->first()->project->name ?? 'Belum Ditugaskan' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 text-xs text-slate-600 font-medium bg-slate-50/50 p-2 rounded-lg border border-slate-100/40">
                        <div class="w-7 h-7 rounded-lg bg-white flex items-center justify-center border border-slate-100 text-indigo-500 flex-shrink-0 shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 block uppercase tracking-wider">Bergabung Sejak</span>
                            <span class="font-black text-slate-800">{{ $employee->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 text-xs text-slate-600 font-medium bg-slate-50/50 p-2 rounded-lg border border-slate-100/40">
                        <div class="w-7 h-7 rounded-lg bg-white flex items-center justify-center border border-slate-100 text-indigo-500 flex-shrink-0 shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 block uppercase tracking-wider">Total Hari Kerja (Fit)</span>
                            <span class="font-black text-slate-800">{{ $totalPresent }} Hari</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Health Indicator Widget --}}
        <div class="profile-health-widget">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center lg:text-left block mb-1">Rata-rata Kesehatan Harian</span>
            
            <div class="grid grid-cols-2 gap-3.5">
                {{-- Suhu Card --}}
                <div class="bg-slate-50 border border-slate-100 p-3.5 rounded-xl flex flex-col items-center justify-center text-center shadow-sm">
                    <div class="w-8 h-8 rounded-full bg-rose-50 flex items-center justify-center mb-1.5">
                        <svg class="w-4.5 h-4.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2" />
                        </svg>
                    </div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Suhu</span>
                    <span class="text-xs font-black text-slate-800 mt-0.5">{{ $avgTemp ? round($avgTemp, 1) . '°C' : '—' }}</span>
                </div>

                {{-- SpO2 Card --}}
                <div class="bg-slate-50 border border-slate-100 p-3.5 rounded-xl flex flex-col items-center justify-center text-center shadow-sm">
                    <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center mb-1.5">
                        <svg class="w-4.5 h-4.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">SpO2</span>
                    <span class="text-xs font-black text-slate-800 mt-0.5">{{ $avgSpo2 ? round($avgSpo2, 0) . '%' : '—' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Statistics --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        {{-- Total Hadir --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-green-500">
            <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-green-600 mb-1">Total Hadir (Fit)</p>
            <p class="text-4xl font-black text-green-600 tabular-nums">{{ $totalPresent }}</p>
        </div>

        {{-- Izin / Cuti --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-sky-500">
            <div class="w-10 h-10 rounded-lg bg-sky-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-sky-600 mb-1">Izin / Cuti</p>
            <p class="text-4xl font-black text-sky-600 tabular-nums">{{ $totalPermission + $totalLeave }}</p>
            <p class="text-[9px] text-slate-400 font-bold uppercase mt-2">Izin: {{ $totalPermission }} | Cuti: {{ $totalLeave }}</p>
        </div>

        {{-- Luar Radius --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-amber-500">
            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-amber-600 mb-1">Absen Luar Area</p>
            <p class="text-4xl font-black text-amber-600 tabular-nums">{{ $outsideRadiusCount }}</p>
        </div>

        {{-- Fake GPS --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-purple-500">
            <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-purple-600 mb-1">Flag Fake GPS</p>
            <p class="text-4xl font-black text-purple-600 tabular-nums">{{ $fakeGpsCount }}</p>
        </div>
    </div>

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* Custom Premium Overrides for Leaflet Popups */
        .leaflet-popup-content-wrapper {
            border-radius: 12px !important;
            border: 1px solid #E2E8F0 !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            padding: 0 !important;
            overflow: hidden;
        }
        .leaflet-popup-content {
            margin: 0 !important;
        }
        .leaflet-popup-close-button {
            top: 8px !important;
            right: 8px !important;
            color: #94A3B8 !important;
            font-size: 14px !important;
        }
    </style>

    {{-- Map Section --}}
    <div class="card-industrial p-5 mb-8 bg-white relative">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-indigo-500 via-violet-500 to-purple-600"></div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 border-b border-slate-100 pb-3">
            <div>
                <h2 class="text-xs font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 animate-pulse"></span>
                    Peta Pelacakan Koordinat & Rute Absensi
                </h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5">Visualisasi rute kronologis dan radius geofence project secara interaktif</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                {{-- Map Legend --}}
                <div class="flex items-center gap-3 text-[9px] font-bold text-slate-500 uppercase tracking-wider bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200/60">
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 border border-white shadow-sm"></span> Dalam Area</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-amber-500 border border-white shadow-sm"></span> Luar Area</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-purple-600 border border-white shadow-sm"></span> Fake GPS</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-indigo-600 border border-white shadow-sm"></span> Project</span>
                </div>
                
                <span class="px-2.5 py-1.5 rounded bg-indigo-50 text-[10px] font-black text-indigo-700 border border-indigo-200 uppercase tracking-wider">
                    {{ $mapAttendances->count() }} Titik Koordinat
                </span>
            </div>
        </div>

        {{-- Leaflet Map Container --}}
        <div id="attendance-map" style="height: 400px;" class="w-full rounded-xl border border-slate-200/80 shadow-inner overflow-hidden z-10"></div>
    </div>

    {{-- Filter Panel --}}
    <div class="card-industrial p-5 mb-8 bg-slate-50/50">
        <form method="GET" action="{{ route('workforce.attendance.employee', $employee->id) }}">
            <div class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider block mb-1">Rentang Tanggal</label>
                    <div class="flex gap-2">
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                               class="flex-1 bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-bold text-slate-700 focus:outline-none focus:border-indigo-500">
                        <span class="self-center text-slate-400 text-xs font-bold">s/d</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                               class="flex-1 bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-bold text-slate-700 focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="w-[180px]">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider block mb-1">Status Kehadiran</label>
                    <select name="status"
                            class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 text-xs font-bold text-slate-700 focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>Hadir (Fit)</option>
                        <option value="Izin" {{ request('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Cuti" {{ request('status') == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                    </select>
                </div>

                <div class="flex gap-2 min-w-[200px]">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white text-[10px] font-black px-4 py-2.5 rounded-lg hover:bg-indigo-700 transition-all shadow-sm uppercase tracking-wider">Terapkan</button>
                    <a href="{{ route('workforce.attendance.employee', $employee->id) }}" class="flex-1 bg-white text-slate-600 text-[10px] text-center font-bold px-4 py-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition-all uppercase tracking-wider">Hapus</a>
                </div>
            </div>
        </form>
    </div>

    {{-- History Records Table --}}
    <div class="card-industrial overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h2 class="text-xs font-black text-slate-700 uppercase tracking-widest">Catatan Riwayat Kehadiran</h2>
            <span class="px-3 py-1 rounded-md text-[10px] font-black bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase">
                {{ $attendances->total() }} Entri
            </span>
        </div>

        {{-- Mobile Cards --}}
        <div class="lg:hidden divide-y divide-slate-100">
            @forelse($attendances as $row)
                <div class="p-4 hover:bg-indigo-50/50 transition-colors" style="{{ $loop->even ? 'background-color: #EFEFEF;' : '' }}">
                    <div class="flex justify-between items-start gap-3 mb-2">
                        <div class="text-xs font-black text-slate-900">
                            {{ $row->created_at->format('d M Y') }}
                            <span class="text-[10px] text-slate-400 font-bold block">{{ $row->created_at->format('H:i') }}</span>
                        </div>
                        <div class="flex flex-col gap-1 items-end">
                            @if($row->presence_status === 'Hadir')
                                <span class="status-chip badge-hadir">✓ Hadir</span>
                            @elseif($row->presence_status === 'Izin')
                                <span class="status-chip badge-izin">ℹ Izin</span>
                            @else
                                <span class="status-chip badge-absent">✗ Cuti</span>
                            @endif
                            
                            @if($row->presence_status === 'Hadir')
                                @if($row->fit_status === 'Fit')
                                    <span class="text-[9px] bg-emerald-50 px-1.5 py-0.5 rounded font-black text-emerald-600 border border-emerald-100">FIT</span>
                                @else
                                    <span class="text-[9px] bg-amber-50 px-1.5 py-0.5 rounded font-black text-amber-600 border border-amber-100">UNFIT</span>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 my-3 text-[11px]">
                        @if($row->presence_status === 'Hadir')
                            <div>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wide block">Verifikasi Foto</span>
                                @if($row->photo_path)
                                    <button type="button" onclick="showPhotoModal('{{ asset('storage/' . $row->photo_path) }}', '{{ $employee->name }}', '{{ $row->created_at->format('d M Y H:i') }}', '{{ $row->project->name ?? '—' }}', '{{ $row->distance_from_project }}', '{{ $row->is_inside_radius }}')" class="relative overflow-hidden rounded w-12 h-12 border border-slate-200 mt-1 block">
                                        <img src="{{ asset('storage/' . $row->photo_path) }}" class="w-full h-full object-cover" alt="Selfie">
                                    </button>
                                @else
                                    <span class="font-bold text-slate-500 block mt-1">—</span>
                                @endif
                            </div>
                            <div>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wide block">Health Indicators</span>
                                <div class="flex flex-col gap-0.5 font-bold text-slate-600 mt-1">
                                    <span>BP: {{ $row->blood_pressure }}</span>
                                    <span>SpO2: {{ $row->spo2 }}%</span>
                                    <span>Temp: {{ $row->temperature }}°C</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-slate-100 pt-2 flex justify-between items-center text-[10px]">
                        <div>
                            <span class="font-bold text-slate-500">Penempatan:</span>
                            <span class="font-black text-slate-700">{{ $row->project->name ?? '—' }}</span>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            @if($row->is_fake_gps_suspected)
                                <span class="status-chip bg-purple-50 text-purple-700 border-purple-200 py-0.5">🚩 Fake GPS</span>
                            @endif
                            @if($row->is_inside_radius === true)
                                <span class="text-emerald-600 font-bold">📍 Dalam Radius</span>
                            @elseif($row->is_inside_radius === false)
                                <span class="text-red-500 font-bold">⚠ Luar Radius ({{ $row->distance_from_project >= 1000 ? round($row->distance_from_project / 1000, 1) . 'km' : round($row->distance_from_project) . 'm' }})</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs font-bold">Belum ada riwayat absensi.</div>
            @endforelse
        </div>

        {{-- Desktop Table --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Hari/Tanggal</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Foto</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Penempatan</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Shift</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Fit Status</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Vital Metrics</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Geo Radius</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($attendances as $row)
                        <tr class="hover:bg-indigo-50 transition-colors border-b border-slate-200/60" style="{{ $loop->even ? 'background-color: #EFEFEF;' : '' }}">
                            <td class="px-5 py-2 text-xs font-bold text-slate-800">
                                <p class="font-black text-xs">{{ $row->created_at->format('d M Y') }}</p>
                                <p class="text-[9px] text-slate-400 mt-0.5">{{ $row->created_at->format('H:i') }}</p>
                            </td>
                            <td class="px-5 py-2">
                                @if($row->photo_path)
                                    <button type="button" onclick="showPhotoModal('{{ asset('storage/' . $row->photo_path) }}', '{{ $employee->name }}', '{{ $row->created_at->format('d M Y H:i') }}', '{{ $row->project->name ?? '—' }}', '{{ $row->distance_from_project }}', '{{ $row->is_inside_radius }}')" class="relative group cursor-pointer overflow-hidden rounded-lg w-8 h-8 border border-slate-200 hover:border-indigo-400 transition-all flex items-center justify-center bg-slate-100">
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
                            <td class="px-5 py-2 text-xs font-bold text-slate-700">
                                {{ $row->project->name ?? '—' }}
                            </td>
                            <td class="px-5 py-2 text-xs font-bold text-slate-700">
                                {{ $row->shift }}
                            </td>
                            <td class="px-5 py-2">
                                @if($row->presence_status === 'Hadir')
                                    <span class="status-chip badge-hadir" style="padding-top: 2px; padding-bottom: 2px;">✓ Hadir</span>
                                @elseif($row->presence_status === 'Izin')
                                    <span class="status-chip badge-izin" style="padding-top: 2px; padding-bottom: 2px;">ℹ Izin</span>
                                @else
                                    <span class="status-chip badge-absent" style="padding-top: 2px; padding-bottom: 2px;">✗ Cuti</span>
                                @endif
                            </td>
                            <td class="px-5 py-2">
                                @if($row->presence_status === 'Hadir')
                                    @if($row->fit_status === 'Fit')
                                        <span class="status-chip badge-fit" style="padding-top: 2px; padding-bottom: 2px;">✓ Fit</span>
                                    @else
                                        <span class="status-chip badge-unfit" style="padding-top: 2px; padding-bottom: 2px;">✗ Unfit</span>
                                    @endif
                                @else
                                    <span class="text-slate-300 font-bold">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-2">
                                @if($row->presence_status === 'Hadir')
                                    <div class="flex items-center gap-1 text-[9px] tabular-nums font-bold text-slate-600">
                                        <span class="bg-slate-100 px-1 py-0.5 rounded border border-slate-200/60 whitespace-nowrap">BP: {{ $row->blood_pressure }}</span>
                                        <span class="bg-slate-100 px-1 py-0.5 rounded border border-slate-200/60 whitespace-nowrap">SpO2: {{ $row->spo2 }}%</span>
                                        <span class="bg-slate-100 px-1 py-0.5 rounded border border-slate-200/60 whitespace-nowrap">T: {{ $row->temperature }}°C</span>
                                    </div>
                                @else
                                    <span class="text-slate-300 font-bold">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-2">
                                <div class="flex flex-col gap-1 items-start">
                                    <div class="flex flex-wrap gap-1">
                                        @if($row->is_fake_gps_suspected)
                                            <span class="status-chip bg-purple-50 text-purple-700 border-purple-200">🚩 Fake GPS</span>
                                        @endif
                                        @if($row->is_inside_radius === true)
                                            <span class="status-chip badge-hadir">📍 Dalam Area</span>
                                        @elseif($row->is_inside_radius === false)
                                            <span class="status-chip badge-absent">⚠ Luar Area ({{ $row->distance_from_project >= 1000 ? round($row->distance_from_project / 1000, 1) . 'km' : round($row->distance_from_project) . 'm' }})</span>
                                        @else
                                            <span class="text-[10px] text-slate-300 font-bold">—</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-slate-400 text-sm font-medium">Belum ada riwayat absensi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
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
                        <a href="{{ $attendances->previousPageUrl() }}" class="px-4 py-2 text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-white border border-indigo-100 rounded-lg hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                            Previous
                        </a>
                    @endif

                    @if($attendances->hasMorePages())
                        <a href="{{ $attendances->nextPageUrl() }}" class="px-5 py-2 text-[10px] font-black text-white uppercase tracking-widest bg-gradient-to-r from-indigo-600 to-violet-600 rounded-lg hover:shadow-lg hover:shadow-indigo-200 transition-all flex items-center gap-2 group">
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

    {{-- Reusable Photo View Modal --}}
    <div id="photo_modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0 pointer-events-none" onclick="hidePhotoModal()">
        <div class="relative bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-sm w-full overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col" onclick="event.stopPropagation()">
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
            
            <div class="relative bg-slate-950 aspect-square flex items-center justify-center overflow-hidden">
                <img id="modal_photo_img" src="" class="w-full h-full object-cover" alt="Foto Verifikasi Full">
            </div>

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

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hidePhotoModal();
            }
        });
    </script>

    @php
        $projectLocations = $projects->map(fn($p) => [
            'name' => $p->name,
            'lat' => (float)$p->latitude,
            'lng' => (float)$p->longitude,
            'radius' => (int)$p->radius_meters,
        ]);

        $attendancePoints = $mapAttendances->map(fn($a) => [
            'lat' => (float)$a->latitude,
            'lng' => (float)$a->longitude,
            'date' => $a->created_at->format('d M Y H:i'),
            'shift' => $a->shift,
            'status' => $a->presence_status,
            'fit' => $a->fit_status,
            'inside' => (bool)$a->is_inside_radius,
            'fake' => (bool)$a->is_fake_gps_suspected,
            'bp' => $a->blood_pressure,
            'spo2' => (int)$a->spo2,
            'temp' => (float)$a->temperature,
            'dist' => (float)$a->distance_from_project,
            'project_name' => $a->project->name ?? '—'
        ]);
    @endphp

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            (function() {
                // Wait for both DOM and Leaflet to load
                function initMap() {
                    const mapEl = document.getElementById('attendance-map');
                    if (!mapEl) return;

                    // Project locations and tracking coordinates
                    const projects = {!! json_encode($projectLocations) !!};
                    const trackingPoints = {!! json_encode($attendancePoints) !!};

                    // Default coordinates (first project or central point)
                    let defaultLat = -2.62330530;
                    let defaultLng = 121.36963140;

                    if (projects.length > 0) {
                        defaultLat = projects[0].lat;
                        defaultLng = projects[0].lng;
                    } else if (trackingPoints.length > 0) {
                        defaultLat = trackingPoints[trackingPoints.length - 1].lat;
                        defaultLng = trackingPoints[trackingPoints.length - 1].lng;
                    }

                    // Initialize Map
                    const map = L.map('attendance-map', {
                        scrollWheelZoom: false
                    }).setView([defaultLat, defaultLng], 14);

                    // OpenStreetMap tiles
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map);

                    // Add Easy Zoom/Scroll handlers
                    map.on('focus', function() { map.scrollWheelZoom.enable(); });
                    map.on('blur', function() { map.scrollWheelZoom.disable(); });

                    const bounds = [];

                    // 1. Draw Project Geofences (Radius boundaries)
                    projects.forEach(function(project) {
                        // Circle geofence boundary
                        L.circle([project.lat, project.lng], {
                            color: '#6366F1',
                            fillColor: '#818CF8',
                            fillOpacity: 0.15,
                            radius: project.radius,
                            weight: 2,
                            dashArray: '4, 4'
                        }).addTo(map);

                        // Custom Project HQ Marker
                        const projectIcon = L.divIcon({
                            className: 'custom-leaflet-icon',
                            html: `<div class="w-8 h-8 rounded-xl bg-indigo-600 border-2 border-white shadow-md flex items-center justify-center text-white"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg></div>`,
                            iconSize: [32, 32],
                            iconAnchor: [16, 16]
                        });

                        L.marker([project.lat, project.lng], { icon: projectIcon })
                            .addTo(map)
                            .bindPopup(`<div class="p-2 font-sans"><p class="font-black text-xs text-indigo-700">${project.name}</p><p class="text-[9px] text-slate-400 font-bold uppercase mt-0.5">Geofence Radius: ${project.radius}m</p></div>`);
                        
                        bounds.push([project.lat, project.lng]);
                    });

                    // 2. Draw Employee Login Markers & Track Path coordinates
                    const pathCoordinates = [];

                    trackingPoints.forEach(function(point, index) {
                        pathCoordinates.push([point.lat, point.lng]);
                        bounds.push([point.lat, point.lng]);

                        // Determine theme & icon based on GPS status
                        let markerBg = 'bg-emerald-500';
                        let markerBorder = 'border-white';
                        let svgInner = `<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>`;

                        if (point.fake) {
                            markerBg = 'bg-purple-600';
                            svgInner = `<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>`;
                        } else if (!point.inside) {
                            markerBg = 'bg-amber-500';
                            svgInner = `<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>`;
                        }

                        // Create custom marker with index/number to show chronological order
                        const trackingIcon = L.divIcon({
                            className: 'custom-leaflet-icon',
                            html: `<div class="w-6 h-6 rounded-full ${markerBg} border-2 ${markerBorder} shadow flex items-center justify-center text-white relative">
                                ${svgInner}
                                <span class="absolute -top-2 -right-2 bg-slate-800 text-white font-black text-[7px] px-1 rounded-full border border-slate-700">${index + 1}</span>
                            </div>`,
                            iconSize: [24, 24],
                            iconAnchor: [12, 12]
                        });

                        // Build Rich Popup Card
                        const popupContent = `
                            <div class="p-3 font-sans" style="min-width: 220px;">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-2 mb-2">
                                    <span class="font-black text-xs text-slate-800">${point.date}</span>
                                    <span class="text-[8px] bg-slate-100 px-1.5 py-0.5 rounded font-black text-slate-500 uppercase">${point.shift}</span>
                                </div>
                                <div class="space-y-1 text-xs">
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-400 font-bold uppercase text-[8px]">Kehadiran:</span>
                                        <span class="font-black ${point.status === 'Hadir' ? 'text-emerald-600' : 'text-rose-600'}">${point.status}</span>
                                    </div>
                                    ${point.status === 'Hadir' ? `
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-400 font-bold uppercase text-[8px]">Kondisi FTW:</span>
                                        <span class="font-black ${point.fit === 'Fit' ? 'text-emerald-600' : 'text-amber-600'}">${point.fit}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-400 font-bold uppercase text-[8px]">Metrik Vital:</span>
                                        <span class="font-black text-slate-700">${point.bp} | SpO2 ${point.spo2}% | ${point.temp}°C</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="text-slate-400 font-bold uppercase text-[8px] mt-0.5">Akurasi GPS:</span>
                                        <span class="font-black ${point.inside ? 'text-emerald-600' : 'text-rose-600'} text-right">
                                            ${point.inside ? '📍 Dalam Area' : `⚠ Luar Area (${point.dist >= 1000 ? (point.dist/1000).toFixed(1)+'km' : Math.round(point.dist)+'m'})`}
                                        </span>
                                    </div>
                                    ${point.fake ? `
                                    <div class="text-center mt-2 py-0.5 bg-red-50 text-red-600 font-black rounded text-[9px] border border-red-200 uppercase tracking-wide">
                                        🚩 SUSPECTED FAKE GPS
                                    </div>
                                    ` : ''}
                                    ` : ''}
                                    <div class="flex justify-between items-center pt-1.5 border-t border-slate-100 mt-1.5">
                                        <span class="text-slate-400 font-bold uppercase text-[8px]">Project Area:</span>
                                        <span class="font-black text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded text-[9px]">${point.project_name}</span>
                                    </div>
                                </div>
                            </div>
                        `;

                        L.marker([point.lat, point.lng], { icon: trackingIcon })
                            .addTo(map)
                            .bindPopup(popupContent);
                    });

                    // 3. Draw Polyline (Breadcrumb tracing route) connecting the points chronologically
                    if (pathCoordinates.length > 1) {
                        L.polyline(pathCoordinates, {
                            color: '#6366F1',
                            weight: 3,
                            opacity: 0.65,
                            dashArray: '6, 6',
                            lineJoin: 'round'
                        }).addTo(map);
                    }

                    // 4. Auto-center and fit bounds so all markers/geofences are perfectly framed
                    if (bounds.length > 0) {
                        map.fitBounds(bounds, { padding: [40, 40] });
                    }
                }

                // Execute on initial load
                initMap();

                // Re-run if SWUP completes a page swap
                if (window.swup) {
                    window.swup.hooks.on('content:replace', initMap);
                }
            })();
        </script>
    @endpush
@endsection
