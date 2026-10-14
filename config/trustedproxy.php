<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Proxy yang Dipercaya
    |--------------------------------------------------------------------------
    |
    | Daftar IP reverse proxy (dipisah koma) yang header X-Forwarded-* nya
    | boleh dipercaya untuk menentukan IP asli pengunjung. Dibaca oleh
    | middleware TrustProxies bawaan Laravel.
    |
    | Default '127.0.0.1' aman untuk Nginx yang berjalan di server yang sama.
    | Jangan gunakan '*' kecuali aplikasi benar-benar hanya bisa diakses
    | lewat proxy, karena '*' memungkinkan siapa pun memalsukan IP lewat
    | header X-Forwarded-For.
    |
    */

    'proxies' => env('TRUSTED_PROXIES', '127.0.0.1'),

];
