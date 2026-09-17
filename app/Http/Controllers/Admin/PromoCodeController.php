<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Models\PromoCodeRedemption;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PromoCodeController extends Controller
{
    public function index()
    {
        $promoCodes = PromoCode::query()
            ->withCount(['redemptions as redeemed_count' => fn($query) => $query->where('status', 'redeemed')])
            ->latest()
            ->paginate(20);
        $redemptions = PromoCodeRedemption::query()
            ->with(['promoCode:id,code', 'user:id,name'])
            ->where('status', 'redeemed')
            ->latest('redeemed_at')
            ->paginate(20, ['*'], 'redemptions_page');

        $promoUsers = User::query()->orderBy('email')->limit(250)->get(['id', 'name', 'email']);

        return view('admin.promo-codes.index', compact('promoCodes', 'promoUsers', 'redemptions'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        PromoCode::create([
            ...$validated,
            'one_per_user' => $request->boolean('one_per_user'),
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('admin.promo-codes.index')->with('success', 'Promo code created successfully.');
    }

    public function edit(PromoCode $promoCode)
    {
        $promoUsers = User::query()->orderBy('email')->limit(250)->get(['id', 'name', 'email']);

        return view('admin.promo-codes.edit', compact('promoCode', 'promoUsers'));
    }

    public function update(Request $request, PromoCode $promoCode)
    {
        $validated = $this->validatedData($request, $promoCode);

        $promoCode->update([
            ...$validated,
            'one_per_user' => $request->boolean('one_per_user'),
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('admin.promo-codes.index')->with('success', 'Promo code updated successfully.');
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();

        return redirect()->route('admin.promo-codes.index')->with('success', 'Promo code deleted successfully.');
    }

    public function toggleStatus(PromoCode $promoCode)
    {
        $promoCode->update(['active' => ! $promoCode->active]);

        return redirect()->route('admin.promo-codes.index')->with('success', 'Promo code status updated successfully.');
    }

    private function validatedData(Request $request, ?PromoCode $promoCode = null): array
    {
        $request->merge(['code' => strtoupper(trim((string) $request->input('code')))]);

        return $request->validate([
            'code' => ['required', 'string', 'max:100', 'regex:/^[A-Z0-9_-]+$/', Rule::unique('promo_codes', 'code')->ignore($promoCode?->id)],
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
    }
}
