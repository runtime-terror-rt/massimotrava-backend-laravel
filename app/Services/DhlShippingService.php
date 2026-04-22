<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DhlShippingService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.dhl.key');
        $this->baseUrl = config('services.dhl.url');
    }

    public function createShipment(array $shipmentData)
    {
        return Http::withBasicAuth($this->apiKey, config('services.dhl.secret'))
            ->post("{$this->baseUrl}/shipments", $shipmentData)
            ->json();
    }

    public function trackShipment(string $trackingNumber)
    {
        $response = Http::withHeaders([
            'DHL-API-Key' => $this->apiKey,
            'Accept'      => 'application/json',
        ])->get($this->baseUrl, [
            'trackingNumber' => $trackingNumber
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('DHL Tracking Error: ' . $response->body());
        return null;
    }

    public function schedulePickup(array $pickupData)
    {
        $pickupUrl = str_replace('/track/shipments', '/pickups', $this->baseUrl);

        $response = Http::withHeaders([
            'DHL-API-Key'  => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ])->post($pickupUrl, $pickupData);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('DHL Pickup Error: ' . $response->body());
        return $response->json();
    }

    // public function schedulePickup(array $pickupDetails)
    // {
    //     return Http::withBasicAuth(
    //         config('services.dhl.key'), 
    //         config('services.dhl.secret')
    //     )
    //     ->withHeaders([
    //         'Content-Type' => 'application/json',
    //     ])
    //     ->post(config('services.dhl.url') . '/pickups', $pickupDetails)
    //     ->json();
    // }
}