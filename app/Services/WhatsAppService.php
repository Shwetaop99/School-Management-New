<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp text message.
     *
     * Note:
     * A normal text message can be sent only when the WhatsApp
     * conversation is within Meta's allowed customer-service window.
     * For initiating a new conversation, use an approved template.
     */
    public function sendTextMessage(
        string $phoneNumber,
        string $message
    ): array {
        $baseUrl = rtrim(
            (string) config('services.whatsapp.url', 'https://graph.facebook.com'),
            '/'
        );

        $version = trim(
            (string) config('services.whatsapp.version', '')
        );

        $phoneNumberId = trim(
            (string) config('services.whatsapp.phone_number_id', '')
        );

        $accessToken = trim(
            (string) config('services.whatsapp.access_token', '')
        );

        /*
        |--------------------------------------------------------------------------
        | Validate WhatsApp Configuration
        |--------------------------------------------------------------------------
        */

        if (
            $version === '' ||
            $phoneNumberId === '' ||
            $accessToken === ''
        ) {
            Log::error('WhatsApp configuration is incomplete.', [
                'url' => $baseUrl,
                'version' => $version,
                'phone_number_id' => $phoneNumberId !== '',
                'access_token' => $accessToken !== '',
            ]);

            return [
                'success' => false,
                'message_id' => null,
                'error' => 'WhatsApp API configuration is incomplete. Check your .env file.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Format Phone Number
        |--------------------------------------------------------------------------
        */

        $formattedPhone = $this->formatPhoneNumber($phoneNumber);

        if ($formattedPhone === '') {
            return [
                'success' => false,
                'message_id' => null,
                'error' => 'Invalid or empty phone number.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | API URL
        |--------------------------------------------------------------------------
        */

        $url = sprintf(
            '%s/%s/%s/messages',
            $baseUrl,
            $version,
            $phoneNumberId
        );

        /*
        |--------------------------------------------------------------------------
        | Send Request
        |--------------------------------------------------------------------------
        */

        try {
            $response = Http::timeout(30)
                ->withToken($accessToken)
                ->acceptJson()
                ->asJson()
                ->post($url, [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $formattedPhone,
                    'type' => 'text',
                    'text' => [
                        'preview_url' => true,
                        'body' => $message,
                    ],
                ]);

            $responseJson = $response->json();

            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            if ($response->successful()) {
                $messageId = data_get(
                    $responseJson,
                    'messages.0.id'
                );

                Log::info('WhatsApp message sent successfully.', [
                    'phone' => $formattedPhone,
                    'message_id' => $messageId,
                    'response' => $responseJson,
                ]);

                return [
                    'success' => true,
                    'message_id' => $messageId,
                    'response' => $responseJson,
                    'phone' => $formattedPhone,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | API Error
            |--------------------------------------------------------------------------
            */

            $errorMessage = data_get(
                $responseJson,
                'error.message'
            );

            $errorCode = data_get(
                $responseJson,
                'error.code'
            );

            $errorType = data_get(
                $responseJson,
                'error.type'
            );

            $errorDetails = data_get(
                $responseJson,
                'error.error_data.details'
            );

            Log::error('WhatsApp API Error.', [
                'phone' => $formattedPhone,
                'status' => $response->status(),
                'error_code' => $errorCode,
                'error_type' => $errorType,
                'error_message' => $errorMessage,
                'error_details' => $errorDetails,
                'response' => $responseJson,
            ]);

            return [
                'success' => false,
                'message_id' => null,
                'phone' => $formattedPhone,
                'status' => $response->status(),
                'error' => $errorMessage
                    ?: $errorDetails
                    ?: 'WhatsApp API rejected the message.',
                'error_code' => $errorCode,
                'error_type' => $errorType,
                'response' => $responseJson,
            ];
        } catch (\Throwable $e) {
            Log::error('WhatsApp API Exception.', [
                'phone' => $formattedPhone,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'success' => false,
                'message_id' => null,
                'phone' => $formattedPhone,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Convert Indian mobile number into WhatsApp format.
     *
     * Examples:
     *
     * 9876543210
     *     ↓
     * 919876543210
     *
     * +91 9876543210
     *     ↓
     * 919876543210
     *
     * 09876543210
     *     ↓
     * 919876543210
     */
    protected function formatPhoneNumber(
        string $phoneNumber
    ): string {
        /*
        |--------------------------------------------------------------------------
        | Remove Spaces, +, -, Brackets, etc.
        |--------------------------------------------------------------------------
        */

        $phoneNumber = preg_replace(
            '/\D+/',
            '',
            trim($phoneNumber)
        );

        if (!$phoneNumber) {
            return '';
        }

        /*
        |--------------------------------------------------------------------------
        | Remove Indian Trunk Prefix
        |--------------------------------------------------------------------------
        |
        | 09876543210
        | becomes
        | 9876543210
        |
        */

        if (
            strlen($phoneNumber) === 11 &&
            str_starts_with($phoneNumber, '0')
        ) {
            $phoneNumber = substr(
                $phoneNumber,
                1
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Add India Country Code
        |--------------------------------------------------------------------------
        */

        if (
            strlen($phoneNumber) === 10 &&
            preg_match(
                '/^[6-9][0-9]{9}$/',
                $phoneNumber
            )
        ) {
            $phoneNumber = '91' . $phoneNumber;
        }

        /*
        |--------------------------------------------------------------------------
        | Already Contains India Country Code
        |--------------------------------------------------------------------------
        */

        if (
            strlen($phoneNumber) === 12 &&
            str_starts_with($phoneNumber, '91')
        ) {
            return $phoneNumber;
        }

        /*
        |--------------------------------------------------------------------------
        | Final Validation
        |--------------------------------------------------------------------------
        */

        if (
            !preg_match(
                '/^[1-9][0-9]{9,14}$/',
                $phoneNumber
            )
        ) {
            Log::warning('Invalid WhatsApp phone number.', [
                'original' => $phoneNumber,
            ]);

            return '';
        }

        return $phoneNumber;
    }
}