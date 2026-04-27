<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Project;

class AttendanceController extends Controller
{
    public function create()
    {
        // Cache data sebagai plain array agar aman dari PHP 8.4 unserialize() bug,
        // lalu hydrate kembali menjadi Eloquent Collection untuk kompatibilitas blade view
        $employees = Employee::hydrate(
            Cache::remember('employees_list', 86400, fn () => Employee::orderBy('name')->get()->toArray())
        );

        $projects = Project::hydrate(
            Cache::remember('projects_list', 86400, fn () => Project::orderBy('name')->get()->toArray())
        );

        return view('forms.attendance', compact('employees', 'projects'));
    }

    public function store(Request $request)
    {
        // 1. Set temporary attendance code to pass validation
        $request->merge(['attendance_code' => 'TMP-' . Str::uuid()]);

        $validated = $request->validate([
            'attendance_code' => 'required|string|unique:attendances,attendance_code',
            'employee_id' => [
                'required',
                'exists:employees,id',
                // One employee can only submit once per day
                \Illuminate\Validation\Rule::unique('attendances')->where(function ($query) {
                    return $query->where('employee_id', request('employee_id'))
                        ->whereDate('created_at', now()->toDateString());
                }),
            ],
            'presence_status' => 'required|string',
            'blood_pressure' => ['required', 'regex:/^\d{2,3}\/\d{2,3}$/'],
            'spo2' => 'required|integer|min:0|max:100',
            'temperature' => 'required|numeric',
            'tak' => 'required|boolean',
            'project_id' => 'required|exists:projects,id',
            'fit_status' => 'required|string|in:Fit,Unfit',
        ], [
            'employee_id.unique' => 'Karyawan ini sudah melakukan absensi hari ini.',
            'attendance_code.unique' => 'Terjadi benturan kode absensi (Duplicate). Silakan coba lagi.',
        ]);

        // 2. Insert atomically
        $record = Attendance::create($validated);

        // 3. Update the unique code using the true Database ID (Race-condition free)
        $todayPrefix = 'FitToWork-TMJ-' . now()->format('dmy');
        $sequence = str_pad($record->id, 4, '0', STR_PAD_LEFT);
        $finalCode = $todayPrefix . '-' . $sequence;

        $record->update(['attendance_code' => $finalCode]);

        return redirect()->route('attendance.success')
            ->with('submission_id', $record->attendance_code)
            ->with('submission_time', $record->created_at->format('d M Y, H:i'));
    }
}
