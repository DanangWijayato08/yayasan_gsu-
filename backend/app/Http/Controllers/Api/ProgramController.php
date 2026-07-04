<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        return response()->json(Program::where('active', true)->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'collected' => 'nullable|integer|min:0',
            'target' => 'nullable|integer|min:0',
            'image' => 'nullable|url',
            'active' => 'boolean',
        ]);

        $program = Program::create($validated);
        return response()->json($program, 201);
    }

    public function show(Program $program)
    {
        return response()->json($program, 200);
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'category' => 'string|max:100',
            'collected' => 'integer|min:0',
            'target' => 'integer|min:0',
            'image' => 'nullable|url',
            'active' => 'boolean',
        ]);

        $program->update($validated);
        return response()->json($program, 200);
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return response()->json(['message' => 'Program deleted'], 200);
    }
}
