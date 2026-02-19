<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SsoService
{
    /**
     * Authenticate user via Polakesatu SSO.
     *
     * @param string $nip
     * @param string $password
     * @return array|null Returns SSO response data on success, null on failure
     */
    public function authenticate(string $nip, string $password): ?array
    {
        try {
            $url = config('services.polakesatu_sso.url');

            $response = Http::withoutVerifying()
                ->timeout(15)
                ->post($url, [
                    'nip' => $nip,
                    'password' => $password,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Check if the SSO response indicates success
                // Adjust the condition based on the actual API response structure
                if (isset($data['success']) && $data['success']) {
                    return $data;
                }

                // Alternative: some APIs return status code or different structure
                if (isset($data['status']) && $data['status'] === true) {
                    return $data;
                }

                // If response is successful (2xx) but no explicit success flag,
                // treat it as successful
                if (!isset($data['success']) && !isset($data['status'])) {
                    return $data;
                }
            }

            Log::warning('SSO login failed', [
                'nip' => $nip,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('SSO connection error', [
                'nip' => $nip,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
