@extends('layouts.admin')

@section('title', 'Monitoring Unit')

@section('content')

    {{-- Page Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800">Monitoring Unit / Fleet</h1>
            <p class="text-sm text-slate-500 mt-0.5">Status dan kondisi kendaraan operasional</p>
        </div>
        <a href="{{ route('dashboard.fleet', ['export' => 'csv'] + request()->all()) }}"
           class="inline-flex items-center gap-2 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all"
           style="background: linear-gradient(135deg, #16A34A, #15803D); box-shadow: 0 2px 8px rgba(22,163,74,0.3);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export CSV
        </a>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
        <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
            <p class="text-2xl font-extrabold text-slate-800">{{ $totalUnits }}</p>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-0.5">All Units</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
            <p class="text-2xl font-extrabold" style="color: #16A34A;">{{ $readyCount }}</p>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-0.5">Ready</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
            <p class="text-2xl font-extrabold" style="color: #D97706;">{{ $standbyCount }}</p>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-0.5">Standby</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
            <p class="text-2xl font-extrabold" style="color: #DC2626;">{{ $downCount }}</p>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-0.5">Down</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-5 shadow-sm">
        <form method="GET" action="{{ route('dashboard.fleet') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-semibold uppercase tracking-widest text-slate-400 mb-1.5">Tanggal</label>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2.5 text-slate-700 font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-semibold uppercase tracking-widest text-slate-400 mb-1.5">Status Unit</label>
                <div class="relative">
                    <select name="status" class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2.5 text-slate-700 font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none appearance-none transition pr-9">
                        <option value="">Semua Status</option>
                        <option value="Ready"   {{ request('status') == 'Ready'   ? 'selected' : '' }}>Ready</option>
                        <option value="Standby" {{ request('status') == 'Standby' ? 'selected' : '' }}>Standby</option>
                        <option value="Down"    {{ request('status') == 'Down'    ? 'selected' : '' }}>Down</option>
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
                        <option value="Main Dev"   {{ request('project') == 'Main Dev'   ? 'selected' : '' }}>Main Dev</option>
                        <option value="Sorlim" {{ request('project') == 'Sorlim' ? 'selected' : '' }}>Sorlim</option>
                        <option value="Big Fleet"  {{ request('project') == 'Big Fleet'  ? 'selected' : '' }}>Big Fleet</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all" style="background: #2563EB;">Filter</button>
                <a href="{{ route('dashboard.fleet') }}" class="text-slate-600 text-sm font-semibold px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 transition-all">Reset</a>
            </div>
        </form>
    </div>

    {{-- Data Table + Chart --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Table (2/3) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between" style="background: #F8FAFC;">
                <h2 class="text-sm font-bold text-slate-700">Daftar Status Unit Kendaraan</h2>
                <span class="px-3 py-1 rounded-full text-xs font-bold" style="background:#EFF6FF; color:#1D4ED8;">
                    Total: {{ $unitStatuses->total() }} Data
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0;">
                            <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Nomor Unit</th>
                            <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-widest text-slate-400">Status</th>
                            <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-widest text-slate-400">Operator</th>
                            <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-widest text-slate-400">Lokasi</th>
                            <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-widest text-slate-400">HM / KM</th>
                            <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-widest text-slate-400">Project</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" x-data="{ expanded: null }">
                        @forelse($unitStatuses as $row)
                            <tr class="hover:bg-slate-50 transition-colors cursor-pointer group" @click="expanded = expanded === {{ $row->id }} ? null : {{ $row->id }}">
                                <td class="px-5 py-3.5 flex items-center gap-3">
                                    <div class="w-6 h-6 rounded-md bg-slate-100 flex items-center justify-center text-slate-400 group-hover:text-blue-500 transition-colors">
                                        <svg class="w-4 h-4 transform transition-transform duration-200" :class="{'rotate-90': expanded === {{ $row->id }}}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $row->unit->no_kendaraan ?? '—' }}</p>
                                        <p class="text-xs text-slate-400">{{ $row->unit->jenis_alat ?? '—' }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($row->status === 'Ready')
                                        <span class="badge-ready">● Ready</span>
                                    @elseif($row->status === 'Standby')
                                        <span class="badge-standby">● Standby</span>
                                    @else
                                        <span class="badge-down">● Down</span>
                                        @if($row->damage_type)
                                            <p class="text-xs text-red-500 font-medium mt-0.5">{{ Str::limit($row->damage_type, 30) }}</p>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-sm text-slate-600 font-medium">{{ $row->operator_name }}</td>
                                <td class="px-5 py-3.5 text-sm text-slate-600">{{ $row->location }}</td>
                                <td class="px-5 py-3.5 text-xs text-slate-500">
                                    <span class="font-semibold text-slate-700">{{ number_format($row->hm, 1) }}</span> H<br>
                                    <span class="font-semibold text-slate-700">{{ number_format($row->km, 1) }}</span> KM
                                </td>
                                <td class="px-5 py-3.5 text-sm text-slate-600 font-medium">{{ $row->project }}</td>
                            </tr>
                            
                            {{-- Expandable History Row --}}
                            <tr x-show="expanded === {{ $row->id }}" x-transition style="display: none;">
                                <td colspan="6" class="px-5 py-4 bg-slate-50/80 border-b border-slate-200">
                                    @php
                                        // Fetch latest history omitting the current row
                                        $historyLogs = \App\Models\UnitStatus::where('unit_id', $row->unit_id)
                                                        ->where('id', '!=', $row->id)
                                                        ->latest()
                                                        ->take(5)
                                                        ->get();
                                    @endphp
                                    
                                    <div class="px-8 flex flex-col gap-3">
                                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            5 Riwayat Logs Terakhir
                                        </h4>
                                        
                                        @if($historyLogs->isEmpty())
                                            <p class="text-sm text-slate-400 italic">Belum ada riwayat sebelumnya untuk unit ini.</p>
                                        @else
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                @foreach($historyLogs as $log)
                                                    <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm flex flex-col gap-2 relative overflow-hidden">
                                                        <div class="absolute top-0 left-0 w-1 h-full 
                                                            {{ $log->status === 'Ready' ? 'bg-green-500' : ($log->status === 'Standby' ? 'bg-amber-500' : 'bg-red-500') }}">
                                                        </div>
                                                        <div class="flex justify-between items-start pl-2">
                                                            <div>
                                                                <p class="text-xs font-bold text-slate-800">{{ $log->created_at->format('d M Y, H:i') }}</p>
                                                                <p class="text-[11px] font-semibold text-slate-400 uppercase">{{ $log->status }} • {{ $log->project }}</p>
                                                            </div>
                                                            <div class="text-right">
                                                                <p class="text-xs font-bold text-slate-600">{{ number_format($log->hm, 1) }} HM</p>
                                                                <p class="text-[11px] text-slate-400 font-medium">{{ $log->operator_name }}</p>
                                                            </div>
                                                        </div>
                                                        @if($log->damage_type)
                                                            <div class="pl-2 mt-1 py-1 px-2 bg-red-50 rounded-md border border-red-100">
                                                                <p class="text-[11px] text-red-600 font-medium">Kendala: {{ $log->damage_type }}</p>
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
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $unitStatuses->links() }}
            </div>
        </div>

        {{-- Sidebar: Chart + Alert (1/3) --}}
        <div class="flex flex-col gap-4">
            {{-- Project Distribution Chart --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="text-sm font-bold text-slate-700 mb-4">Project Distribution</h3>
                <canvas id="projectChart" height="180"></canvas>
            </div>

            {{-- Critical Alert --}}
            <div class="rounded-2xl p-5" style="background: linear-gradient(135deg, #1E3A5F, #1D4ED8);">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-xs font-bold text-amber-300 uppercase tracking-widest">Unit Down</p>
                </div>
                <p class="text-2xl font-extrabold text-white mb-1">{{ $downCount }}</p>
                <p class="text-sm text-white/70">unit memerlukan perhatian segera</p>
                @if($downCount > 0)
                <div class="mt-3 pt-3 border-t border-white/10">
                    <p class="text-xs text-white/60">Segera koordinasi dengan tim maintenance untuk penanganan.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
    const ctx = document.getElementById('projectChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Main Dev', 'Sorlim', 'Big Fleet'],
            datasets: [{
                label: 'Jumlah Laporan',
                data: [{{ $chartMainDev }}, {{ $chartSorlim }}, {{ $chartBigFleet }}],
                backgroundColor: ['#3B82F6', '#8B5CF6', '#EC4899'],
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0, font: { size: 11 } }, grid: { color: '#F1F5F9' } },
                x: { ticks: { font: { size: 11 } }, grid: { display: false } }
            }
        }
    });
    </script>

@endsection
