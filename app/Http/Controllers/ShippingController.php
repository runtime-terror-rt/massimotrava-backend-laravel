<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DhlShippingService;

class ShippingController extends Controller
{

    public function requestPickup(Request $request, DhlShippingService $dhlService)
    {
        $payload = [
            "plannedShipmentDateAndTime" => "2026-04-25T10:00:00Z",
            "pickupDetails" => [
                "postalAddress" => [
                    "postalCode" => "1212",
                    "cityName" => "Dhaka",
                    "countryCode" => "BD",
                    "streetLines" => ["House 12, Road 5"]
                ],
                "contactInformation" => [
                    "personName" => "Md. Mahbubur Rahman",
                    "phone" => "+8801700000000",
                    "email" => "mahbub@example.com"
                ]
            ],
            "totalWeight" => ["value" => 1.5, "unit" => "kg"]
        ];

        $response = $dhlService->schedulePickup($payload);
        return response()->json($response);
    }
    // public function requestPickup(Request $request, DhlShippingService $dhlService)
    // {
    //     $payload = [
    //         "plannedShipmentDateAndTime" => "2026-04-25T10:00:00Z", // 'Z' যুক্ত করুন
    //         "pickupDetails" => [
    //             "postalAddress" => [
    //                 "postalCode" => "1212",
    //                 "cityName" => "Dhaka",
    //                 "countryCode" => "BD",
    //                 "streetLines" => ["House 12, Road 5"]
    //             ],
    //             "contactInformation" => [
    //                 "personName" => "Md. Mahbubur Rahman",
    //                 "phone" => "+8801XXXXXXXXX",
    //                 "email" => "your-email@example.com" // ইমেইলটি যোগ করা ভালো
    //             ]
    //         ],
    //         // প্রোডাকশন বা ইন্টিগ্রেশন এনভায়রনমেন্ট অনুযায়ী প্রয়োজন হলে এটি যোগ করুন
    //         "totalWeight" => ["value" => 1.5, "unit" => "kg"] 
    //     ];

    //     $response = $dhlService->schedulePickup($payload);

    //     if (isset($response['dispatchConfirmationNumber'])) {
    //         return response()->json(['status' => 'success', 'data' => $response]);
    //     }

    //     return response()->json(['status' => 'error', 'message' => $response], 400);
    // }

    public function track(Request $request, DhlShippingService $dhl)
    {
        $trackingNumber = $request->input('number');
        $data = $dhl->trackShipment($trackingNumber);

        if ($data) {
            return response()->json($data);
        }
        
        return response()->json(['error' => 'Could not track shipment'], 400);
    }

    public function trackShipment($trackingNumber, DhlShippingService $dhlService)
    {
        try {
            $response = $dhlService->getTrackingInfo($trackingNumber);

            return response()->json([
                'status' => 'success',
                'data' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tracking info not found or API error',
                'details' => $e->getMessage()
            ], 404);
        }
    }
}
