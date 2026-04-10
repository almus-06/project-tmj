@extends('layouts.admin')

@section('title', 'Absensi Karyawan')

@section('content')

    {{-- Page Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800">Absensi Karyawan</h1>
            <p class="text-sm text-slate-500 mt-0.5">Data kehadiran & status Fit To Work karyawan</p>
        </div>
        <a href="{{ route('admin.attendances', ['export' => 'csv'] + request()->all()) }}"
           class="inline-flex items-center gap-2 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all"
           style="background: linear-gradient(135deg, #16A34A, #15803D); box-shadow: 0 2px 8px rgba(22,163,74,0.3);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export CSV
        </a>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-5 shadow-sm">
        <form method="GET" action="{{ route('admin.attendances') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-semibold uppercase tracking-widest text-slate-400 mb-1.5">Tanggal</label>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2.5 text-slate-700 font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-semibold uppercase tracking-widest text-slate-400 mb-1.5">Status Kehadiran</label>
                <div class="relative">
                    <select name="status" class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2.5 text-slate-700 font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none appearance-none transition pr-9">
                        <option value="">Semua Status</option>
                        <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Tidak Hadir" {{ request('status') == 'Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                        <option value="Izin" {{ request('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Cuti" {{ request('status') == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="Tanpa Keterangan" {{ request('status') == 'Tanpa Keterangan' ? 'selected' : '' }}>Tanpa Keterangan</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-semibold uppercase tracking-widest text-slate-400 mb-1.5">Project</label>
                <div class="relative">
                    <select name="project" class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2.5 text-slate-700 font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none appearance-none transition pr-9">
                        <option value="">Semua Project</option>
                        <option value="Main Dev" {{ request('project') == 'Main Dev' ? 'selected' : '' }}>Main Dev</option>
                        <option value="Sorlim" {{ request('project') == 'Sorlim' ? 'selected' : '' }}>Sorlim</option>
                        <option value="Big Fleet" {{ request('project') == 'Big Fleet' ? 'selected' : '' }}>Big Fleet</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all" style="background: #2563EB;">Filter</button>
                <a href="{{ route('admin.attendances') }}" class="text-slate-600 text-sm font-semibold px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 transition-all">Reset</a>
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        {{-- Table Header --}}
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between" style="background: #F8FAFC;">
            <h2 class="text-sm font-bold text-slate-700">Daftar Absensi Karyawan</h2>
            <span class="px-3 py-1 rounded-full text-xs font-bold" style="background:#EFF6FF; color:#1D4ED8;">
                Total: {{ $attendances->total() }} Data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0;">
                        <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-widest text-slate-400">Nama / Jabatan</th>
                        <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-widest text-slate-400">Project</th>
                        <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-widest text-slate-400">Kehadiran</th>
                        <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-widest text-slate-400">Kesehatan</th>
                        <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-widest text-slate-400">Fit Status</th>
                        <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-widest text-slate-400">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($attendances as $row)
                        @php
                            $name = $row->employee->name ?? '—';
                            $initials = strtoupper(implode('', array_map(fn($w) => $w[0], explode(' ', trim($name)))));
                            $initials = substr($initials, 0, 2);
                            $avatarColors = ['#DBEAFE,#1D4ED8','#DCF7E6,#16A34A','#FEF3C7,#92400E','#EDE9FE,#5B21B6','#FCE7F3,#9D174D'];
                            $colorPair = explode(',', $avatarColors[abs(crc32($name)) % count($avatarColors)]);
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="avatar text-xs" style="background: {{ $colorPair[0] }}; color: {{ $colorPair[1] }};">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800 text-sm">{{ $name }}</p>
                                        <p class="text-xs text-slate-400">{{ $row->employee->position ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-sm text-slate-600 font-medium">{{ $row->project }}</td>
                            <td class="px-5 py-3.5">
                                @if($row->presence_status === 'Hadir')
                                    <span class="badge-hadir">{{ $row->presence_status }}</span>
                                @elseif(in_array($row->presence_status, ['Izin','Cuti']))
                                    <span class="badge-izin">{{ $row->presence_status }}</span>
                                @else
                                    <span class="badge-absent">{{ $row->presence_status }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-500">
                                <span class="font-semibold text-slate-600">{{ $row->blood_pressure }}</span> mmHg<br>
                                SpO2: {{ $row->spo2 }}% · {{ $row->temperature }}°C<br>
                                TAK: {{ $row->tak ? 'Normal' : 'Tidak Normal' }}
                            </td>
                            <td class="px-5 py-3.5">
                                @if($row->fit_status === 'Fit')
                                    <span class="badge-fit">✓ Fit</span>
                                @else
                                    <span class="badge-unfit">✗ Unfit</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-400 font-medium whitespace-nowrap">
                                {{ $row->created_at->format('d M Y') }}<br>
                                <span class="font-semibold text-slate-500">{{ $row->created_at->format('H:i') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
