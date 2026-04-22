<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DhlShippingService;

class ShippingController extends Controller
{
    public function requestPickup(Request $request, DhlShippingService $dhlService)
    {
        $payload = [
            "plannedShipmentDateAndTime" => "2026-04-25T10:00:00GMT+06:00",
            "pickupDetails" => [
                "postalAddress" => [
                    "postalCode" => "1212",
                    "cityName" => "Dhaka",
                    "countryCode" => "BD",
                    "streetLines" => ["House 12, Road 5"]
                ],
                "contactInformation" => [
                    "personName" => "Md. Mahbubur Rahman",
                    "phone" => "+8801XXXXXXXXX"
                ]
            ],
        ];

        $response = $dhlService->schedulePickup($payload);

        if (isset($response['dispatchConfirmationNumber'])) {
            return response()->json(['status' => 'success', 'data' => $response]);
        }

        return response()->json(['status' => 'error', 'message' => $response], 400);
    }

    public function track(Request $request, DhlShippingService $dhl)
    {
        $trackingNumber = $request->input('number');
        $data = $dhl->trackShipment($trackingNumber);

        if ($data) {
            return response()->json($data);
        }
        
        return response()->json(['error' => 'Could not track shipment'], 400);
    }
}
