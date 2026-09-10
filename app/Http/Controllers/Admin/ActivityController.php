<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Hobby; // Import Hobby Model
use App\Http\Requests\Admin\ActivityRequest;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('hobby');

        if ($request->filled('search')) {
            $search = strtolower(trim($request->search));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(activity_title) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('tier')) {
            $tier = strtolower(trim($request->tier));
            $query->whereRaw('LOWER(tier) LIKE ?', ["%{$tier}%"]);
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(10);
        $hobbies = Hobby::orderBy('name', 'asc')->get(); // Fetch categories/hobbies

        return view('admin.activities.index', compact('activities', 'hobbies'));
    }

    public function show($id)
    {
        $activity = Activity::with('hobby')->findOrFail($id);
        return view('admin.activities.show', compact('activity'));
    }

    public function store(ActivityRequest $request)
    {
        $data = $request->validated();
        $data['neurodivergent_friendly'] = $request->has('neurodivergent_friendly') ? 'Yes' : 'No';
        $data['status'] = $data['status'] ?? 'active';

        Activity::create($data);

        return redirect()->route('admin.activities.index')->with('success', 'Activity created successfully.');
    }

    public function update(ActivityRequest $request, $id)
    {
        $activity = Activity::findOrFail($id);
        $data = $request->validated();

        $data['neurodivergent_friendly'] = $request->has('neurodivergent_friendly') ? 'Yes' : 'No';

        if (empty($data['hobby_id'])) {
            $data['hobby_id'] = $activity->hobby_id ?? 1;
        }

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
