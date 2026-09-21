<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $request = request();
        $perPage = min((int) $request->integer('per_page', 10), 50);
        $query = Contact::query();

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($search = $request->string('search')->toString()) {
            $likeOp = config('database.default') === 'pgsql' ? 'ilike' : 'like';
            $query->where(function ($q) use ($search, $likeOp) {
                $q->where('name', $likeOp, "%{$search}%")
                    ->orWhere('email', $likeOp, "%{$search}%")
                    ->orWhere('subject', $likeOp, "%{$search}%")
                    ->orWhere('message', $likeOp, "%{$search}%");
            });
        }

        return response()->json($query->latest()->paginate($perPage), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $validated['name'] = strip_tags(trim($validated['name']));
        if (!empty($validated['subject'])) {
            $validated['subject'] = strip_tags(trim($validated['subject']));
        }
        $validated['message'] = strip_tags(trim($validated['message']));
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
            'status' => 'string|in:unread,read,archived',
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
