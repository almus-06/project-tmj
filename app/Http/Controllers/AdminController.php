<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Attendance;
use App\Models\UnitStatus;

class AdminController extends Controller
{
    public function dashboard()
    {
        $fitCount   = Attendance::whereDate('created_at', today())->where('fit_status', 'Fit')->count();
        $unfitCount = Attendance::whereDate('created_at', today())->where('fit_status', 'Unfit')->count();
        
        $latestStatuses = UnitStatus::whereIn('id', function($query) {
            $query->selectRaw('MAX(id)')->from('unit_statuses')->groupBy('unit_id');
        })->get();
        
        $readyUnit  = $latestStatuses->where('status', 'Ready')->count();
        $downUnit   = $latestStatuses->where('status', 'Down')->count();

        return view('dashboard', compact('fitCount', 'unfitCount', 'readyUnit', 'downUnit'));
    }

    public function attendances(Request $request)
    {
        $query = Attendance::with('employee')->latest();

        if ($request->filled('date'))    $query->whereDate('created_at', $request->date);
        if ($request->filled('project')) $query->where('project', $request->project);
        if ($request->filled('status'))  $query->where('presence_status', $request->status);

        if ($request->get('export') == 'csv') {
            return $this->exportCsv($query->get(), 'attendances');
        }

        $attendances = $query->paginate(20);
        return view('admin.attendances', compact('attendances'));
    }

    public function units(Request $request)
    {
        $latestIdsQuery = UnitStatus::selectRaw('MAX(id)')->groupBy('unit_id');
        $query = UnitStatus::with('unit')->whereIn('id', $latestIdsQuery)->latest();

        if ($request->filled('date'))    $query->whereDate('created_at', $request->date);
        if ($request->filled('project')) $query->where('project', $request->project);
        if ($request->filled('status'))  $query->where('status', $request->status);

        if ($request->get('export') == 'csv') {
            return $this->exportCsv($query->get(), 'unit_statuses');
        }

        $unitStatuses = $query->paginate(20);

        // Summary counts from exactly the latest submitted status of each physical unit
        $latestPerUnit = UnitStatus::whereIn('id', function($query) {
            $query->selectRaw('MAX(id)')->from('unit_statuses')->groupBy('unit_id');
        })->get();

        $totalUnits   = $latestPerUnit->count();
        $readyCount   = $latestPerUnit->where('status', 'Ready')->count();
        $standbyCount = $latestPerUnit->where('status', 'Standby')->count();
        $downCount    = $latestPerUnit->where('status', 'Down')->count();

        // Chart data distribution based on latest project placement
        $chartMainDev = $latestPerUnit->where('project', 'Main Dev')->count();
        $chartSorlim  = $latestPerUnit->where('project', 'Sorlim')->count();
        $chartBigFleet = $latestPerUnit->where('project', 'Big Fleet')->count();

        return view('admin.units', compact(
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
                        $row->project,
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
                        $row->operator_name,
                        $row->project,
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
