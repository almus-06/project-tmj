@extends('layouts.admin')

@section('title', 'Pengaturan Project')

@section('content')

    {{-- Page Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan Project</h1>
            <p class="text-sm text-slate-500 font-medium mt-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                Koordinat area kerja, radius geofence absensi, dan jam shift per project
            </p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 p-4 rounded-lg flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-amber-50 border border-amber-200 p-4 rounded-lg shadow-sm">
            <p class="text-sm font-bold text-amber-800 mb-1">Data belum tersimpan, periksa isian berikut:</p>
            <ul class="list-disc pl-5 text-xs text-amber-700 font-semibold space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
        @foreach($projects as $project)
            @php $sedangDiedit = old('_project_id') == $project->id; @endphp
            <div class="card-industrial p-6 border-b-4 {{ $sedangDiedit && $errors->any() ? 'border-b-amber-500' : 'border-b-indigo-500' }}">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-lg font-black text-slate-900">{{ $project->name }}</h2>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-0.5">
                            @if($project->latitude !== null && $project->longitude !== null)
                                Geofence aktif · radius {{ $project->radius_meters }} m
                            @else
                                Geofence belum diatur
                            @endif
                        </p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        @if($project->shift_pagi_mulai !== null && $project->shift_pagi_selesai !== null)
                            Shift Pagi {{ sprintf('%02d', $project->shift_pagi_mulai) }}:00–{{ sprintf('%02d', $project->shift_pagi_selesai) }}:00
                        @else
                            Tanpa shift
                        @endif
                    </span>
                </div>

                <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_project_id" value="{{ $project->id }}">

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Lokasi</label>
                        <input type="text" name="location" maxlength="255"
                            value="{{ $sedangDiedit ? old('location') : $project->location }}"
                            placeholder="Nama area / pit"
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm px-3 py-2 text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Latitude</label>
                            <input type="number" step="0.00000001" min="-90" max="90" name="latitude"
                                value="{{ $sedangDiedit ? old('latitude') : $project->latitude }}"
                                placeholder="-2.62330530"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm px-3 py-2 text-slate-700 font-bold tabular-nums focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Longitude</label>
                            <input type="number" step="0.00000001" min="-180" max="180" name="longitude"
                                value="{{ $sedangDiedit ? old('longitude') : $project->longitude }}"
                                placeholder="121.36963140"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm px-3 py-2 text-slate-700 font-bold tabular-nums focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Radius (meter)</label>
                            <input type="number" step="1" min="1" max="100000" name="radius_meters" required
                                value="{{ $sedangDiedit ? old('radius_meters') : $project->radius_meters }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm px-3 py-2 text-slate-700 font-bold tabular-nums focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Shift Pagi mulai (jam)</label>
                            <input type="number" step="1" min="0" max="23" name="shift_pagi_mulai"
                                value="{{ $sedangDiedit ? old('shift_pagi_mulai') : $project->shift_pagi_mulai }}"
                                placeholder="6"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm px-3 py-2 text-slate-700 font-bold tabular-nums focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Shift Pagi selesai (jam)</label>
                            <input type="number" step="1" min="0" max="23" name="shift_pagi_selesai"
                                value="{{ $sedangDiedit ? old('shift_pagi_selesai') : $project->shift_pagi_selesai }}"
                                placeholder="16"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm px-3 py-2 text-slate-700 font-bold tabular-nums focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 font-semibold leading-relaxed">
                        Di luar rentang jam Shift Pagi dihitung Shift Malam. Kosongkan kedua jam bila project tidak memakai shift.
                        Kosongkan latitude &amp; longitude bila jarak absensi tidak perlu diperiksa.
                    </p>

                    <div class="flex items-center justify-end pt-2">
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2.5 rounded-lg transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            SIMPAN
                        </button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>

@endsection
