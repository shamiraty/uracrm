<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PrimaryApiService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        // Hizi ni credentials za API yako ya msingi
        $this->baseUrl = "https://membercard.urasaccos.co.tz/api";
        $this->apiKey = "XkizhFdGL5J43EznFaMmgNgAZnN2BLEpdnfshLsp";
    }

    /**
     * Make a GET request to the primary API.
     *
     * @param string $endpoint The API endpoint (e.g., 'card-details', 'card-details/123')
     * @param array $query Query parameters for the request
     * @return array|null Decoded JSON response or null on failure
     */
    public function get(string $endpoint, array $query = []): ?array
    {
        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
                'Accept' => 'application/json',
            ])->get("{$this->baseUrl}/{$endpoint}", $query);

            $response->throw(); // Itatupa exception kama response si successful (e.g., 4xx, 5xx)

            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Log detailed error including response body
            Log::error("Primary API GET request failed for {$endpoint}: " . $e->getMessage());
            if ($e->response) {
                Log::error("Response body: " . $e->response->body());
            }
            return null;
        } catch (\Exception $e) {
            Log::error("An unexpected error occurred during API GET request for {$endpoint}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Make a PATCH (update) request to the primary API.
     * This handles both general updates and status updates.
     *
     * @param string $endpoint The API endpoint (e.g., 'card-details/123', 'card-details/123/status')
     * @param array $data The data payload for the request
     * @return array|null Decoded JSON response or null on failure
     */
    public function patch(string $endpoint, array $data): ?array
    {
        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->patch("{$this->baseUrl}/{$endpoint}", $data);

            $response->throw();

            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error("Primary API PATCH request failed for {$endpoint}: " . $e->getMessage());
            if ($e->response) {
                Log::error("Response body: " . $e->response->body());
            }
            return null;
        } catch (\Exception $e) {
            Log::error("An unexpected error occurred during API PATCH request for {$endpoint}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Make a DELETE request to the primary API.
     *
     * @param string $endpoint The API endpoint (e.g., 'card-details/123')
     * @return array|null Decoded JSON response or null on failure
     */
    public function delete(string $endpoint): ?array
    {
        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
                'Accept' => 'application/json',
            ])->delete("{$this->baseUrl}/{$endpoint}");

            $response->throw();

            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error("Primary API DELETE request failed for {$endpoint}: " . $e->getMessage());
            if ($e->response) {
                Log::error("Response body: " . $e->response->body());
            }
            return null;
        } catch (\Exception $e) {
            Log::error("An unexpected error occurred during API DELETE request for {$endpoint}: " . $e->getMessage());
            return null;
        }
    }

    // Tunaruka 'post' method kulingana na ombi lako la kutokuwa na insert kutoka upande huu.
}