<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Http\Requests\BadgeRequest;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::all();
        return view('badges.index', compact('badges'));
    }

    public function create()
    {
        return view('badges.create');
    }

    public function store(BadgeRequest $request)
    {
        Badge::create($request->validated());
        return redirect()->route('badges.index')->with('success', 'Badge created successfully!');
    }

    public function show(Badge $badge)
    {
        return view('badges.show', compact('badge'));
    }

    public function edit(Badge $badge)
    {
        return view('badges.edit', compact('badge'));
    }

    public function update(BadgeRequest $request, Badge $badge)
    {
        $badge->update($request->validated());
        return redirect()->route('badges.index')->with('success', 'Badge updated successfully!');
    }

    public function destroy(Badge $badge)
    {
        $badge->delete();
        return redirect()->route('badges.index')->with('success', 'Badge deleted successfully!');
    }
}
