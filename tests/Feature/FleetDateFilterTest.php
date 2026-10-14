<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FleetDateFilterTest extends TestCase
{
    use RefreshDatabase;

    private Unit $unitA;
    private Unit $unitB;

    protected function setUp(): void
    {
        parent::setUp();

        $operator = Employee::create(['name' => 'Operator Uji', 'position' => 'Operator']);
        $this->unitA = Unit::create(['no_kendaraan' => 'DT 01', 'jenis_alat' => 'DUMP TRUCK', 'qr_code_string' => 'DT01']);
        $this->unitB = Unit::create(['no_kendaraan' => 'EX 01', 'jenis_alat' => 'EXCAVATOR', 'qr_code_string' => 'EX01']);

        // Unit A: Ready tgl 1, lalu Down tgl 10. Unit B: hanya Standby tgl 10.
        $this->catatStatus($this->unitA->id, $operator->id, 'Ready', '2026-10-01 08:00:00');
        $this->catatStatus($this->unitA->id, $operator->id, 'Down', '2026-10-10 08:00:00');
        $this->catatStatus($this->unitB->id, $operator->id, 'Standby', '2026-10-10 09:00:00');
    }

    public function test_tanpa_filter_menampilkan_status_terbaru_setiap_unit(): void
    {
        $data = $this->bukaFleet('');

        $this->assertSame(['DT 01' => 'Down', 'EX 01' => 'Standby'], $data['tabel']);
        $this->assertSame(['total' => 2, 'ready' => 0, 'standby' => 1, 'down' => 1], $data['stat']);
    }

    public function test_filter_tanggal_mengambil_status_terbaru_di_dalam_rentang(): void
    {
        // Rentang 1–5 Okt: Unit A harus tetap muncul dengan status Ready (status
        // terakhir di rentang itu), Unit B tidak muncul karena belum ada datanya.
        $data = $this->bukaFleet('start_date=2026-10-01&end_date=2026-10-05');

        $this->assertSame(['DT 01' => 'Ready'], $data['tabel']);
        $this->assertSame(['total' => 1, 'ready' => 1, 'standby' => 0, 'down' => 0], $data['stat']);
    }

    public function test_filter_status_diterapkan_setelah_status_terbaru_ditentukan(): void
    {
        // Tanpa rentang tanggal, status terbaru A = Down, jadi filter Ready kosong
        $this->assertSame([], $this->bukaFleet('status=Ready')['tabel']);

        // Dengan rentang 1–5 Okt, status terbaru A = Ready
        $this->assertSame(['DT 01' => 'Ready'], $this->bukaFleet('status=Ready&start_date=2026-10-01&end_date=2026-10-05')['tabel']);
    }

    public function test_export_memakai_logika_filter_yang_sama(): void
    {
        $isi = $this->actingAs($this->admin())
            ->get('/operations-dashboard/fleet-management?export=csv&start_date=2026-10-01&end_date=2026-10-05')
            ->assertOk()
            ->streamedContent();

        $this->assertStringContainsString('"DT 01 (DUMP TRUCK)"', $isi);
        $this->assertStringContainsString(';Ready;', $isi);
        $this->assertStringNotContainsString('EX 01', $isi);
        $this->assertStringNotContainsString(';Down;', $isi);
    }

    /** @return array{tabel: array<string,string>, stat: array<string,int>} */
    private function bukaFleet(string $query): array
    {
        $response = $this->actingAs($this->admin())
            ->get('/operations-dashboard/fleet-management' . ($query ? "?{$query}" : ''))
            ->assertOk();

        $tabel = collect($response->viewData('unitStatuses')->items())
            ->mapWithKeys(fn ($s) => [$s->unit->no_kendaraan => $s->status])
            ->sortKeys()
            ->all();

        return [
            'tabel' => $tabel,
            'stat' => [
                'total' => $response->viewData('totalUnits'),
                'ready' => $response->viewData('readyCount'),
                'standby' => $response->viewData('standbyCount'),
                'down' => $response->viewData('downCount'),
            ],
        ];
    }

    private function catatStatus(int $unitId, int $operatorId, string $status, string $waktu): void
    {
        DB::table('unit_statuses')->insert([
            'unit_id' => $unitId, 'operator_id' => $operatorId, 'project_id' => 1,
            'status' => $status, 'location' => 'Pit A', 'damage_type' => null, 'hm' => 0, 'km' => 0,
            'created_at' => $waktu, 'updated_at' => $waktu,
        ]);
    }

    private function admin(): User
    {
        if ($user = User::where('username', 'admin_uji')->first()) {
            return $user;
        }
        $user = new User(['name' => 'Admin', 'username' => 'admin_uji', 'password' => Hash::make('password123')]);
        $user->forceFill(['role' => 'admin'])->save();

        return $user;
    }
}
