<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\Attendance;
use App\Models\UnitStatus;

class OperationsController extends Controller
{
    public function dashboard()
    {
        $fitCount = Attendance::whereDate('created_at', today())->where('type', 'clock_in')->where('fit_status', 'Fit')->count();
        $unfitCount = Attendance::whereDate('created_at', today())->where('type', 'clock_in')->where('fit_status', 'Unfit')->count();

        $latestStatuses = UnitStatus::whereIn('id', function ($query) {
            $query->selectRaw('MAX(id)')->from('unit_statuses')->groupBy('unit_id');
        })->get();

        $readyCount = $latestStatuses->where('status', 'Ready')->count();
        $downCount = $latestStatuses->where('status', 'Down')->count();

        return view('admin.admin-dashboard', compact('fitCount', 'unfitCount', 'readyCount', 'downCount'));
    }

    public function attendances(Request $request)
    {
        $mulai = $request->input('start_date');
        $sampai = $request->input('end_date');
        $proyek = $request->input('project');

        // Query yang sama dipakai untuk tabel dan export
        $query = Attendance::with(['employee', 'project'])
            ->rentangTanggal($mulai, $sampai)
            ->proyek($proyek)
            ->latest();

        if ($request->input('export') == 'csv') {
            return $this->exportCsv($query, 'attendances');
        }

        $attendances = $query->paginate(35)->withQueryString();

        // Statistik mengikuti filter tanggal & proyek. Jika tidak ada filter
        // sama sekali, kartu ringkasan menampilkan data hari ini saja.
        $statsBase = Attendance::query()->rentangTanggal($mulai, $sampai)->proyek($proyek);
        if (! $mulai && ! $sampai && ! $proyek) {
            $statsBase->whereDate('created_at', now()->toDateString());
        }

        $totalCount = (clone $statsBase)->where('type', 'clock_in')->count();
        $fitCount = (clone $statsBase)->where('type', 'clock_in')->where('fit_status', 'Fit')->count();
        $unfitCount = (clone $statsBase)->where('type', 'clock_in')->where('fit_status', 'Unfit')->count();

        return view('admin.admin-attendance', compact('attendances', 'totalCount', 'fitCount', 'unfitCount'));
    }

    public function units(Request $request)
    {
        $mulai = $request->input('start_date');
        $sampai = $request->input('end_date');
        $proyek = $request->input('project');

        // Status terbaru per unit di dalam rentang tanggal, lalu filter proyek & status.
        // Query yang sama dipakai untuk tabel dan export.
        $query = UnitStatus::with(['unit', 'operator', 'project'])
            ->terbaruPerUnit($mulai, $sampai)
            ->proyek($proyek)
            ->status($request->input('status'))
            ->latest();

        if ($request->input('export') == 'csv') {
            return $this->exportCsv($query, 'unit_statuses');
        }

        $unitStatuses = $query->paginate(35)->withQueryString();

        // Statistik: logika sama dengan tabel, tanpa filter status
        $filteredUnits = UnitStatus::with('project')
            ->terbaruPerUnit($mulai, $sampai)
            ->proyek($proyek)
            ->get();

        $totalUnits = $filteredUnits->count();
        $readyCount = $filteredUnits->where('status', 'Ready')->count();
        $standbyCount = $filteredUnits->where('status', 'Standby')->count();
        $downCount = $filteredUnits->where('status', 'Down')->count();

        // Chart data distribution based on latest project placement
        $chartMainDev = $filteredUnits->filter(fn($u) => optional($u->project)->name === 'Main Dev')->count();
        $chartSorlim = $filteredUnits->filter(fn($u) => optional($u->project)->name === 'Sorlim')->count();
        $chartBigFleet = $filteredUnits->filter(fn($u) => optional($u->project)->name === 'Big Fleet')->count();

        return view('admin.admin-fleet', compact(
            'unitStatuses',
            'totalUnits',
            'readyCount',
            'standbyCount',
            'downCount',
            'chartMainDev',
            'chartSorlim',
            'chartBigFleet'
        ));
    }

