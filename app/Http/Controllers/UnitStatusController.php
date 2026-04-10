<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UnitStatus;
use App\Models\Unit;

class UnitStatusController extends Controller
{
    public function create(Request $request)
    {
        $units = Unit::all();
        $selectedUnitId = $request->get('unit_id');
        
        return view('unit_status.create', compact('units', 'selectedUnitId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'operator_name' => 'required|string',
            'status' => 'required|string|in:Ready,Standby,Down',
            'location' => 'required|string',
            'damage_type' => 'required_if:status,Down',
            'hm' => 'required|numeric',
            'km' => 'required|numeric',
            'project' => 'required|string'
        ]);

        $record = UnitStatus::create($validated);
        $unit = \App\Models\Unit::find($record->unit_id);

        return redirect()->route('unit.status.success')
            ->with('submission_id', '#UNIT-' . str_pad($record->id, 5, '0', STR_PAD_LEFT))
            ->with('unit_number', $unit->unit_number ?? '—')
            ->with('submission_time', now()->format('d M Y, H:i'));
    }
}
