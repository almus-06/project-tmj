<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Attendance;
use App\Models\Employee;

class AttendanceController extends Controller
{
    public function create()
    {
        $employees = Employee::orderBy('name')->get();
        return view('attendance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        // 1. Generate base prefix: FitToWork-TMJ-ddmmyy
        $todayPrefix = 'FitToWork-TMJ-' . now()->format('dmy');

        // 2. Calculate sequential ID for today
        // Count existing records for today with this prefix
        $countToday = Attendance::where('attendance_code', 'like', $todayPrefix . '%')->count();
        $sequence = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);
        $finalCode = $todayPrefix . '-' . $sequence;

        // 3. Merge the generated code into the request for validation
        $request->merge(['attendance_code' => $finalCode]);

        $validated = $request->validate([
            'attendance_code' => 'required|string|unique:attendances,attendance_code',
            'employee_id'     => ['required', 'exists:employees,id',
                // One employee can only submit once per day
                \Illuminate\Validation\Rule::unique('attendances')->where(function ($query) {
                    return $query->where('employee_id', request('employee_id'))
                                 ->whereDate('created_at', now()->toDateString());
                }),
            ],
            'presence_status' => 'required|string',
            'blood_pressure'  => ['required', 'regex:/^\d{2,3}\/\d{2,3}$/'],
            'spo2'            => 'required|integer|min:0|max:100',
            'temperature'     => 'required|numeric',
            'tak'             => 'required|boolean',
            'project'         => 'required|string',
            'fit_status'      => 'required|string|in:Fit,Unfit',
        ], [
            'employee_id.unique'           => 'Karyawan ini sudah melakukan absensi hari ini.',
            'attendance_code.unique'       => 'Terjadi benturan kode absensi (Duplicate). Silakan coba lagi.',
        ]);

        $record = Attendance::create($validated);

        return redirect()->route('attendance.success')
            ->with('submission_id', $record->attendance_code)
            ->with('submission_time', $record->created_at->format('d M Y, H:i'));
    }
}
