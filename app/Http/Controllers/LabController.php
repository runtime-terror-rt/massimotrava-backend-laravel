<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LabController extends Controller
{
    /**
     * Display a listing of the labs.
     */
    public function index(Request $request)
    {

        $labs = Lab::paginate(10);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'data' => $labs
            ], 200);
        }

        return view('admin.labs.index', compact('labs'));
    }

    /**
     * Store or Update lab information.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'             => 'nullable|exists:labs,id',
            'name'           => 'required|string|max:255',
            'street_address' => 'required|string',
            'city'           => 'required|string',
            'province'       => 'required|string|size:2', // eg: MI, RM, TO
            'postal_code'    => 'required|string|max:10', // CAP (e.g., 20121)
            'phone'          => 'required|string',       // Courier contact
            'contact_email'  => 'nullable|email',
            'country'        => 'nullable'
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $lab = Lab::updateOrCreate(
                ['id' => $request->id],
                [
                    'name'           => $request->name,
                    'street_address' => $request->street_address,
                    'city'           => $request->city,
                    'province'       => strtoupper($request->province),
                    'postal_code'    => $request->postal_code,
                    'phone'          => $request->phone,
                    'contact_email'  => $request->contact_email,
                    'country'        => $request->country ?? 'Italy',
                    'status'         => $request->status ?? 1,
                ]
            );

            $isUpdate = $request->filled('id');
            $message = $isUpdate ? 'Lab updated successfully' : 'Lab created successfully';

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'  => 'success',
                    'message' => $message,
                    'data'    => $lab
                ], $isUpdate ? 200 : 201);
            }

            return redirect()->route('admin.labs.index')->with('success', $message);

        } catch (\Exception $e) {
            Log::error("Lab Store Error: " . $e->getMessage());
            
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong!'], 500);
            }
            return back()->with('error', 'Failed to save lab information.');
        }
    }


    /**
     * Remove the specified lab.
     */
    public function destroy(Request $request, $id)
    {
        $lab = Lab::find($id);

        if (!$lab) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['status' => 'error', 'message' => 'Lab not found'], 404);
            }
            return back()->with('error', 'Lab not found');
        }

        $lab->delete();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['status' => 'success', 'message' => 'Lab deleted successfully']);
        }

        return back()->with('success', 'Lab deleted successfully');
    }
}