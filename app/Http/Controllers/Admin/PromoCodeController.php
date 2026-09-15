<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PromoCodeController extends Controller
{
    public function index()
    {
        $promoCodes = PromoCode::query()
            ->withCount(['redemptions as redeemed_count' => fn ($query) => $query->where('status', 'redeemed')])
            ->latest()
            ->paginate(20);

        $promoUsers = User::query()->orderBy('email')->limit(250)->get(['id', 'name', 'email']);

        return view('admin.promo-codes.index', compact('promoCodes', 'promoUsers'));
    }

    public function store(Request $request)
    {
        $request->merge(['code' => strtoupper(trim((string) $request->input('code')))]);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:100', 'regex:/^[A-Z0-9_-]+$/', Rule::unique('promo_codes', 'code')],
            'discount_percent' => ['required', 'integer', 'min:1', 'max:100'],
            'offer_tag' => ['required', 'string', 'max:255'],
            'max_redemptions' => ['required', 'integer', 'min:1'],
            'assigned_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'one_per_user' => ['nullable', 'boolean'],
            'applicable_tier' => ['nullable', 'string', 'max:100'],
            'applicable_product_id' => ['nullable', 'string', 'max:255'],
            'expires_at' => ['nullable', 'date'],
            'active' => ['nullable', 'boolean'],
        ]);

        PromoCode::create([
            ...$validated,
            'one_per_user' => $request->boolean('one_per_user'),
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('admin.promo-codes.index')->with('success', 'Promo code created successfully.');
    }
}
 