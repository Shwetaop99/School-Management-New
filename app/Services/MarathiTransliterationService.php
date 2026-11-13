<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MarathiTransliterationService
{
    public function transliterate(string $text): ?string
    {
        $text = trim(preg_replace('/\s+/', ' ', $text));

        if ($text === '') {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Google Cloud Translation Advanced API
        |--------------------------------------------------------------------------
        */

        $projectId = config('services.google_cloud.project_id');
        $apiKey = config('services.google_cloud.api_key');

        if (!$projectId || !$apiKey) {
            return null;
        }

        $url = 'https://translation.googleapis.com/v3/projects/'
            . $projectId
            . '/locations/global:transliterate';

        try {
            $response = Http::timeout(10)
                ->post($url . '?key=' . $apiKey, [
                    'source_language_code' => 'en',
                    'target_language_code' => 'mr',
                    'contents' => [$text],
                ]);

            if (!$response->successful()) {
                Log::warning(
                    'Marathi transliteration failed',
                    [
                        'status' => $response->status(),
                        'response' => $response->body(),
                    ]
                );

                return null;
            }

            $data = $response->json();

            return $data['translations'][0]['translatedText']
                ?? null;

        } catch (\Throwable $e) {

            Log::error(
                'Marathi transliteration exception',
                [
                    'message' => $e->getMessage(),
                ]
            );

            return null;
        }
    }
}