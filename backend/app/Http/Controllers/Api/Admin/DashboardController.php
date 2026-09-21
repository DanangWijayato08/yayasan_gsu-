<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Program;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'programs_active' => Program::where('is_active', true)->count(),
            'galleries_total' => Gallery::count(),
            'messages_total' => Contact::count(),
            'messages_unread' => Contact::where('status', 'unread')->count(),
            'latest_messages' => Contact::latest()->limit(5)->get(),
        ]);
    }
}
