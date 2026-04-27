<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UnitStatus;
use App\Models\Unit;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;

class FleetController extends Controller
{
    public function create(Request $request, $qr_code = null)
    {
        $units = Unit::hydrate(
            Cache::remember('units_list', 86400, fn () => Unit::all()->toArray())
        );
        $employees = Employee::hydrate(
            Cache::remember('employees_list', 86400, fn () => Employee::orderBy('name')->get()->toArray())
        );
        $projects = Project::hydrate(
            Cache::remember('projects_list', 86400, fn () => Project::orderBy('name')->get()->toArray())
        );
        
        $selectedUnitId = $request->get('unit_id');
        $isLocked = false;

        if ($qr_code) {
            $unit = Unit::where('qr_code_string', $qr_code)
                ->orWhere('no_kendaraan', $qr_code)
                ->orWhere('ct', $qr_code)
                ->first();
            
            if ($unit) {
                $selectedUnitId = $unit->id;
                $isLocked = true;
            }
        }
        
        return view('forms.fleet', compact('units', 'employees', 'projects', 'selectedUnitId', 'isLocked'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id'       => 'required|exists:units,id',
            'operator_id'   => 'required|exists:employees,id',
            'status'        => 'required|string|in:Ready,Standby,Down',
            'location'      => 'required|string',
            'damage_type'   => 'required_if:status,Down',
            'hm'            => 'required|numeric',
            'km'            => 'required|numeric',
            'project_id'    => 'required|exists:projects,id'
        ]);

        $record = UnitStatus::create($validated);
        
        $unit = Unit::find($record->unit_id);

        return redirect()->route('fleet.success')
            ->with('submission_id', '#UNIT-' . str_pad($record->id, 5, '0', STR_PAD_LEFT))
            ->with('unit_number', $unit->no_kendaraan ?? '—')
            ->with('submission_time', $record->created_at->format('d M Y, H:i'))
            ->with('status_label', $record->status);
    }
}
