@extends('layouts.admin')

@section('content')
<ul class="breadcrumb"><li><a href="{{ route('admin.dashboard') }}">Admin</a></li><li><i class="fa-solid fa-chevron-right" style="font-size: .65rem;"></i></li><li style="color: var(--text-heading); font-weight: 600;">Promo Codes</li></ul>

<div class="promo-layout">
    <section class="card">
        <h2>Create Promo Code</h2>
        <p class="muted">Authorize a Pivot code to use an existing Google Play Developer-determined offer.</p>
        @if(session('success')) <div class="success-message">{{ session('success') }}</div> @endif
        <form method="POST" action="{{ route('admin.promo-codes.store') }}">
            @csrf
            <div class="form-grid">
                <div><label>Promo code</label><input name="code" value="{{ old('code') }}" placeholder="PIVOT20" required maxlength="100" style="text-transform:uppercase">@error('code')<small class="error">{{ $message }}</small>@enderror</div>
                <div><label>Discount %</label><input type="number" name="discount_percent" value="{{ old('discount_percent') }}" min="1" max="100" placeholder="20" required>@error('discount_percent')<small class="error">{{ $message }}</small>@enderror</div>
                <div><label>Google Play offer tag</label><input name="offer_tag" value="{{ old('offer_tag') }}" placeholder="pivot-20-off" required>@error('offer_tag')<small class="error">{{ $message }}</small>@enderror</div>
                <div><label>Maximum redemptions</label><input type="number" name="max_redemptions" value="{{ old('max_redemptions', 1) }}" min="1" required>@error('max_redemptions')<small class="error">{{ $message }}</small>@enderror</div>
                <div><label>Assigned user <span>(optional)</span></label><select name="assigned_user_id"><option value="">Any eligible user</option>@foreach($promoUsers as $user)<option value="{{ $user->id }}" @selected((string) old('assigned_user_id') === (string) $user->id)>{{ $user->email }}{{ $user->name ? ' — '.$user->name : '' }}</option>@endforeach</select></div>
                <div><label>Applicable product <span>(optional)</span></label><input name="applicable_product_id" value="{{ old('applicable_product_id') }}" placeholder="tier_2_android"></div>
                <div><label>Applicable tier <span>(optional)</span></label><input name="applicable_tier" value="{{ old('applicable_tier') }}" placeholder="tier_2"></div>
                <div><label>Expires at <span>(optional)</span></label><input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"></div>
            </div>
            <div class="toggles"><label><input type="checkbox" name="one_per_user" value="1" @checked(old('one_per_user', true))> One redemption per user</label><label><input type="checkbox" name="active" value="1" @checked(old('active', true))> Active immediately</label></div>
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-plus"></i> Create Promo Code</button>
        </form>
    </section>
    <section class="card">
        <div class="table-header"><div><h2>Promo Codes</h2><p class="muted">Redemptions are counted only after a successful purchase.</p></div></div>
        <div style="overflow-x:auto"><table><thead><tr><th>Code</th><th>Play offer</th><th>Uses</th><th>Expires</th><th>Status</th></tr></thead><tbody>
        @forelse($promoCodes as $promoCode)<tr><td><strong>{{ $promoCode->code }}</strong><small>{{ $promoCode->discount_percent }}% off</small></td><td><code>{{ $promoCode->offer_tag }}</code></td><td>{{ $promoCode->redeemed_count }} / {{ $promoCode->max_redemptions }}</td><td>{{ $promoCode->expires_at?->format('d M Y, H:i') ?? 'Never' }}</td><td><span class="badge badge-{{ $promoCode->active ? 'success' : 'warning' }}">{{ $promoCode->active ? 'Active' : 'Inactive' }}</span></td></tr>
        @empty<tr><td colspan="5" class="empty">No promo codes created yet.</td></tr>@endforelse
        </tbody></table></div>
        <div class="pagination">{{ $promoCodes->links('partials.pagination') }}</div>
    </section>
</div>
<style>
.promo-layout{display:grid;gap:1.5rem}.promo-layout h2{margin:0;color:var(--text-heading);font-size:1.2rem}.muted{margin:.4rem 0 1.25rem;color:var(--text-muted);font-size:.85rem}.form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem}.form-grid>div{display:flex;flex-direction:column;gap:.4rem}.form-grid label{color:var(--text-heading);font-size:.8125rem;font-weight:600}.form-grid label span{color:var(--text-muted);font-weight:400}.form-grid input,.form-grid select{border:1px solid var(--border-color);border-radius:.5rem;background:var(--bg-primary);color:var(--text-heading);padding:.65rem .75rem;width:100%}.toggles{display:flex;flex-wrap:wrap;gap:1rem;margin:1.25rem 0;color:var(--text-heading);font-size:.875rem}.toggles label{display:flex;gap:.45rem;align-items:center}.error{color:var(--danger)}.success-message{padding:.75rem 1rem;margin-bottom:1rem;border-radius:.5rem;background:var(--success-light);color:var(--success)}table{width:100%;border-collapse:collapse;font-size:.85rem}th,td{padding:.8rem .5rem;border-bottom:1px solid var(--border-color);text-align:left;color:var(--text-heading)}th{font-size:.75rem;text-transform:uppercase;color:var(--text-muted)}td small{display:block;margin-top:.2rem;color:var(--text-muted)}code{color:var(--primary)}.empty{text-align:center;color:var(--text-muted)}@media(max-width:768px){.form-grid{grid-template-columns:1fr}}
</style>
@endsection
