<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChallengePacksWebhook;
use App\Models\User;
use App\Http\Requests\Admin\ChallengePackRequest;
use Illuminate\Http\Request;

class ChallengePackController extends Controller
{
    public function index(Request $request)
    {
        $query = ChallengePacksWebhook::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('product_id', 'like', '%' . $request->search . '%')
                  ->orWhere('app_id', 'like', '%' . $request->search . '%')
                  ->orWhere('transaction_id', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('store')) {
            $query->where('store', $request->store);
        }

        $challengePacks = $query->orderBy('created_at', 'desc')->paginate(10);
        $users = User::select('id', 'name', 'email')->orderBy('name', 'asc')->get();

        return view('admin.challenge-packs.index', compact('challengePacks', 'users'));
    }

    public function store(ChallengePackRequest $request)
    {
        ChallengePacksWebhook::create($request->validated());

        return redirect()->route('admin.challenge-packs.index')->with('success', 'Challenge Pack created successfully.');
    }

    public function show($id)
    {
        $pack = ChallengePacksWebhook::with('user')->findOrFail($id);

        return view('admin.challenge-packs.show', compact('pack'));
    }

    public function update(ChallengePackRequest $request, $id)
    {
        $pack = ChallengePacksWebhook::findOrFail($id);
        $pack->update($request->validated());

        return redirect()->route('admin.challenge-packs.index')->with('success', 'Challenge Pack updated successfully.');
    }

    public function destroy($id)
    {
        $pack = ChallengePacksWebhook::findOrFail($id);
        $pack->delete();

        return redirect()->route('admin.challenge-packs.index')->with('success', 'Challenge Pack deleted successfully.');
    }
}