    /**
     * Export CSV secara streaming. Data dibaca per 1000 baris lewat lazyByIdDesc()
     * sehingga aman untuk ratusan ribu record tanpa memuat semuanya ke memori.
     */
    private function exportCsv(Builder $query, string $type)
    {
        $filename = "export_{$type}_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [];
        if ($type == 'attendances') {
            $columns = ['Tanggal', 'Tipe', 'Kode', 'Karyawan', 'Proyek', 'Status Kehadiran', 'Tekanan Darah', 'SpO2', 'Suhu (°C)', 'TAK', 'Status Fit', 'Latitude', 'Longitude', 'Jarak (m)', 'Dalam Area', 'Akurasi (m)', 'Kecepatan (m/s)', 'Fake GPS', 'Link Foto'];
        } else {
            $columns = ['Tanggal', 'Unit', 'Operator', 'Proyek', 'Status', 'Lokasi', 'Kerusakan', 'HM', 'KM'];
        }

        // Format angka desimal dengan koma agar terbaca sebagai angka di Excel locale Indonesia
        $num = fn($value, $decimals = 2) => $value !== null && $value !== ''
            ? number_format((float) $value, $decimals, ',', '')
            : '-';
        // Paksa sebagai teks agar Excel tidak mengubah nilai seperti "120/80" menjadi tanggal
        $text = fn($value) => $value !== null && $value !== '' ? '="' . str_replace('"', '""', $value) . '"' : '-';

        // Urutan dari paginasi (latest) diganti dengan urutan id menurun agar
        // pembacaan per-chunk stabil dan tidak melompati baris.
        $rows = $query->reorder()->lazyByIdDesc(1000);

        $callback = function () use ($rows, $columns, $type, $num, $text) {
            $file = fopen('php://output', 'w');
            // BOM UTF-8 agar Excel membaca karakter dengan benar
            fwrite($file, "\xEF\xBB\xBF");
            // Excel dengan regional Indonesia memakai titik koma sebagai pemisah kolom
            $delimiter = ';';
            fputcsv($file, $columns, $delimiter);
            foreach ($rows as $row) {
                if ($type == 'attendances') {
                    fputcsv($file, [
                        $row->created_at->format('d/m/Y H:i:s'),
                        $row->type === 'clock_out' ? 'Pulang' : 'Masuk',
                        $row->attendance_code,
                        $row->employee->name ?? '-',
                        $row->project->name ?? '-',
                        $row->presence_status,
                        $text($row->blood_pressure),
                        $row->spo2,
                        $num($row->temperature, 1),
                        $row->tak ? 'Ya' : 'Tidak',
                        $row->fit_status,
                        $num($row->latitude, 8),
                        $num($row->longitude, 8),
                        $row->distance_from_project !== null ? round($row->distance_from_project) : '-',
                        $row->is_inside_radius === true ? 'Ya' : ($row->is_inside_radius === false ? 'Tidak' : '-'),
                        $num($row->accuracy),
                        $num($row->speed),
                        $row->is_fake_gps_suspected ? 'Ya' : 'Tidak',
                        $row->photo_path ? url('storage/' . $row->photo_path) : '-',
                    ], $delimiter);
                } else {
                    fputcsv($file, [
                        $row->created_at->format('d/m/Y H:i:s'),
                        ($row->unit->no_kendaraan ?? '-') . ' (' . ($row->unit->jenis_alat ?? '-') . ')',
                        $row->operator->name ?? '-',
                        $row->project->name ?? '-',
                        $row->status,
                        $row->location,
                        $row->damage_type ?? '-',
                        $num($row->hm),
                        $num($row->km),
                    ], $delimiter);
                }
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function employeeHistory($employee_id, Request $request)
    {
        $employee = \App\Models\Employee::findOrFail($employee_id);

        // Filter tanggal memakai scope yang sama dengan halaman absensi
        $query = Attendance::where('employee_id', $employee_id)
            ->with(['project'])
            ->rentangTanggal($request->input('start_date'), $request->input('end_date'));

        // Clone the base query before modifying it for pagination
        $mapQuery = clone $query;

        // Ambil data untuk riwayat (paginated)
        $attendances = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Ambil semua data koordinat yang cocok dengan filter untuk plotting di peta (urut kronologis asc)
        $mapAttendances = $mapQuery
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('created_at', 'asc')
            ->get();

        // Ambil data project koordinat untuk menggambar radius geofence
        $projects = \App\Models\Project::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // Hitung stats karyawan ini
        $allAttendances = Attendance::where('employee_id', $employee_id)->get();
        $totalCount = $allAttendances->count();
        $totalFit = $allAttendances->where('fit_status', 'Fit')->count();
        $totalUnfit = $allAttendances->where('fit_status', 'Unfit')->count();

        // Rata-rata parameter kesehatan
        $hadirLogs = $allAttendances->where('presence_status', 'Hadir');
        $avgTemp = $hadirLogs->avg('temperature');
        $avgSpo2 = $hadirLogs->avg('spo2');

        return view('admin.employee-history', compact(
            'employee',
            'attendances',
            'mapAttendances',
            'projects',
            'totalCount',
            'totalFit',
            'totalUnfit',
            'avgTemp',
            'avgSpo2'
        ));
    }
}
