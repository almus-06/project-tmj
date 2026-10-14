<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LokalisasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_locale_bawaan_adalah_id(): void
    {
        $this->assertSame('id', config('app.locale'));
        $this->assertSame('id', app()->getLocale());
    }

    public function test_pesan_validasi_berbahasa_indonesia(): void
    {
        $this->assertSame('Username wajib diisi.', __('validation.required', ['attribute' => 'Username']));
        $this->assertSame('Kredensial ini tidak cocok dengan data kami.', __('auth.failed'));

        // Login tanpa data → pesan validasi dari lang/id
        $this->post('/login', [])->assertSessionHasErrors(['username', 'password']);
        $pesan = session('errors')->first('username');
        $this->assertStringContainsString('wajib diisi', $pesan);
    }

    public function test_halaman_login_tanpa_link_lupa_password(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Hubungi admin untuk reset password')
            ->assertDontSee(route('password.request'), false);
    }
}
