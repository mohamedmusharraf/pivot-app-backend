<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RevenueCatService;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index(Request $request, RevenueCatService $revenueCat)
    {
        $overview = $revenueCat->getMetricsOverview();

        $currentPage = (int) $request->query('page', 1);
        $startingAfter = $request->query('starting_after');
        $customersResponse = $revenueCat->getCustomers(20, $startingAfter);
        $customerItems = $customersResponse['items'] ?? [];
        $nextPageUrl = $customersResponse['next_page'] ?? null;
        
        $nextStartingAfter = null;
        if ($nextPageUrl) {
            parse_str((string) parse_url($nextPageUrl, PHP_URL_QUERY), $query);
            $nextStartingAfter = $query['starting_after'] ?? null;
        }

        $countries = \App\Models\Country::pluck('name', 'iso_code');

        $realCustomers = collect($customerItems)->map(function ($customer) use ($revenueCat, $countries) {
            $customerId = $customer['id'] ?? null;

            $subData = $customerId ? $revenueCat->getCustomerSubscriptions($customerId) : [];
            $subscriptions = $subData['items'] ?? [];
            $primarySub = $subscriptions[0] ?? [];

            // DEBUG: Log the primary subscription to inspect available fields like product and revenue
            if (!empty($primarySub)) {  
                \Illuminate\Support\Facades\Log::info('RevenueCat Subscription Data: ', $primarySub);
            }

            $user = null;
            if ($customerId && is_numeric($customerId)) {
                $user = User::find((int) $customerId);
            } elseif ($customerId) {
                $user = User::where('email', $customerId)->first();
            }

            $userName = $user?->name;
            if (!$userName) {
                if (str_starts_with($customerId ?? '', '$RCAnonymousID:')) {
                    $userName = 'Anonymous User';
                } else {
                    $userName = 'Customer #' . $customerId;
                }
            }

            $status = 'Expired';
            if (!empty($primarySub['expires_at'])) {
                $expiresAt = Carbon::parse($primarySub['expires_at']);
                if ($expiresAt->isFuture()) {
                    $status = 'Active';
                }
            } elseif (($primarySub['status'] ?? '') === 'active') {
                $status = 'Active';
            }
            
            $isoCode = $customer['last_seen_country'] ?? null;
            $countryName = $isoCode ? ($countries[$isoCode] ?? $isoCode) : 'N/A';

            $productId = $primarySub['product']['identifier'] ?? $primarySub['product']['id'] ?? $primarySub['product_id'] ?? $primarySub['product_identifier'] ?? $primarySub['entitlement_id'] ?? null;
            $tier = 'N/A';
            if ($productId) {
                if (stripos($productId, 'tier 3') !== false || stripos($productId, 'tier_3') !== false || stripos($productId, 'tier-3') !== false) {
                    $tier = 'Tier 3';
                } elseif (stripos($productId, 'tier 2') !== false || stripos($productId, 'tier_2') !== false || stripos($productId, 'tier-2') !== false) {
                    $tier = 'Tier 2';
                } elseif (stripos($productId, 'tier 1') !== false || stripos($productId, 'tier_1') !== false || stripos($productId, 'tier-1') !== false) {
                    $tier = 'Tier 1';
                } else {
                    // Fallback to formatting the product id nicely if it doesn't match a standard tier
                    $tier = ucwords(str_replace(['_', '-'], ' ', explode(':', $productId)[0]));
                }
            }

            return [
                'customer_id'   => $customerId ?? 'N/A',
                'user_name'     => $userName,
                'has_real_name' => !empty($user?->name),
                'user_email'    => $user?->email ?? 'N/A',
                'status'        => $status,
                'tier'          => $tier,
                'store'         => $primarySub['store'] ?? 'N/A',
                'subscription_id' => $primarySub['id'] ?? 'N/A',
                'auto_renewal_status' => $primarySub['auto_renewal_status'] ?? 'N/A',
                'gross_revenue' => $primarySub['total_revenue_in_usd']['gross'] ?? 0,
                'subscription_starts_at' => !empty($primarySub['starts_at']) ? (is_numeric($primarySub['starts_at']) ? date('M d, Y', $primarySub['starts_at'] / 1000) : \Carbon\Carbon::parse($primarySub['starts_at'])->format('M d, Y')) : 'N/A',
                'subscription_ends_at' => !empty($primarySub['ends_at']) ? (is_numeric($primarySub['ends_at']) ? date('M d, Y', $primarySub['ends_at'] / 1000) : \Carbon\Carbon::parse($primarySub['ends_at'])->format('M d, Y')) : 'N/A',
                'first_seen'    => isset($customer['first_seen_at']) && $customer['first_seen_at'] ? date('M d, Y', $customer['first_seen_at'] / 1000) : 'N/A',
                'last_seen'     => isset($customer['last_seen_at']) && $customer['last_seen_at'] ? date('M d, Y', $customer['last_seen_at'] / 1000) : 'N/A',
                'app_version'   => $customer['last_seen_app_version'] ?? 'N/A',
                'country'       => $countryName,
                'platform'      => $customer['last_seen_platform'] ?? 'N/A',
                'os_version'    => $customer['last_seen_platform_version'] ?? 'N/A',
            ];
        })->filter(function ($customer) {
            return $customer['has_real_name'] === true && $customer['tier'] !== 'Tier 1';
        })->values();

        return view('admin.subscriptions.index', [
            'overview'          => $overview,
            'subscriptions'     => ['items' => $realCustomers->all()],
            'nextStartingAfter' => $nextStartingAfter,
            'currentPage'       => $currentPage,
        ]);
    }
}