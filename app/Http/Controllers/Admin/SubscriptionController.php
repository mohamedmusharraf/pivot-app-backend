<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RevenueCatService;
use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    private const PER_PAGE = 15;

    public function index(Request $request, RevenueCatService $revenueCat)
    {
        $overview = $revenueCat->getMetricsOverview();

        $countries = Country::pluck('name', 'iso_code');

        // Paginate users who have a subscription and exist in the DB
        $paginator = User::whereHas('subscription')
            ->with('subscription')
            ->orderBy('id', 'desc')
            ->paginate(self::PER_PAGE, ['*'], 'page', (int) $request->query('page', 1));

        $currentPage = $paginator->currentPage();
        $totalPages  = $paginator->lastPage();
        $total       = $paginator->total();

        $realCustomers = collect($paginator->items())->map(function (User $user) use ($revenueCat, $countries) {
            $customerId = (string) $user->id;

            $subData       = $revenueCat->getCustomerSubscriptions($customerId);
            $subscriptions = $subData['items'] ?? [];
            $primarySub    = $subscriptions[0] ?? [];

            // Determine status
            $status = 'Expired';
            if (!empty($primarySub['expires_at'])) {
                if (Carbon::parse($primarySub['expires_at'])->isFuture()) {
                    $status = 'Active';
                }
            } elseif (($primarySub['status'] ?? '') === 'active') {
                $status = 'Active';
            }

            // Country
            $isoCode     = $primarySub['last_seen_country'] ?? null;
            $countryName = $isoCode ? ($countries[$isoCode] ?? $isoCode) : 'N/A';

            // Tier from product identifier
            $productId = $primarySub['product']['identifier']
                ?? $primarySub['product']['id']
                ?? $primarySub['product_id']
                ?? $primarySub['product_identifier']
                ?? $primarySub['entitlement_id']
                ?? null;

            $tier = 'N/A';
            if ($productId) {
                if (stripos($productId, 'tier 3') !== false || stripos($productId, 'tier_3') !== false || stripos($productId, 'tier-3') !== false) {
                    $tier = 'Tier 3';
                } elseif (stripos($productId, 'tier 2') !== false || stripos($productId, 'tier_2') !== false || stripos($productId, 'tier-2') !== false) {
                    $tier = 'Tier 2';
                } elseif (stripos($productId, 'tier 1') !== false || stripos($productId, 'tier_1') !== false || stripos($productId, 'tier-1') !== false) {
                    $tier = 'Tier 1';
                } else {
                    $tier = ucwords(str_replace(['_', '-'], ' ', explode(':', $productId)[0]));
                }
            }

            return [
                'user_name'              => $user->name,
                'user_email'             => $user->email ?? 'N/A',
                'status'                 => $status,
                'tier'                   => $tier,
                'store'                  => $primarySub['store'] ?? 'N/A',
                'subscription_id'        => $primarySub['id'] ?? 'N/A',
                'auto_renewal_status'    => $primarySub['auto_renewal_status'] ?? 'N/A',
                'gross_revenue'          => $primarySub['total_revenue_in_usd']['gross'] ?? 0,
                'subscription_starts_at' => !empty($primarySub['starts_at'])
                    ? (is_numeric($primarySub['starts_at'])
                        ? date('M d, Y', $primarySub['starts_at'] / 1000)
                        : Carbon::parse($primarySub['starts_at'])->format('M d, Y'))
                    : 'N/A',
                'subscription_ends_at'   => !empty($primarySub['ends_at'])
                    ? (is_numeric($primarySub['ends_at'])
                        ? date('M d, Y', $primarySub['ends_at'] / 1000)
                        : Carbon::parse($primarySub['ends_at'])->format('M d, Y'))
                    : 'N/A',
                'country'                => $countryName,
                'platform'               => $primarySub['platform'] ?? 'N/A',
                'os_version'             => 'N/A',
            ];
        })->filter(fn($c) => $c['tier'] !== 'Tier 1')->values();

        return view('admin.subscriptions.index', [
            'overview'      => $overview,
            'subscriptions' => ['items' => $realCustomers->all()],
            'currentPage'   => $currentPage,
            'totalPages'    => $totalPages,
            'total'         => $total,
            'paginator'     => $paginator,
        ]);
    }
}