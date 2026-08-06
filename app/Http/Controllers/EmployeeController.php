<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees with filter and search.
     */
    public function index(Request $request)
    {
        $query = Employee::query()->withCount(['attendances', 'unitStatuses']);

        // Search by name or position
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        // Filter by position
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        $totalEmployees = Employee::count();
        $positionsCount = Employee::distinct('position')->count('position');
        $positions = Employee::select('position')->distinct()->whereNotNull('position')->orderBy('position')->pluck('position');

        $employees = $query->orderBy('name', 'asc')->paginate(20)->withQueryString();

        return view('admin.admin-employees', compact('employees', 'totalEmployees', 'positionsCount', 'positions'));
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama karyawan wajib diisi.',
            'position.required' => 'Jabatan/Posisi karyawan wajib diisi.',
        ]);

        $validated['name'] = trim($validated['name']);
        $validated['position'] = trim($validated['position']);

        Employee::create($validated);

        return redirect()->route('workforce.employees')
            ->with('success', 'Data karyawan "' . $validated['name'] . '" berhasil ditambahkan.');
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama karyawan wajib diisi.',
            'position.required' => 'Jabatan/Posisi karyawan wajib diisi.',
        ]);

        $validated['name'] = trim($validated['name']);
        $validated['position'] = trim($validated['position']);

        $employee->update($validated);

        return redirect()->route('workforce.employees')
            ->with('success', 'Data karyawan "' . $employee->name . '" berhasil diperbarui.');
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee)
    {
        // Check foreign key constraints/relations before deleting
        $attendancesCount = $employee->attendances()->count();
        $unitStatusesCount = $employee->unitStatuses()->count();

        if ($attendancesCount > 0 || $unitStatusesCount > 0) {
            return redirect()->route('workforce.employees')
                ->with('error', 'Gagal menghapus karyawan "' . $employee->name . '". Data ini terkait dengan ' . $attendancesCount . ' catatan absensi dan ' . $unitStatusesCount . ' riwayat unit.');
        }

        $employeeName = $employee->name;
        $employee->delete();

        return redirect()->route('workforce.employees')
            ->with('success', 'Data karyawan "' . $employeeName . '" berhasil dihapus.');
    }
}
