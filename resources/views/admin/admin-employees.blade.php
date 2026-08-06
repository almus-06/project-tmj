@extends('layouts.admin')

@section('title', 'Manajemen Data Karyawan')

@section('content')

    <div x-data="{ 
        createModalOpen: false, 
        editModalOpen: false, 
        deleteModalOpen: false,
        
        // Form states
        createForm: { name: '', position: '', customPosition: '', isCustom: false },
        editData: { id: '', name: '', position: '', customPosition: '', isCustom: false },
        deleteData: { id: '', name: '', attendancesCount: 0, unitStatusesCount: 0 },
        
        // Existing positions list
        positions: {{ json_encode($positions->values()) }},

        openCreate() {
            this.createForm = { name: '', position: '', customPosition: '', isCustom: false };
            this.createModalOpen = true;
        },

        openEdit(emp) {
            const isExisting = this.positions.includes(emp.position);
            this.editData = { 
                id: emp.id, 
                name: emp.name, 
                position: isExisting ? emp.position : '', 
                customPosition: isExisting ? '' : emp.position,
                isCustom: !isExisting
            };
            this.editModalOpen = true;
        },

        openDelete(emp) {
            this.deleteData = { 
                id: emp.id, 
                name: emp.name, 
                attendancesCount: emp.attendances_count || 0, 
                unitStatusesCount: emp.unit_statuses_count || 0 
            };
            this.deleteModalOpen = true;
        }
    }">

        {{-- Page Header --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Data Karyawan</h1>
                <p class="text-sm text-slate-500 font-medium mt-1 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                    Kelola data personel, posisi kerja, dan catatan riwayat karyawan TMJ
                </p>
            </div>
            <button @click="openCreate()"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2.5 rounded-lg transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                TAMBAH KARYAWAN
            </button>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 p-4 rounded-lg flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 p-4 rounded-lg flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center text-rose-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-rose-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-amber-50 border border-amber-200 p-4 rounded-lg shadow-sm">
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-sm font-bold text-amber-800">Mohon periksa kesalahan input berikut:</p>
                </div>
                <ul class="list-disc list-inside text-xs text-amber-700 space-y-1 ml-6 font-semibold">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
            <div class="card-industrial p-6 flex flex-col justify-between border-b-4 border-b-indigo-500">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-600">Total Karyawan</p>
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-black text-indigo-600 tabular-nums">{{ number_format($totalEmployees) }}</p>
                <p class="text-[10px] text-slate-400 font-bold uppercase mt-2">Personel Terdaftar</p>
            </div>

            <div class="card-industrial p-6 flex flex-col justify-between border-b-4 border-b-emerald-500">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Jabatan / Posisi</p>
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-black text-emerald-600 tabular-nums">{{ number_format($positionsCount) }}</p>
                <p class="text-[10px] text-slate-400 font-bold uppercase mt-2">Variasi Jabatan</p>
            </div>

            <div class="card-industrial p-6 flex flex-col justify-between border-b-4 border-b-amber-500">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-amber-600">Hasil Filter</p>
                    <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-black text-amber-600 tabular-nums">{{ number_format($employees->total()) }}</p>
                <p class="text-[10px] text-slate-400 font-bold uppercase mt-2">Tampil di Halaman Ini</p>
            </div>
        </div>

        {{-- Filter & Search Bar --}}
        <div class="card-industrial p-4 mb-6">
            <form method="GET" action="{{ route('workforce.employees') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Cari Nama / Posisi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Ketik nama karyawan atau jabatan..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm pl-9 pr-3 py-2 text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                </div>

                <div class="flex items-end gap-2">
                    <div class="flex-1">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Filter Jabatan</label>
                        <select name="position" class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm px-3 py-2 text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                            <option value="">Semua Posisi</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos }}" {{ request('position') == $pos ? 'selected' : '' }}>
                                    {{ $pos }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-4 h-[38px] rounded-lg transition shadow-sm flex items-center justify-center gap-1 flex-shrink-0">
                        CARI
                    </button>

                    @if(request()->filled('search') || request()->filled('position'))
                        <a href="{{ route('workforce.employees') }}"
                            class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs px-3 h-[38px] rounded-lg transition flex items-center justify-center flex-shrink-0" title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Employees Data Table --}}
        <div class="card-industrial overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="table-header">
                            <th class="w-12 text-center">NO</th>
                            <th>NAMA KARYAWAN</th>
                            <th>JABATAN / POSISI</th>
                            <th class="text-center">CATATAN RIWAYAT</th>
                            <th>DIBUAT PADA</th>
                            <th class="text-right pr-6">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($employees as $index => $emp)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400 tabular-nums text-xs">
                                    {{ $employees->firstItem() + $index }}
                                </td>

                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    <div class="flex items-center gap-2.5">
                                        <div class="avatar" style="background: #E0E7FF; color: #4F46E5;">
                                            {{ strtoupper(substr($emp->name, 0, 2)) }}
                                        </div>
                                        <span>{{ $emp->name }}</span>
                                    </div>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $emp->position }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 text-center">
                                    <div class="inline-flex items-center gap-2">
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            📋 {{ $emp->attendances_count }} Absensi
                                        </span>
                                        @if($emp->unit_statuses_count > 0)
                                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                🚜 {{ $emp->unit_statuses_count }} Unit
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-3.5 px-4 text-slate-500 text-xs font-medium tabular-nums">
                                    {{ $emp->created_at ? $emp->created_at->format('d M Y H:i') : '-' }}
                                </td>

                                <td class="py-3.5 px-4 text-right pr-6">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('workforce.attendance.employee', $emp->id) }}"
                                            class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded transition"
                                            title="Lihat Riwayat Absensi">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <button @click="openEdit({{ json_encode($emp) }})"
                                            class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded transition"
                                            title="Edit Data Karyawan">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        <button @click="openDelete({{ json_encode($emp) }})"
                                            class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded transition"
                                            title="Hapus Karyawan">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 font-medium">
                                    Tidak ada data karyawan ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($employees->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>


        {{-- INDUSTRIAL TMJ CREATE EMPLOYEE MODAL --}}
        <div x-show="createModalOpen" 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-cloak>
            
            <div class="card-industrial relative w-full max-w-lg bg-white rounded-xl shadow-2xl border border-slate-200"
                @click.away="createModalOpen = false">
                
                {{-- Industrial Top Accent Line --}}
                <div class="h-1.5 rounded-t-xl bg-gradient-to-r from-indigo-500 via-indigo-600 to-violet-600"></div>

                {{-- Modal Header --}}
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 tracking-tight">Tambah Karyawan Baru</h3>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">Input personel baru ke direktori perusahaan TMJ</p>
                        </div>
                    </div>
                    <button @click="createModalOpen = false" class="text-slate-400 hover:text-slate-600 transition p-1.5 rounded-lg hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Form Body --}}
                <form method="POST" action="{{ route('employees.store') }}" class="p-6 space-y-5"
                    @submit="if(createForm.isCustom) { $refs.finalCreatePos.value = createForm.customPosition; } else { $refs.finalCreatePos.value = createForm.position; }">
                    @csrf
                    <input type="hidden" name="position" x-ref="finalCreatePos">

                    {{-- Nama Karyawan --}}
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Nama Lengkap Karyawan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" name="name" x-model="createForm.name" required
                                placeholder="MISAL: RAMLI MUH. DJONO SANUSI"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm pl-9 pr-3 py-2.5 text-slate-900 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition uppercase tracking-wide">
                        </div>
                    </div>

                    {{-- Jabatan / Posisi Custom Search Dropdown --}}
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Jabatan / Posisi Kerja</label>
                        
                        <div x-data="{
                            search: '',
                            open: false,
                            get filteredOptions() {
                                if (this.search === '') return createForm.positions;
                                return createForm.positions.filter(p => p.toLowerCase().includes(this.search.toLowerCase()));
                            },
                            select(p) {
                                if (p === 'NEW_CUSTOM') {
                                    createForm.isCustom = true;
                                    createForm.position = '';
                                } else {
                                    createForm.isCustom = false;
                                    createForm.position = p;
                                }
                                this.search = '';
                                this.open = false;
                            }
                        }" class="relative" @click.away="open = false">

                            {{-- Dropdown Trigger Button / Search Bar --}}
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="text" 
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm pl-9 pr-10 py-2.5 text-slate-900 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition cursor-pointer"
                                    :placeholder="createForm.isCustom ? '+ Ketik Posisi Baru...' : (createForm.position || 'Pilih atau cari jabatan...')" 
                                    x-model="search" 
                                    @click="open = true" 
                                    @focus="open = true"
                                    autocomplete="off">
                                <div class="absolute inset-y-0 right-0 pr-2 flex items-center">
                                    <button type="button" @click="open = !open" tabindex="-1" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-full">
                                        <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Dropdown Menu --}}
                            <div x-show="open" 
                                x-transition:enter="transition ease-out duration-200" 
                                x-transition:enter-start="opacity-0 translate-y-1 scale-95" 
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                                x-transition:leave="transition ease-in duration-100" 
                                x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                                x-transition:leave-end="opacity-0 translate-y-1 scale-95" 
                                class="absolute z-50 w-full mt-1.5 bg-white rounded-xl shadow-2xl border border-slate-200 overflow-hidden" 
                                style="display: none;">
                                
                                <div class="max-h-52 overflow-y-auto divide-y divide-slate-50">
                                    <template x-for="pos in positions.filter(p => search === '' || p.toLowerCase().includes(search.toLowerCase()))" :key="pos">
                                        <div @click="select(pos)" 
                                            class="px-4 py-2.5 cursor-pointer hover:bg-slate-50 transition-colors text-sm font-bold text-slate-700 flex items-center justify-between" 
                                            :class="createForm.position === pos && !createForm.isCustom ? 'bg-indigo-50/60 text-indigo-600' : ''">
                                            <span x-text="pos"></span>
                                            <template x-if="createForm.position === pos && !createForm.isCustom">
                                                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </template>
                                        </div>
                                    </template>
                                    
                                    {{-- Option for new position --}}
                                    <div @click="select('NEW_CUSTOM')" 
                                        class="px-4 py-3 cursor-pointer bg-indigo-50/40 hover:bg-indigo-50 transition-colors text-sm font-black text-indigo-600 flex items-center gap-2 border-t border-slate-100">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                        </svg>
                                        <span>+ Ketik Posisi / Jabatan Baru...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Custom Position Field (appears if NEW_CUSTOM is selected) --}}
                    <div x-show="createForm.isCustom" x-transition>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-indigo-600 mb-1.5">Nama Posisi Kerja Baru</label>
                        <input type="text" x-model="createForm.customPosition" :required="createForm.isCustom"
                            placeholder="Ketik jabatan baru (misal: LEAD ENGINEER)"
                            class="w-full bg-indigo-50/40 border border-indigo-200 rounded-lg text-sm px-3.5 py-2.5 text-indigo-950 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>

                    {{-- Modal Footer Actions --}}
                    <div class="px-6 py-4 -mx-6 -mb-6 bg-slate-50/80 border-t border-slate-100 flex items-center justify-end gap-3 mt-6">
                        <button type="button" @click="createModalOpen = false"
                            class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                            BATAL
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2.5 rounded-lg transition shadow-sm">
                            SIMPAN KARYAWAN
                        </button>
                    </div>
                </form>
            </div>
        </div>


        {{-- INDUSTRIAL TMJ EDIT EMPLOYEE MODAL --}}
        <div x-show="editModalOpen" 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-cloak>
            
            <div class="card-industrial relative w-full max-w-lg bg-white rounded-xl shadow-2xl border border-slate-200"
                @click.away="editModalOpen = false">
                
                {{-- Industrial Top Accent Line --}}
                <div class="h-1.5 rounded-t-xl bg-gradient-to-r from-amber-500 via-amber-600 to-orange-600"></div>

                {{-- Modal Header --}}
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 tracking-tight">Edit Data Karyawan</h3>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">Perbarui informasi nama atau posisi karyawan</p>
                        </div>
                    </div>
                    <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 transition p-1.5 rounded-lg hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Form Body --}}
                <form method="POST" :action="'/operations-dashboard/employees/' + editData.id" class="p-6 space-y-5"
                    @submit="if(editData.isCustom) { $refs.finalEditPos.value = editData.customPosition; } else { $refs.finalEditPos.value = editData.position; }">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="position" x-ref="finalEditPos">

                    {{-- Nama Karyawan --}}
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Nama Lengkap Karyawan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" name="name" x-model="editData.name" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm pl-9 pr-3 py-2.5 text-slate-900 font-bold focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition uppercase tracking-wide">
                        </div>
                    </div>

                    {{-- Jabatan / Posisi Custom Dropdown --}}
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Jabatan / Posisi Kerja</label>
                        
                        <div x-data="{
                            search: '',
                            open: false,
                            select(p) {
                                if (p === 'NEW_CUSTOM') {
                                    editData.isCustom = true;
                                    editData.position = '';
                                } else {
                                    editData.isCustom = false;
                                    editData.position = p;
                                }
                                this.search = '';
                                this.open = false;
                            }
                        }" class="relative" @click.away="open = false">

                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="text" 
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg text-sm pl-9 pr-10 py-2.5 text-slate-900 font-bold focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition cursor-pointer"
                                    :placeholder="editData.isCustom ? '+ Ketik Posisi Baru...' : (editData.position || 'Pilih atau cari jabatan...')" 
                                    x-model="search" 
                                    @click="open = true" 
                                    @focus="open = true"
                                    autocomplete="off">
                                <div class="absolute inset-y-0 right-0 pr-2 flex items-center">
                                    <button type="button" @click="open = !open" tabindex="-1" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-full">
                                        <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div x-show="open" 
                                x-transition:enter="transition ease-out duration-200" 
                                x-transition:enter-start="opacity-0 translate-y-1 scale-95" 
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                                x-transition:leave="transition ease-in duration-100" 
                                x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                                x-transition:leave-end="opacity-0 translate-y-1 scale-95" 
                                class="absolute z-50 w-full mt-1.5 bg-white rounded-xl shadow-2xl border border-slate-200 overflow-hidden" 
                                style="display: none;">
                                
                                <div class="max-h-52 overflow-y-auto divide-y divide-slate-50">
                                    <template x-for="pos in positions.filter(p => search === '' || p.toLowerCase().includes(search.toLowerCase()))" :key="pos">
                                        <div @click="select(pos)" 
                                            class="px-4 py-2.5 cursor-pointer hover:bg-slate-50 transition-colors text-sm font-bold text-slate-700 flex items-center justify-between" 
                                            :class="editData.position === pos && !editData.isCustom ? 'bg-amber-50/60 text-amber-600' : ''">
                                            <span x-text="pos"></span>
                                            <template x-if="editData.position === pos && !editData.isCustom">
                                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </template>
                                        </div>
                                    </template>
                                    
                                    <div @click="select('NEW_CUSTOM')" 
                                        class="px-4 py-3 cursor-pointer bg-amber-50/40 hover:bg-amber-50 transition-colors text-sm font-black text-amber-600 flex items-center gap-2 border-t border-slate-100">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                        </svg>
                                        <span>+ Ketik Posisi / Jabatan Baru...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Custom Position Field --}}
                    <div x-show="editData.isCustom" x-transition>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-amber-600 mb-1.5">Nama Posisi Kerja Baru</label>
                        <input type="text" x-model="editData.customPosition" :required="editData.isCustom"
                            placeholder="Ketik posisi baru..."
                            class="w-full bg-amber-50/40 border border-amber-200 rounded-lg text-sm px-3.5 py-2.5 text-amber-950 font-bold focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition">
                    </div>

                    {{-- Modal Footer Actions --}}
                    <div class="px-6 py-4 -mx-6 -mb-6 bg-slate-50/80 border-t border-slate-100 flex items-center justify-end gap-3 mt-6">
                        <button type="button" @click="editModalOpen = false"
                            class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                            BATAL
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold px-5 py-2.5 rounded-lg transition shadow-sm">
                            UPDATE KARYAWAN
                        </button>
                    </div>
                </form>
            </div>
        </div>


        {{-- INDUSTRIAL TMJ DELETE CONFIRMATION MODAL --}}
        <div x-show="deleteModalOpen" 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-cloak>
            
            <div class="card-industrial relative w-full max-w-md bg-white rounded-xl shadow-2xl border border-slate-200 overflow-hidden"
                @click.away="deleteModalOpen = false">
                
                {{-- Top Red Accent Line --}}
                <div class="h-1.5 bg-gradient-to-r from-rose-500 via-rose-600 to-red-600"></div>

                <div class="p-6">
                    <div class="flex items-center gap-3.5 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 tracking-tight">Konfirmasi Hapus Data</h3>
                            <p class="text-xs font-medium text-slate-500">Tindakan ini tidak dapat dibatalkan</p>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-200/80 mb-5">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">KARYAWAN:</span>
                            <span class="text-sm font-black text-slate-900" x-text="deleteData.name"></span>
                        </div>
                        
                        <template x-if="deleteData.attendancesCount > 0 || deleteData.unitStatusesCount > 0">
                            <div class="mt-3 p-3 bg-amber-50 rounded-lg border border-amber-200 text-amber-800 text-xs space-y-1">
                                <p class="font-bold flex items-center gap-1 text-amber-900">
                                    ⚠️ Proteksi Integritas Data Aktif!
                                </p>
                                <p class="font-medium" x-text="'• Terkait ' + deleteData.attendancesCount + ' catatan absensi'"></p>
                                <p class="font-medium" x-text="'• Terkait ' + deleteData.unitStatusesCount + ' catatan operator unit'"></p>
                            </div>
                        </template>
                    </div>

                    <form method="POST" :action="'/operations-dashboard/employees/' + deleteData.id" class="flex items-center justify-end gap-3">
                        @csrf
                        @method('DELETE')

                        <button type="button" @click="deleteModalOpen = false"
                            class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                            BATAL
                        </button>
                        <button type="submit"
                            :disabled="deleteData.attendancesCount > 0 || deleteData.unitStatusesCount > 0"
                            :class="(deleteData.attendancesCount > 0 || deleteData.unitStatusesCount > 0) ? 'bg-slate-300 text-slate-500 cursor-not-allowed' : 'bg-rose-600 hover:bg-rose-700 text-white shadow-sm'"
                            class="font-bold text-xs px-5 py-2.5 rounded-lg transition">
                            HAPUS KARYAWAN
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection
