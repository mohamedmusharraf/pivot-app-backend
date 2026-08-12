<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Http\Requests\Admin\ActivityRequest;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::query();

        if ($request->filled('search')) {
            $query->where('activity_title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tier')) {
            $query->where('tier', 'like', '%' . $request->tier . '%');
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.activities.index', compact('activities'));
    }

    public function store(ActivityRequest $request)
    {
        $data = $request->validated();
        $data['neurodivergent_friendly'] = $request->has('neurodivergent_friendly') ? 'Yes' : 'No';
        $data['status'] = $data['status'] ?? 'active';

        Activity::create($data);

        return redirect()->route('admin.activities.index')->with('success', 'Activity created successfully.');
    }

    public function show($id)
    {
        $activity = Activity::findOrFail($id);
        return view('admin.activities.show', compact('activity'));
    }

    public function update(ActivityRequest $request, $id)
    {
        $activity = Activity::findOrFail($id);
        $data = $request->validated();
        $data['neurodivergent_friendly'] = $request->has('neurodivergent_friendly') ? 'Yes' : 'No';

        $activity->update($data);

        return redirect()->route('admin.activities.index')->with('success', 'Activity updated successfully.');
    }

    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        return redirect()->route('admin.activities.index')->with('success', 'Activity deleted successfully.');
    }
}