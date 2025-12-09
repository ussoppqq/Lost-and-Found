<?php

return [

    'name' => env('APP_NAME', 'Lost & Found'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Default timezone untuk fungsi tanggal/waktu PHP & Carbon::now().
    | Kamu masih tetap menyimpan submitted_at sebagai UTC di kode submit,
    | ini hanya memudahkan tampilan & default now() jadi WIB.
    |
    */
    'timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    */
    'locale' => env('APP_LOCALE', 'id'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'id_ID'),
    'timezone' => 'Asia/Jakarta',

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | Driver "file" cukup untuk 1 server. Kalau pakai banyak server,
    | ubah driver ke "cache" dan set store sesuai cache cluster kamu.
    |
    */
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        // "store" hanya dipakai kalau driver = "cache"
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
