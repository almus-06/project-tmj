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
        $fitCount   = Attendance::whereDate('created_at', today())->where('fit_status', 'Fit')->count();
        $unfitCount = Attendance::whereDate('created_at', today())->where('fit_status', 'Unfit')->count();
        
        $latestStatuses = UnitStatus::whereIn('id', function($query) {
            $query->selectRaw('MAX(id)')->from('unit_statuses')->groupBy('unit_id');
        })->get();
        
        $readyCount  = $latestStatuses->where('status', 'Ready')->count();
        $downCount   = $latestStatuses->where('status', 'Down')->count();

        return view('admin.admin-dashboard', compact('fitCount', 'unfitCount', 'readyCount', 'downCount'));
    }

    public function attendances(Request $request)
    {
        $query = Attendance::with(['employee', 'project'])->latest();

        if ($request->filled('date'))    $query->whereDate('created_at', $request->date);
        if ($request->filled('project')) $query->whereHas('project', fn($q) => $q->where('name', $request->project));
        if ($request->filled('status'))  $query->where('presence_status', $request->status);

        if ($request->get('export') == 'csv') {
            return $this->exportCsv($query->get(), 'attendances');
        }

        $attendances = $query->paginate(20);

        // Stats version Karyawan (Daily Summary)
        $todayQuery = Attendance::whereDate('created_at', now()->toDateString());
        $hadirCount  = $todayQuery->clone()->where('presence_status', 'Hadir')->count();
        $unfitCount  = $todayQuery->clone()->where('fit_status', 'Unfit')->count();
        $leaveCount  = $todayQuery->clone()->whereIn('presence_status', ['Cuti', 'Izin'])->count();
        $alphaCount  = $todayQuery->clone()->whereIn('presence_status', ['Tidak Hadir', 'Tanpa Keterangan'])->count();

        return view('admin.admin-attendance', compact('attendances', 'hadirCount', 'unfitCount', 'leaveCount', 'alphaCount'));
    }

    public function units(Request $request)
    {
        $latestIdsQuery = UnitStatus::selectRaw('MAX(id)')->groupBy('unit_id');
        $query = UnitStatus::with(['unit', 'operator', 'project'])->whereIn('id', $latestIdsQuery)->latest();

        if ($request->filled('date'))    $query->whereDate('created_at', $request->date);
        if ($request->filled('project')) $query->whereHas('project', fn($q) => $q->where('name', $request->project));
        if ($request->filled('status'))  $query->where('status', $request->status);

        if ($request->get('export') == 'csv') {
            return $this->exportCsv($query->get(), 'unit_statuses');
        }

        $unitStatuses = $query->paginate(20);

        // Summary counts from exactly the latest submitted status of each physical unit
        $latestPerUnit = UnitStatus::with('project')->whereIn('id', function($query) {
            $query->selectRaw('MAX(id)')->from('unit_statuses')->groupBy('unit_id');
        })->get();

        $totalUnits   = $latestPerUnit->count();
        $readyCount   = $latestPerUnit->where('status', 'Ready')->count();
        $standbyCount = $latestPerUnit->where('status', 'Standby')->count();
        $downCount    = $latestPerUnit->where('status', 'Down')->count();

        // Chart data distribution based on latest project placement
        $chartMainDev = $latestPerUnit->filter(fn($u) => optional($u->project)->name === 'Main Dev')->count();
        $chartSorlim  = $latestPerUnit->filter(fn($u) => optional($u->project)->name === 'Sorlim')->count();
        $chartBigFleet = $latestPerUnit->filter(fn($u) => optional($u->project)->name === 'Big Fleet')->count();

        return view('admin.admin-fleet', compact(
            'unitStatuses', 'totalUnits',
            'readyCount', 'standbyCount', 'downCount',
            'chartMainDev', 'chartSorlim', 'chartBigFleet'
        ));
    }

    private function exportCsv($data, $type)
    {
        $filename = "export_{$type}_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $columns = [];
        if ($type == 'attendances') {
            $columns = ['Date', 'Code', 'Employee', 'Project', 'Status', 'BP', 'SpO2', 'Temp', 'TAK', 'Fit Status'];
        } else {
            $columns = ['Date', 'Unit', 'Operator', 'Project', 'Status', 'Location', 'Damage', 'HM', 'KM'];
        }

        $callback = function() use($data, $columns, $type) {
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
                        $row->fit_status
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
}
