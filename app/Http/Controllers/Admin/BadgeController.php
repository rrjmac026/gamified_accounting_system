<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Http\Requests\BadgeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Better to paginate if many badges
        $badges = Badge::paginate(10); 
        return view('admin.badges.index', compact('badges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.badges.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BadgeRequest $request)
    {
        $validated = $request->validated();
        
        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('badges', 'public');
            $validated['icon_path'] = $path;
        }

        Badge::create($validated);

        return redirect()
            ->route('admin.badges.index')
            ->with('success', 'Badge created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Badge $badge)
    {
        return view('admin.badges.show', compact('badge'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Badge $badge)
    {
        return view('admin.badges.edit', compact('badge'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BadgeRequest $request, Badge $badge)
    {
        $validated = $request->validated();
        
        if ($request->hasFile('icon')) {
            // Delete old icon if exists
            if ($badge->icon_path && Storage::disk('public')->exists($badge->icon_path)) {
                Storage::disk('public')->delete($badge->icon_path);
            }
            
            // Store new icon
            $validated['icon_path'] = $request->file('icon')->store('badges', 'public');
        }

        $badge->update($validated);

        return redirect()
            ->route('admin.badges.index')
            ->with('success', 'Badge updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Badge $badge)
    {
        $badge->delete();

        return redirect()
            ->route('admin.badges.index')
            ->with('success', 'Badge deleted successfully!');
    }
}
