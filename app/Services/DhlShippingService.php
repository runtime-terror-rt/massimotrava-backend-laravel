<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DhlShippingService
{
    protected $apiKey;
    protected $apiSecret;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey    = config('services.dhl.key');
        $this->apiSecret = config('services.dhl.secret');
        $this->baseUrl   = config('services.dhl.url');
    }

    // ১. পিকআপের জন্য (Shipping API)
    public function schedulePickup($payload)
    {
        return Http::withBasicAuth($this->apiKey, $this->apiSecret)
            ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
            ->post($this->baseUrl . '/pickups', $payload)
            ->json();
    }

    // ২. ট্র্যাকিংয়ের জন্য (Unified Tracking API)
    public function getTrackingInfo($trackingNumber)
    {
        // ট্র্যাকিংয়ের জন্য নির্দিষ্ট URL ব্যবহার করুন (shipping API নয়)
        $url = "https://api-eu.dhl.com/track/shipments?trackingNumber={$trackingNumber}";

        return Http::withBasicAuth($this->apiKey, $this->apiSecret)
            ->withHeaders(['Accept' => 'application/json'])
            ->get($url)
            ->json();
    }
}