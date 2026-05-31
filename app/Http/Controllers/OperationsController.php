<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\Attendance;
use App\Models\UnitStatus;

class OperationsController extends Controller
{
    public function dashboard()
    {
        $fitCount = Attendance::whereDate('created_at', today())->where('fit_status', 'Fit')->count();
        $unfitCount = Attendance::whereDate('created_at', today())->where('fit_status', 'Unfit')->count();

        $latestStatuses = UnitStatus::whereIn('id', function ($query) {
            $query->selectRaw('MAX(id)')->from('unit_statuses')->groupBy('unit_id');
        })->get();

        $readyCount = $latestStatuses->where('status', 'Ready')->count();
        $downCount = $latestStatuses->where('status', 'Down')->count();

        return view('admin.admin-dashboard', compact('fitCount', 'unfitCount', 'readyCount', 'downCount'));
    }

    public function attendances(Request $request)
    {
        $query = Attendance::with(['employee', 'project'])->latest();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('project'))
            $query->whereHas('project', fn($q) => $q->where('name', $request->project));
        if ($request->filled('status'))
            $query->where('presence_status', $request->status);

        if ($request->input('export') == 'csv') {
            return $this->exportCsv($query->get(), 'attendances');
        }

        $attendances = $query->paginate(35)->withQueryString();

