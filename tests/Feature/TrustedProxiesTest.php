<?php

namespace Tests\Feature;

use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Tests\TestCase;

class TrustedProxiesTest extends TestCase
{
    public function test_header_x_forwarded_for_dari_alamat_tidak_dipercaya_diabaikan(): void
    {
        config(['trustedproxy.proxies' => '127.0.0.1']);

        $this->assertSame('203.0.113.5', $this->ipTerlihat('203.0.113.5', '1.2.3.4'));
    }

    public function test_header_dari_proxy_yang_dipercaya_dipakai(): void
    {
        config(['trustedproxy.proxies' => '127.0.0.1, 10.0.0.1']);

        $this->assertSame('1.2.3.4', $this->ipTerlihat('10.0.0.1', '1.2.3.4'));
        $this->assertSame('1.2.3.4', $this->ipTerlihat('127.0.0.1', '1.2.3.4'));
    }

    public function test_default_konfigurasi_bukan_bintang(): void
    {
        $this->assertNotSame('*', config('trustedproxy.proxies'));
    }

    private function ipTerlihat(string $remoteAddr, string $forwardedFor): string
    {
        $request = Request::create('/', 'GET', [], [], [], [
            'REMOTE_ADDR' => $remoteAddr,
            'HTTP_X_FORWARDED_FOR' => $forwardedFor,
        ]);

        $response = (new TrustProxies)->handle($request, fn (Request $r) => response($r->ip()));

        return $response->getContent();
    }
}
