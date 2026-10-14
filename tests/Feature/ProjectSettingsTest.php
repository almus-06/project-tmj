<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProjectSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_nilai_koordinat_lama_tidak_berubah_oleh_migrasi(): void
    {
        $p = Project::where('name', 'Main Dev')->first();

        $this->assertSame('-2.62330530', (string) $p->latitude);
        $this->assertSame('121.36963140', (string) $p->longitude);
        $this->assertSame(20, $p->radius_meters);
    }

    public function test_admin_bisa_membuka_dan_menyimpan_pengaturan_project(): void
    {
        $p = Project::where('name', 'Sorlim')->first();

        $this->actingAs($this->user('admin'))
            ->get('/operations-dashboard/projects')
            ->assertOk()
            ->assertSee('Pengaturan Project')
            ->assertSee('Sorlim');

        $this->actingAs($this->user('admin'))
            ->put("/operations-dashboard/projects/{$p->id}", [
                'location' => 'Pit Sorlim',
                'latitude' => '-2.5',
                'longitude' => '121.4',
                'radius_meters' => 150,
                'shift_pagi_mulai' => 7,
                'shift_pagi_selesai' => 19,
            ])
            ->assertRedirect(route('projects.index'))
            ->assertSessionHas('success');

        $p->refresh();
        $this->assertSame('Pit Sorlim', $p->location);
        $this->assertSame('-2.50000000', (string) $p->latitude);
        $this->assertSame('121.40000000', (string) $p->longitude);
        $this->assertSame(150, $p->radius_meters);
        $this->assertSame([7, 19], [$p->shift_pagi_mulai, $p->shift_pagi_selesai]);
        $this->assertSame('Sorlim', $p->name, 'Nama project tidak boleh berubah');
    }

    public function test_validasi_menolak_koordinat_dan_radius_tidak_valid(): void
    {
        $p = Project::where('name', 'Sorlim')->first();

        $this->actingAs($this->user('admin'))
            ->from('/operations-dashboard/projects')
            ->put("/operations-dashboard/projects/{$p->id}", [
                'latitude' => '95', 'longitude' => '200', 'radius_meters' => 0, 'shift_pagi_mulai' => 6,
            ])
            ->assertRedirect('/operations-dashboard/projects')
            ->assertSessionHasErrors(['latitude', 'longitude', 'radius_meters', 'shift_pagi_selesai']);

        $this->assertSame(20, $p->fresh()->radius_meters);
    }

    public function test_menyimpan_membersihkan_cache_daftar_project(): void
    {
        Cache::put('projects_list_geo', ['lama'], 3600);
        Cache::put('projects_list', ['lama'], 3600);
        $p = Project::where('name', 'Sorlim')->first();

        $this->actingAs($this->user('admin'))
            ->put("/operations-dashboard/projects/{$p->id}", ['radius_meters' => 50]);

        $this->assertFalse(Cache::has('projects_list_geo'));
        $this->assertFalse(Cache::has('projects_list'));
    }

    public function test_selain_admin_tidak_bisa_mengakses(): void
    {
        $p = Project::where('name', 'Sorlim')->first();

        // Tamu (belum login) diarahkan ke halaman login
        $this->get('/operations-dashboard/projects')->assertRedirect(route('login'));

        foreach (['hrd', 'supervisor', 'workshop'] as $role) {
            $this->actingAs($this->user($role))
                ->get('/operations-dashboard/projects')
                ->assertRedirect(route('operations.dashboard'));

            $this->actingAs($this->user($role))
                ->put("/operations-dashboard/projects/{$p->id}", ['radius_meters' => 999])
                ->assertRedirect(route('operations.dashboard'));
        }

        $this->assertSame(20, $p->fresh()->radius_meters);
    }

    public function test_link_proyek_hanya_tampil_untuk_admin(): void
    {
        $this->actingAs($this->user('admin'))->get('/operations-dashboard')->assertSee(route('projects.index'));
        $this->actingAs($this->user('hrd'))->get('/operations-dashboard')->assertDontSee(route('projects.index'));
    }

    private function user(string $role): User
    {
        if ($u = User::where('username', 'uji_' . $role)->first()) {
            return $u;
        }
        $u = new User(['name' => 'Uji ' . $role, 'username' => 'uji_' . $role, 'password' => Hash::make('password123')]);
        $u->forceFill(['role' => $role])->save();

        return $u;
    }
}
