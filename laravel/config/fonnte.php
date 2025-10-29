<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Base URL API Fonnte
    |--------------------------------------------------------------------------
    | Endpoint default Fonnte untuk mengirim pesan.
    | Jangan ubah kecuali kamu menggunakan versi API khusus.
    */
    'base_url' => env('FONNTE_BASE_URL', 'https://api.fonnte.com'),

    /*
    |--------------------------------------------------------------------------
    | Token Otentikasi
    |--------------------------------------------------------------------------
    | Token ini didapat dari dashboard Fonnte (https://app.fonnte.com).
    | Simpan di .env untuk alasan keamanan.
    */
    'token' => env('FONNTE_TOKEN', null),

    /*
    |--------------------------------------------------------------------------
    | Timeout HTTP
    |--------------------------------------------------------------------------
    | Batas waktu (detik) untuk permintaan HTTP ke API Fonnte.
    */
    'timeout' => (int) env('FONNTE_TIMEOUT', 15),

    /*
    |--------------------------------------------------------------------------
    | Path Sertifikat SSL (CA Bundle)
    |--------------------------------------------------------------------------
    | Arahkan ke file cacert.pem untuk menghindari error cURL error 60.
    | Pastikan path absolut dan file-nya benar-benar ada.
    | Contoh: storage_path('app/certs/cacert.pem')
    */
    'verify_path' => env('CACERT_PATH', storage_path('app/certs/cacert.pem')),

    /*
    |--------------------------------------------------------------------------
    | Nonaktifkan verifikasi SSL di lokal (opsional)
    |--------------------------------------------------------------------------
    | Jika kamu masih di lingkungan lokal dan sering kena error SSL,
    | kamu bisa mengatur ini menjadi true di .env:
    | DISABLE_VERIFY_ON_LOCAL=true
    | ⚠️ Jangan aktifkan di production.
    */
    'disable_verify_on_local' => (bool) env('DISABLE_VERIFY_ON_LOCAL', false),

];