        // Stats version Karyawan (Filtered Summary)
        // We use a base query that reflects date and project filters, but NOT the status filter
        $statsBase = Attendance::query();
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $statsBase->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        } elseif ($request->filled('start_date')) {
            $statsBase->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $statsBase->whereDate('created_at', '<=', $request->end_date);
        } elseif (!$request->filled('start_date') && !$request->filled('end_date') && !$request->filled('project')) {
            // Default to today for summary cards only if NO filters are applied
            // If a project is selected but no date, show all-time for that project to match table
            $statsBase->whereDate('created_at', now()->toDateString());
        }

        if ($request->filled('project')) {
            $statsBase->whereHas('project', fn($q) => $q->where('name', $request->project));
        }

        $hadirCount = $statsBase->clone()->where('presence_status', 'Hadir')->count();
        $unfitCount = $statsBase->clone()->where('fit_status', 'Unfit')->count();
        $leaveCount = $statsBase->clone()->whereIn('presence_status', ['Cuti', 'Izin'])->count();
        $alphaCount = $statsBase->clone()->whereIn('presence_status', ['Tidak Hadir', 'Tanpa Keterangan'])->count();

        return view('admin.admin-attendance', compact('attendances', 'hadirCount', 'unfitCount', 'leaveCount', 'alphaCount'));
    }

    public function units(Request $request)
    {
        $latestIdsQuery = UnitStatus::selectRaw('MAX(id)')->groupBy('unit_id');
        $query = UnitStatus::with(['unit', 'operator', 'project'])->whereIn('id', $latestIdsQuery)->latest();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('project'))
            $query->whereHas('project', fn($q) => $q->where('name', $request->project));
        if ($request->filled('status'))
            $query->where('status', $request->status);

        if ($request->input('export') == 'csv') {
            return $this->exportCsv($query->get(), 'unit_statuses');
        }

        $unitStatuses = $query->paginate(35)->withQueryString();

        // Stats version Fleet (Filtered Summary)
        // Match the same logic as the table: latest status per unit, filtered by project/date
        $statsBase = UnitStatus::whereIn('id', function ($q) {
            $q->selectRaw('MAX(id)')->from('unit_statuses')->groupBy('unit_id');
        });

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $statsBase->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        } elseif ($request->filled('start_date')) {
            $statsBase->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $statsBase->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('project')) {
            $statsBase->whereHas('project', fn($q) => $q->where('name', $request->project));
        }

        $filteredUnits = $statsBase->get();

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

    private function exportCsv($data, $type)
    {
        $filename = "export_{$type}_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [];
        if ($type == 'attendances') {
            $columns = ['Date', 'Code', 'Employee', 'Project', 'Status', 'BP', 'SpO2', 'Temp', 'TAK', 'Fit Status', 'Latitude', 'Longitude', 'Jarak (m)', 'Dalam Area', 'Akurasi (m)', 'Kecepatan (m/s)', 'Fake GPS', 'Link Foto'];
        } else {
            $columns = ['Date', 'Unit', 'Operator', 'Project', 'Status', 'Location', 'Damage', 'HM', 'KM'];
        }

        $callback = function () use ($data, $columns, $type) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($data as $row) {
                if ($type == 'attendances') {
                    fputcsv($file, [
                        $row->created_at->format('Y-m-d H:i:s'),
                        $row->attendance_code,
                        $row->employee->name ?? '-',
                        $row->project->name ?? '-',
                        $row->presence_status,
                        $row->blood_pressure,
                        $row->spo2,
                        $row->temperature,
                        $row->tak ? 'Ya' : 'Tidak',
                        $row->fit_status,
                        $row->latitude ?? '-',
                        $row->longitude ?? '-',
                        $row->distance_from_project !== null ? round($row->distance_from_project) : '-',
                        $row->is_inside_radius === true ? 'Ya' : ($row->is_inside_radius === false ? 'Tidak' : '-'),
                        $row->accuracy !== null ? round($row->accuracy, 2) : '-',
                        $row->speed !== null ? round($row->speed, 2) : '-',
                        $row->is_fake_gps_suspected ? 'Ya' : 'Tidak',
                        $row->photo_path ? url('storage/' . $row->photo_path) : '-',
                    ]);
                } else {
                    fputcsv($file, [
                        $row->created_at->format('Y-m-d H:i:s'),
                        ($row->unit->no_kendaraan ?? '-') . ' (' . ($row->unit->jenis_alat ?? '-') . ')',
                        $row->operator->name ?? '-',
                        $row->project->name ?? '-',
                        $row->status,
                        $row->location,
                        $row->damage_type,
                        $row->hm,
                        $row->km
                    ]);
                }
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function employeeHistory($employee_id, \Illuminate\Http\Request $request)
    {
        $employee = \App\Models\Employee::findOrFail($employee_id);

        $query = \App\Models\Attendance::where('employee_id', $employee_id)
            ->with(['project']);

        // Filter tanggal jika diisi
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter status jika diisi
        if ($request->filled('status')) {
            $query->where('presence_status', $request->status);
        }

        // Ambil data untuk riwayat (paginated)
        $attendances = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Ambil semua data koordinat yang cocok dengan filter untuk plotting di peta (urut kronologis asc)
        $mapAttendances = $query->clone()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('created_at', 'asc')
            ->get();

        // Ambil data project koordinat untuk menggambar radius geofence
        $projects = \App\Models\Project::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // Hitung stats karyawan ini
        $allAttendances = \App\Models\Attendance::where('employee_id', $employee_id)->get();
        $totalPresent = $allAttendances->where('presence_status', 'Hadir')->count();
        $totalPermission = $allAttendances->where('presence_status', 'Izin')->count();
        $totalLeave = $allAttendances->where('presence_status', 'Cuti')->count();

        $outsideRadiusCount = $allAttendances->where('presence_status', 'Hadir')->where('is_inside_radius', false)->count();
        $fakeGpsCount = $allAttendances->where('is_fake_gps_suspected', true)->count();

        // Rata-rata parameter kesehatan
        $hadirLogs = $allAttendances->where('presence_status', 'Hadir');
        $avgTemp = $hadirLogs->avg('temperature');
        $avgSpo2 = $hadirLogs->avg('spo2');

        return view('admin.employee-history', compact(
            'employee',
            'attendances',
            'mapAttendances',
            'projects',
            'totalPresent',
            'totalPermission',
            'totalLeave',
            'outsideRadiusCount',
            'fakeGpsCount',
            'avgTemp',
            'avgSpo2'
        ));
    }
}
