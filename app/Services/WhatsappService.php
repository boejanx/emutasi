<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsappService
{
    /**
     * Mengirim pesan whatsapp menggunakan API Fonnte
     *
     * @param string $target Nomor WhatsApp tujuan (contoh: 08123456789)
     * @param string $message Isi pesan yang dikirim
     * @return bool|string True jika berhasil, error message jika gagal
     */
    public function sendMessage($target, $message)
    {
        $token = env('FONNTE_TOKEN', ''); // Ambil token dari file .env
        
        if (empty($token) || empty($target)) {
            Log::warning('WhatsApp Notification skipped: Fonnte API Token or target number is empty.');
            return false;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', // Optional, standard for Indonesia
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $token
            ),
        ));

        $response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            Log::error('Fonnte API Error: ' . $error_msg);
            curl_close($curl);
            return $error_msg;
        }
        
        curl_close($curl);
        
        // Log the response for debugging purposes
        Log::info('Fonnte API Response: ' . $response);
        
        return $response;
    }
}
