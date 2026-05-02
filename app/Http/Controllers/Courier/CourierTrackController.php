<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CourierTrackController extends Controller
{

    public function trackShipment($trackingNumber="1234567890")
    {
        $response = Http::withHeaders([
            'DHL-API-Key' => config('services.dhl.key'), // store your key in .env
        ])->get('https://api-eu.dhl.com/track/shipments', [
            'trackingNumber' => $trackingNumber,
        ]);

        if ($response->successful()) {
            return $response->json(); // returns array
        }

        return [
            'success' => false,
            'message' => $response->body(),
            'status' => $response->status(),
        ];
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
