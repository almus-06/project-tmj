<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Pengaturan project (koordinat geofence, radius, dan jam shift).
 * Hanya untuk role admin (lihat routes/web.php).
 */
class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::orderBy('name')->get();

        return view('admin.admin-projects', compact('projects'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'location' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90', 'required_with:longitude'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180', 'required_with:latitude'],
            'radius_meters' => ['required', 'integer', 'between:1,100000'],
            'shift_pagi_mulai' => ['nullable', 'integer', 'between:0,23', 'required_with:shift_pagi_selesai'],
            'shift_pagi_selesai' => ['nullable', 'integer', 'between:0,23', 'required_with:shift_pagi_mulai'],
        ], [], [
            'location' => 'Lokasi',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'radius_meters' => 'Radius',
            'shift_pagi_mulai' => 'Jam mulai Shift Pagi',
            'shift_pagi_selesai' => 'Jam selesai Shift Pagi',
        ]);

        // Hanya kolom yang ada di form yang diubah; nama project tidak disentuh.
        $project->update([
            'location' => $validated['location'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'radius_meters' => $validated['radius_meters'],
            'shift_pagi_mulai' => $validated['shift_pagi_mulai'] ?? null,
            'shift_pagi_selesai' => $validated['shift_pagi_selesai'] ?? null,
        ]);

        // Form absensi & fleet meng-cache daftar project beserta koordinatnya
        Cache::forget('projects_list');
        Cache::forget('projects_list_geo');

        return redirect()->route('projects.index')
            ->with('success', 'Pengaturan project "' . $project->name . '" berhasil disimpan.');
    }
}
