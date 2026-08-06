<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ActivityRequest;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('hobby');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('activity_title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        if ($request->filled('tier')) {
            $query->where('tier', $request->input('tier'));
        }

        $activities = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.activities.index', compact('activities'));
    }

    public function store(ActivityRequest $request)
    {
        Activity::create($request->validated());
        return redirect()->route('admin.activities.index')->with('success', 'Activity created successfully.');
    }

    public function show(Activity $activity)
    {
        return view('admin.activities.show', compact('activity'));
    }

    public function update(ActivityRequest $request, Activity $activity)
    {
        $activity->update($request->validated());
        return redirect()->route('admin.activities.index')->with('success', 'Activity updated successfully.');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->route('admin.activities.index')->with('success', 'Activity deleted successfully.');
    }
}