<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return response()->json(Contact::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        $validated['status'] = 'unread';
        $contact = Contact::create($validated);
        return response()->json(['message' => 'Pesan terkirim', 'data' => $contact], 201);
    }

    public function show(Contact $contact)
    {
        return response()->json($contact, 200);
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'status' => 'string|in:unread,read',
        ]);

        $contact->update($validated);
        return response()->json($contact, 200);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json(['message' => 'Contact deleted'], 200);
    }
}
