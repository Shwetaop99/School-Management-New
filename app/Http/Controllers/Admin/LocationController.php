<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    /**
     * Create authenticated request for Location API.
     */
    private function api()
    {
        return Http::withHeaders([
            'X-API-Key' => config('services.location.api_key'),
            'Accept'    => 'application/json',
        ]);
    }


    /**
     * Get all states.
     */
    public function states(): JsonResponse
    {
        $response = $this->api()->get(
            rtrim(config('services.location.url'), '/') . '/states'
        );

        return response()->json(
            $response->json(),
            $response->status()
        );
    }


    /**
     * Get ALL districts for selected state.
     */
    public function districts(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'state_id' => [
                'required',
                'integer',
            ],
        ]);

        $url = rtrim(config('services.location.url'), '/')
            . '/states/'
            . $validated['state_id']
            . '/districts';

        $response = $this->api()->get($url);

        return response()->json(
            $response->json(),
            $response->status()
        );
    }


    /**
     * Get ALL talukas / tehsils for selected district.
     */
    public function tehsils(Request $request): JsonResponse
{
    $validated = $request->validate([
        'district_id' => ['required', 'integer'],
    ]);

    $url = rtrim(config('services.location.url'), '/')
        . '/districts/'
        . $validated['district_id']
        . '/tehsils';

    try {

        $response = $this->api()->get($url);

        \Log::info('TEHSIL API REQUEST', [
            'district_id' => $validated['district_id'],
            'url' => $url,
            'status' => $response->status(),
            'response' => $response->json(),
        ]);

        return response()->json(
            $response->json(),
            $response->status()
        );

    } catch (\Throwable $e) {

        \Log::error('TEHSIL API ERROR', [
            'district_id' => $validated['district_id'],
            'url' => $url,
            'message' => $e->getMessage(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Unable to load talukas.',
            'data' => [],
        ], 500);
    }
}


    /**
     * Get ALL locations / pincodes for selected taluka.
     */
    public function locations(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tehsil_id' => [
                'required',
                'integer',
            ],
        ]);

        $url = rtrim(config('services.location.url'), '/')
            . '/tehsils/'
            . $validated['tehsil_id']
            . '/locations';

        $response = $this->api()->get($url);

        return response()->json(
            $response->json(),
            $response->status()
        );
    }
}
