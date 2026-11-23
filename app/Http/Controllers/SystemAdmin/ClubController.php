<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function index()
    {
        // Fetch all clubs, including the user assigned as their admin
        $clubs = Club::with('admin')->get();
        
        return view('system-admin.clubs.index', compact('clubs'));
    }

    public function create()
    {
        // This is no longer needed since we are using a modal, but safe to leave here
        return view('system-admin.clubs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Changed from required to nullable
            'email' => 'nullable|email|unique:clubs,email',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Club::create($validated);

        return redirect()->route('system_admin.clubs.index')
            ->with('success', 'Club created successfully.');
    }

    public function edit(Club $club)
    {
        return view('system-admin.clubs.edit', compact('club'));
    }

    public function update(Request $request, Club $club)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Made nullable here as well so the system admin can clear it if needed
            'email' => 'nullable|email|unique:clubs,email,' . $club->id, 
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $club->update($validated);

        return redirect()->route('system_admin.clubs.index')
            ->with('success', 'Club updated successfully.');
    }

    public function destroy(Club $club)
    {
        $club->delete();

        return redirect()->route('system_admin.clubs.index')
            ->with('success', 'Club deleted successfully.');
    }
}