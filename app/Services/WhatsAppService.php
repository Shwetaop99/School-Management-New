<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp text message.
     */
    public function sendTextMessage(
        string $phoneNumber,
        string $message
    ): array {
        $url = sprintf(
            '%s/%s/%s/messages',
            config('services.whatsapp.url'),
            config('services.whatsapp.version'),
            config('services.whatsapp.phone_number_id')
        );

        try {

            $response = Http::withToken(
                config('services.whatsapp.access_token')
            )
            ->acceptJson()
            ->post($url, [

                'messaging_product' => 'whatsapp',

                'recipient_type' => 'individual',

                'to' => $this->formatPhoneNumber($phoneNumber),

                'type' => 'text',

                'text' => [
                    'preview_url' => true,
                    'body' => $message,
                ],

            ]);

            if ($response->successful()) {

                return [
                    'success' => true,

                    'message_id' => data_get(
                        $response->json(),
                        'messages.0.id'
                    ),

                    'response' => $response->json(),
                ];
            }

            Log::error(
                'WhatsApp API Error',
                [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]
            );

            return [
                'success' => false,

                'message_id' => null,

                'error' => $response->json(),
            ];

        } catch (\Throwable $e) {

            Log::error(
                'WhatsApp API Exception',
                [
                    'message' => $e->getMessage(),
                ]
            );

            return [
                'success' => false,

                'message_id' => null,

                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Convert Indian mobile number into WhatsApp format.
     */
    protected function formatPhoneNumber(
        string $phoneNumber
    ): string {

        $phoneNumber = preg_replace(
            '/\D/',
            '',
            $phoneNumber
        );

        /*
        Example:

        9876543210
        ↓
        919876543210
        */

        if (
            strlen($phoneNumber) === 10 &&
            str_starts_with($phoneNumber, '6') ||
            str_starts_with($phoneNumber, '7') ||
            str_starts_with($phoneNumber, '8') ||
            str_starts_with($phoneNumber, '9')
        ) {
            $phoneNumber = '91' . $phoneNumber;
        }

        return $phoneNumber;
    }
}