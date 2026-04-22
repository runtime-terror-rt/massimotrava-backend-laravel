<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactsController extends Controller
{
    /**
     * Store message and send email to admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string',
            'email'   => 'required|email',
            'message' => 'required|string',
        ]);

        $contact = Contacts::create($validated);

        Mail::raw(
            "New Contact Message\n\nFrom: {$validated['email']}\n\nMessage:\n{$validated['message']}",
            function ($mail) use ($validated) {
                $mail->to('massimotravasupport@gmail.com')
                    ->subject('New Contact Message')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->replyTo($validated['email']);
            }
        );

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent to admin successfully!',
            'data'    => $contact,
        ]);
    }

    /**
     * Display all contact messages (paginated)
     */
    public function index()
    {
        try {
            $perPage = 10;
            $contacts = Contacts::orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'All contact messages retrieved successfully',
                'data'    => $contacts->items(),
                'meta' => [
                    'current_page' => $contacts->currentPage(),
                    'last_page'    => $contacts->lastPage(),
                    'per_page'     => $contacts->perPage(),
                    'total'        => $contacts->total(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve contacts: ' . $e->getMessage(),
            ], 500);
        }
    }
}
