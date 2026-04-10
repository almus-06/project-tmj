<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UnitStatus;
use App\Models\Unit;

class UnitStatusController extends Controller
{
    public function create(Request $request, $qr_code = null)
    {
        $units = Unit::all();
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
        
        return view('unit_status.create', compact('units', 'selectedUnitId', 'isLocked'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id'       => 'required|exists:units,id',
            'operator_name' => 'required|string',
            'status'        => 'required|string|in:Ready,Standby,Down',
            'location'      => 'required|string',
            'damage_type'   => 'required_if:status,Down',
            'hm'            => 'required|numeric',
            'km'            => 'required|numeric',
            'project'       => 'required|string'
        ]);

        $record = UnitStatus::create($validated);
        
        $unit = Unit::find($record->unit_id);

        return redirect()->route('unit.status.success')
            ->with('submission_id', '#UNIT-' . str_pad($record->id, 5, '0', STR_PAD_LEFT))
            ->with('unit_number', $unit->no_kendaraan ?? '—')
            ->with('submission_time', $record->created_at->format('d M Y, H:i'))
            ->with('status_label', $record->status);
    }
}
