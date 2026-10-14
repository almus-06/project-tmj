<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationDisabledTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_register_mengembalikan_404(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_post_register_mengembalikan_404(): void
    {
        $this->post('/register', [
            'name' => 'Coba',
            'username' => 'coba',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertNotFound();
    }

    public function test_route_register_tidak_terdaftar(): void
    {
        $this->assertFalse(\Illuminate\Support\Facades\Route::has('register'));
    }
}
