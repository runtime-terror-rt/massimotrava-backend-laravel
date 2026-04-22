<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

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
        return Http::withBasicAuth($this->apiKey, config('services.dhl.secret'))
            ->get("{$this->baseUrl}/shipments/{$trackingNumber}/tracking")
            ->json();
    }

    public function schedulePickup(array $pickupDetails)
    {
        $url = config('services.dhl.url') . '/pickups';
        
        return Http::withBasicAuth(
            config('services.dhl.key'), 
            config('services.dhl.secret')
        )
        ->withHeaders([
            'Content-Type' => 'application/json',
        ])
        ->post($url, $pickupDetails)
        ->json();
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