@extends('layouts.admin')

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: .65rem;"></i></li>
    <li><a href="{{ route('admin.promo-codes.index') }}">Promo Codes</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: .65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">Edit {{ $promoCode->code }}</li>
</ul>

<section class="card promo-edit-card">
    <div class="table-header">
        <div>
            <h2>Edit Promo Code</h2>
            <p class="muted">Update the code settings and availability.</p>
        </div><a class="btn btn-secondary" href="{{ route('admin.promo-codes.index') }}"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
    @if(session('success')) <div class="success-message">{{ session('success') }}</div> @endif
    <form method="POST" action="{{ route('admin.promo-codes.update', $promoCode) }}">
        @csrf @method('PUT')
        <div class="form-grid">
            <div><label>Promo code</label><input name="code" value="{{ old('code', $promoCode->code) }}" required maxlength="100" style="text-transform:uppercase">@error('code')<small class="error">{{ $message }}</small>@enderror</div>
            <div><label>Discount %</label><input type="number" name="discount_percent" value="{{ old('discount_percent', $promoCode->discount_percent) }}" min="1" max="100" required>@error('discount_percent')<small class="error">{{ $message }}</small>@enderror</div>
            <div><label>Google Play offer tag</label><input name="offer_tag" value="{{ old('offer_tag', $promoCode->offer_tag) }}" required>@error('offer_tag')<small class="error">{{ $message }}</small>@enderror</div>
            <div><label>Maximum redemptions</label><input type="number" name="max_redemptions" value="{{ old('max_redemptions', $promoCode->max_redemptions) }}" min="1" required>@error('max_redemptions')<small class="error">{{ $message }}</small>@enderror</div>
            <div><label>Assigned user <span>(optional)</span></label><select name="assigned_user_id">
                    <option value="">Any eligible user</option>@foreach($promoUsers as $user)<option value="{{ $user->id }}" @selected((string) old('assigned_user_id', $promoCode->assigned_user_id) === (string) $user->id)>{{ $user->email }}{{ $user->name ? ' — '.$user->name : '' }}</option>@endforeach
                </select></div>
            <div><label>Applicable product <span>(optional)</span></label><input name="applicable_product_id" value="{{ old('applicable_product_id', $promoCode->applicable_product_id) }}"></div>
            <div><label>Applicable tier <span>(optional)</span></label><input name="applicable_tier" value="{{ old('applicable_tier', $promoCode->applicable_tier) }}"></div>
            <div><label>Expires at <span>(optional)</span></label><input type="datetime-local" name="expires_at" value="{{ old('expires_at', $promoCode->expires_at?->format('Y-m-d\TH:i')) }}"></div>
        </div>
        <div class="toggles"><label><input type="checkbox" name="one_per_user" value="1" @checked(old('one_per_user', $promoCode->one_per_user))> One redemption per user</label><label><input type="checkbox" name="active" value="1" @checked(old('active', $promoCode->active))> Active</label></div>
        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-save"></i> Save Changes</button>
    </form>
</section>
<style>
    .promo-edit-card {
        max-width: 900px
    }

    .promo-edit-card h2 {
        margin: 0;
        color: var(--text-heading);
        font-size: 1.2rem
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem
    }

    .muted {
        margin: .4rem 0 1.25rem;
        color: var(--text-muted);
        font-size: .85rem
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem
    }

    .form-grid>div {
        display: flex;
        flex-direction: column;
        gap: .4rem
    }

    .form-grid label {
        color: var(--text-heading);
        font-size: .8125rem;
        font-weight: 600
    }

    .form-grid label span {
        color: var(--text-muted);
        font-weight: 400
    }

    .form-grid input,
    .form-grid select {
        border: 1px solid var(--border-color);
        border-radius: .5rem;
        background: var(--bg-primary);
        color: var(--text-heading);
        padding: .65rem .75rem;
        width: 100%
    }

    .toggles {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin: 1.25rem 0;
        color: var(--text-heading);
        font-size: .875rem
    }

    .toggles label {
        display: flex;
        gap: .45rem;
        align-items: center
    }

    .error {
        color: var(--danger)
    }

    .success-message {
        padding: .75rem 1rem;
        margin-bottom: 1rem;
        border-radius: .5rem;
        background: var(--success-light);
        color: var(--success)
    }

    @media(max-width:768px) {
        .form-grid {
            grid-template-columns: 1fr
        }

        .table-header {
            align-items: stretch;
            flex-direction: column
        }
    }
</style>
@endsection