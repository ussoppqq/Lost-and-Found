<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public static function sendMessage($target, $message)
    {
        try {
            // Pastikan token ada
            $token = env('FONNTE_TOKEN');
            
            if (empty($token)) {
                throw new \Exception('FONNTE_TOKEN tidak ditemukan di .env');
            }

            Log::info('Sending OTP to: ' . $target);
            
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => $token,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ])
                ->asForm()
                ->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

            $result = $response->json();
            
            Log::info('Fonnte API Response: ', [
                'status_code' => $response->status(),
                'body' => $result
            ]);

            Log::info('Fonnte Raw Response', ['response' => $response]);

            if (!$response->successful()) {
                Log::error('HTTP Error: ' . $response->status());
                return [
                    'status' => false,
                    'reason' => 'HTTP Error: ' . $response->status()
                ];
            }

            return $result;
            
        } catch (\Exception $e) {
            Log::error('FonnteService Error: ' . $e->getMessage());
            return [
                'status' => false,
                'reason' => $e->getMessage()
            ];
        }
    }

    /**
     * Cek status device Fonnte
     */
    public static function checkDevice()
    {
        try {
            $token = env('FONNTE_TOKEN');
            
            if (empty($token)) {
                return [
                    'status' => false,
                    'reason' => 'Token tidak ditemukan'
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->get('https://api.fonnte.com/device');

            return $response->json();
            
        } catch (\Exception $e) {
            return [
                'status' => false,
                'reason' => $e->getMessage()
            ];
        }
    }
}