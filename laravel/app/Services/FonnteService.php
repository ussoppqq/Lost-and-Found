<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected static function httpBase()
    {
        $token = env('FONNTE_TOKEN');
        if (empty($token)) {
            throw new \Exception('FONNTE_TOKEN tidak ditemukan di .env');
        }

        // Path ke CA bundle (pakai file yang barusan kamu download)
        $caPath = base_path('storage/certs/cacert.pem');

        $options = [];
        if (file_exists($caPath)) {
            $options['verify'] = $caPath; // ✅ arahkan verifikasi ke cacert.pem
        }

        // (Opsional) Di LOCAL saja, kamu bisa mematikan verifikasi untuk debugging.
        // HINDARI di production!
        if (App::environment('local') && !file_exists($caPath)) {
            $options['verify'] = false; // ❗ hanya local fallback, tidak disarankan utk prod
        }

        return Http::timeout(30)
            ->withHeaders([
                'Authorization' => $token,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])
            ->withOptions($options)
            ->asForm();
    }

    public static function sendMessage(string $target, string $message): array
    {
        try {
            Log::info('Sending OTP to: ' . $target);

            $response = self::httpBase()->post('https://api.fonnte.com/send', [
                'target'      => $target,
                'message'     => $message,
                'countryCode' => '62',
            ]);

            // Log ringkas + body mentah untuk debugging
            Log::info('Fonnte API status=' . $response->status());
            Log::debug('Fonnte API body: ' . $response->body());

            if (!$response->successful()) {
                // Tangkap pesan SSL spesifik jika ada
                $reason = 'HTTP Error: ' . $response->status();
                if ($response->status() === 0) {
                    $reason = 'Network/SSL error (cek CA bundle & verifikasi SSL).';
                }
                return ['status' => false, 'reason' => $reason, 'raw' => $response->body()];
            }

            return $response->json() ?? ['status' => true];

        } catch (\Throwable $e) {
            // Deteksi error SSL (CURLE_PEER_FAILED_VERIFICATION = 60)
            $msg = $e->getMessage();
            if (str_contains($msg, 'cURL error 60') || str_contains($msg, 'SSL certificate')) {
                $msg .= ' | Perbaiki dengan memasang cacert.pem dan arahkan via withOptions([verify=>...]) atau php.ini.';
            }
            Log::error('FonnteService Error: ' . $msg);
            return ['status' => false, 'reason' => $msg];
        }
    }

    public static function checkDevice(): array
    {
        try {
            $response = self::httpBase()
                ->withHeaders([]) // header sudah di-set di httpBase()
                ->get('https://api.fonnte.com/device');

            if (!$response->successful()) {
                return ['status' => false, 'reason' => 'HTTP Error: ' . $response->status(), 'raw' => $response->body()];
            }

            return $response->json() ?? ['status' => true];
        } catch (\Throwable $e) {
            return ['status' => false, 'reason' => $e->getMessage()];
        }
    }
}
