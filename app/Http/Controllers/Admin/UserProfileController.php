<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserProfileRequest;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function index(Request $request)
    {
        $query = UserProfile::with(['user', 'country']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        $profiles = $query->paginate(15)->withQueryString();

        return view('admin.user-profiles.index', compact('profiles'));
    }

    public function show($user_id)
    {
        $profile = UserProfile::with(['user', 'country', 'hobbies'])->where('user_id', $user_id)->firstOrFail();
        return view('admin.user-profiles.show', compact('profile'));
    }

    public function update(UserProfileRequest $request, $user_id)
    {
        $profile = UserProfile::where('user_id', $user_id)->firstOrFail();
        $profile->update($request->validated());

        return redirect()->route('admin.user-profiles.index')->with('success', 'User profile updated.');
    }

    public function destroy($user_id)
    {
        $profile = UserProfile::where('user_id', $user_id)->firstOrFail();
        $profile->delete();

        return redirect()->route('admin.user-profiles.index')->with('success', 'User profile deleted.');
    }
}