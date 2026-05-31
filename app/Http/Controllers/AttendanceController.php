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
            Cache::remember('projects_list_geo', 86400, fn () => Project::orderBy('name')->get()->toArray())
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
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric',
            'altitude' => 'nullable|numeric',
            'heading' => 'nullable|numeric',
            'speed' => 'nullable|numeric',
            'device_info' => 'nullable|string',
            'photo_file' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'photo_base64' => 'nullable|string',
        ], [
            'employee_id.unique' => 'Karyawan ini sudah melakukan absensi hari ini.',
            'attendance_code.unique' => 'Terjadi benturan kode absensi (Duplicate). Silakan coba lagi.',
            'photo_file.image' => 'File yang diunggah harus berupa gambar.',
            'photo_file.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'photo_file.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        // 1.5 Validate that photo is taken if presence_status is 'Hadir'
        if ($request->input('presence_status') === 'Hadir' && !$request->hasFile('photo_file') && !$request->filled('photo_base64')) {
            return back()->withErrors(['photo_file' => 'Foto verifikasi wajib diambil untuk status kehadiran Hadir.'])->withInput();
        }

        // Process and save verification photo
        $photoPath = null;
        if ($request->hasFile('photo_file')) {
            $file = $request->file('photo_file');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $photoPath = $file->storeAs('attendance_photos', $filename, 'public');
        } elseif ($request->filled('photo_base64')) {
            $base64Data = $request->input('photo_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                $ext = strtolower($type[1]);
                if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    return back()->withErrors(['photo_file' => 'Format gambar base64 tidak didukung.'])->withInput();
                }
            } else {
                $ext = 'jpg';
            }

            $imageData = base64_decode($base64Data);
            if ($imageData === false) {
                return back()->withErrors(['photo_file' => 'Gagal mendekode data gambar.'])->withInput();
            }

            // Security: verify mime type of the decoded content
            $finfo = finfo_open();
            $mimeType = finfo_buffer($finfo, $imageData, FILEINFO_MIME_TYPE);
            finfo_close($finfo);

            if (!str_starts_with($mimeType, 'image/')) {
                return back()->withErrors(['photo_file' => 'Data yang dikirimkan bukan merupakan gambar yang valid.'])->withInput();
            }

            $filename = Str::random(40) . '.' . $ext;
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('attendance_photos');
            \Illuminate\Support\Facades\Storage::disk('public')->put('attendance_photos/' . $filename, $imageData);
            $photoPath = 'attendance_photos/' . $filename;
        }

        $validated['photo_path'] = $photoPath;

        // Basic Anti-Fake GPS Detection
        $isFakeGps = false;
        
        // 1. Akurasi < 3 meter biasanya tidak mungkin dari GPS HP standard, kemungkinan Mock Location
        if (isset($validated['accuracy']) && $validated['accuracy'] > 0 && $validated['accuracy'] < 3) {
            $isFakeGps = true;
        }
        
        // 2. Kecepatan tidak wajar (misal: bergerak > 20 m/s atau ~72 km/h saat absen)
        if (isset($validated['speed']) && $validated['speed'] > 20) {
            $isFakeGps = true;
        }

        $validated['is_fake_gps_suspected'] = $isFakeGps;

        // Calculate Shift
        $project = Project::find($validated['project_id']);
        $hour = now()->hour;
        $shift = 'Luar Shift'; // Fallback just in case

        if ($project && in_array($project->name, ['Main Dev', 'Sorlim'])) {
            if ($hour >= 6 && $hour < 16) {
                $shift = 'Shift Pagi';
            } else {
                $shift = 'Shift Malam';
            }
        } elseif ($project && $project->name === 'Big Fleet') {
            if ($hour >= 6 && $hour < 18) {
                $shift = 'Shift Pagi';
            } else {
                $shift = 'Shift Malam';
            }
        }

        $validated['shift'] = $shift;

        // Calculate geolocation distance if coordinates are provided
        if (!empty($validated['latitude']) && !empty($validated['longitude']) && $project) {
            if ($project->latitude && $project->longitude) {
                $distance = $this->haversineDistance(
                    $validated['latitude'],
                    $validated['longitude'],
                    $project->latitude,
                    $project->longitude
                );

                $validated['distance_from_project'] = round($distance, 2);
                $validated['is_inside_radius'] = $distance <= $project->radius_meters;
            }
        }

        // 2. Insert atomically
        $record = Attendance::create($validated);

        // 3. Update the unique code using a daily sequence counter (Race-condition free)
        $todayPrefix = 'FitToWork-TMJ-' . $record->created_at->format('dmy');
        $startOfDay = $record->created_at->copy()->startOfDay();
        
        $dailySequence = Attendance::where('created_at', '>=', $startOfDay)
                                   ->where('id', '<=', $record->id)
                                   ->count();
                                   
        $sequence = str_pad($dailySequence, 4, '0', STR_PAD_LEFT);
        $finalCode = $todayPrefix . '-' . $sequence;

        $record->update(['attendance_code' => $finalCode]);

        return redirect()->route('attendance.success')
            ->with('submission_id', $record->attendance_code)
            ->with('submission_time', $record->created_at->format('d M Y, H:i'))
            ->with('is_inside_radius', $record->is_inside_radius)
            ->with('distance', $record->distance_from_project);
    }

    /**
     * Calculate distance between two GPS points using the Haversine formula.
     *
     * @param float $lat1 Latitude of point 1
     * @param float $lon1 Longitude of point 1
     * @param float $lat2 Latitude of point 2
     * @param float $lon2 Longitude of point 2
     * @return float Distance in meters
     */
    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // Earth's radius in meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
