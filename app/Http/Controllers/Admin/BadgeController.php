<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Http\Requests\BadgeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\Loggable;

class BadgeController extends Controller
{
    use Loggable;

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

        $badge = Badge::create($validated);

        $this->logActivity(
            "Created Badge",
            "Badge",
            $badge->id,
            [
                'name' => $badge->name,
                'type' => $badge->criteria,
                'xp_threshold' => $badge->xp_threshold
            ]
        );

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
        $originalData = $badge->toArray();
        
        if ($request->hasFile('icon')) {
            // Delete old icon if exists
            if ($badge->icon_path && Storage::disk('public')->exists($badge->icon_path)) {
                Storage::disk('public')->delete($badge->icon_path);
            }
            
            // Store new icon
            $validated['icon_path'] = $request->file('icon')->store('badges', 'public');
        }

        $badge->update($validated);

        $this->logActivity(
            "Updated Badge",
            "Badge",
            $badge->id,
            [
                'original' => $originalData,
                'changes' => $badge->getChanges()
            ]
        );

        return redirect()
            ->route('admin.badges.index')
            ->with('success', 'Badge updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Badge $badge)
    {
        $badgeData = $badge->toArray();
        $badge->delete();

        $this->logActivity(
            "Deleted Badge",
            "Badge",
            $badge->id,
            [
                'name' => $badgeData['name'],
                'type' => $badgeData['criteria'],
                'xp_threshold' => $badgeData['xp_threshold']
            ]
        );

        return redirect()
            ->route('admin.badges.index')
            ->with('success', 'Badge deleted successfully!');
    }
}
