@extends('layouts.admin')

@section('title', 'Absensi Karyawan')

@section('content')

    {{-- Page Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dinamika Tenaga Kerja</h1>
            <p class="text-sm text-slate-500 font-medium mt-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                Manajemen kehadiran dan Fit To Work (FTW) karyawan
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
            <p class="text-[10px] font-black uppercase tracking-widest text-green-600 mb-1">PRESENSI: HADIR</p>
            <p class="text-4xl font-black text-green-600 tabular-nums">{{ $hadirCount }}</p>
            <p class="text-[10px] text-green-400 font-bold mt-2 uppercase">Karyawan Aktif Hari Ini</p>
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
            <p class="text-[10px] text-amber-400 font-bold mt-2 uppercase">Perlu Tindak Lanjut</p>
        </div>

        {{-- Leave --}}
        <div class="card-industrial p-6 flex flex-col items-center text-center border-b-4 border-b-amber-500">
            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002-2z"/>
                </svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-amber-600 mb-1">STATUS: CUTI / IZIN</p>
            <p class="text-4xl font-black text-amber-600 tabular-nums">{{ $leaveCount }}</p>
            <p class="text-[10px] text-amber-400 font-bold mt-2 uppercase">Karyawan Tidak Bertugas</p>
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
            <p class="text-[10px] text-red-400 font-bold mt-2 uppercase">Tanpa Keterangan</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card-industrial p-4 mb-6">
        <form method="GET" action="{{ route('workforce.attendance') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Tanggal</label>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm px-3 py-2 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Status Kehadiran</label>
                <div class="relative">
                    <select name="status"
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm px-3 py-2 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none appearance-none transition pr-9">
                        <option value="">Semua Status</option>
                        <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Tidak Hadir" {{ request('status') == 'Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                        <option value="Izin" {{ request('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Cuti" {{ request('status') == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="Tanpa Keterangan" {{ request('status') == 'Tanpa Keterangan' ? 'selected' : '' }}>Tanpa Keterangan</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Area Project</label>
                <div class="relative">
                    <select name="project"
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm px-3 py-2 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none appearance-none transition pr-9">
                        <option value="">Semua Project</option>
                        <option value="Main Dev" {{ request('project') == 'Main Dev' ? 'selected' : '' }}>Main Dev</option>
                        <option value="Sorlim" {{ request('project') == 'Sorlim' ? 'selected' : '' }}>Sorlim</option>
                        <option value="Big Fleet" {{ request('project') == 'Big Fleet' ? 'selected' : '' }}>Big Fleet</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 bg-blue-600 text-white text-[10px] font-black px-4 py-2.5 rounded-lg hover:bg-blue-700 transition-all shadow-sm">TERAPKAN</button>
                <a href="{{ route('workforce.attendance') }}"
                    class="flex-1 bg-white text-slate-600 text-[10px] text-center font-bold px-4 py-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition-all">HAPUS</a>
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="card-industrial overflow-hidden">
        {{-- Table Header --}}
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h2 class="text-xs font-black text-slate-700 uppercase tracking-widest">Catatan Kehadiran</h2>
            <span
                class="px-3 py-1 rounded-md text-[10px] font-black bg-blue-50 text-blue-700 border border-blue-100 uppercase">
                {{ $attendances->total() }} Entri
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Personel</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Penempatan
                        </th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Kehadiran</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Metrik FTW
                        </th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Hasil FTW</th>
                        <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                            Waktu</th>
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
                        <tr class="hover:bg-blue-50 transition-colors even:bg-slate-100/70 border-b border-slate-200/60">
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
                                    <span class="text-[10px] bg-slate-100 px-1.5 py-0.5 rounded font-bold text-slate-600">BP:
                                        {{ $row->blood_pressure }}</span>
                                    <span class="text-[10px] bg-slate-100 px-1.5 py-0.5 rounded font-bold text-slate-600">SpO2:
                                        {{ $row->spo2 }}%</span>
                                    <span class="text-[10px] bg-slate-100 px-1.5 py-0.5 rounded font-bold text-slate-600">T:
                                        {{ $row->temperature }}°C</span>
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
                            <td colspan="6" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-slate-400 text-sm font-medium">Belum ada data absensi yang sesuai filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-slate-100">
            {{ $attendances->links() }}
        </div>
    </div>

@endsection