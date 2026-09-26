<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RevenueCatService;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request, RevenueCatService $revenueCat)
    {
        // 1. Fetch live subscription records directly from RevenueCat v2 API
        $response = $revenueCat->getSubscriptions(50);
        $items = $response['items'] ?? [];

        // 2. Map RevenueCat response fields
        $formattedSubscriptions = collect($items)->map(function ($sub) {
            $appUserId = $sub['app_user_id'] ?? null;
            $user = $appUserId ? User::find($appUserId) : null;

            $storeMap = [
                'play_store' => 'Google Play',
                'app_store'  => 'App Store',
                'stripe'     => 'Stripe',
                'amazon'     => 'Amazon',
            ];

            $storeKey = strtolower($sub['store'] ?? '');
            $storeName = $storeMap[$storeKey] ?? ($sub['store'] ?? 'Unknown');

            $productId = $sub['product_id'] ?? '';
            $period = str_contains(strtolower($productId), 'annual') || str_contains(strtolower($productId), 'year')
                ? 'Annual'
                : 'Monthly';

            return [
                'subscription_id' => $sub['id'] ?? 'N/A',
                'user_name'       => $user?->name ?? 'User #' . ($appUserId ?? 'N/A'),
                'user_email'      => $user?->email ?? 'N/A',
                'product_id'      => $productId ?: 'N/A',
                'store_platform'  => $storeName,
                'price'           => $sub['price_in_purchased_currency'] ?? 0.00,
                'currency'        => $sub['currency'] ?? 'USD',
                'status'          => ucfirst(strtolower($sub['status'] ?? 'Active')),
                'environment'     => ucfirst(strtolower($sub['environment'] ?? 'Production')),
                'purchase_date'   => isset($sub['starts_at']) ? date('M d, Y', $sub['starts_at'] / 1000) : 'N/A',
                'expiry_date'     => isset($sub['expires_at']) ? date('M d, Y', $sub['expires_at'] / 1000) : 'N/A',
                'auto_renew'      => ($sub['auto_resume'] ?? true) ? 'Yes' : 'No',
            ];
        });

        // 3. Filter by search / status / store if requested
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $formattedSubscriptions = $formattedSubscriptions->filter(function ($item) use ($search) {
                return str_contains(strtolower($item['user_name']), $search) ||
                    str_contains(strtolower($item['user_email']), $search) ||
                    str_contains(strtolower($item['subscription_id']), $search);
            });
        }

        if ($request->filled('status')) {
            $formattedSubscriptions = $formattedSubscriptions->where('status', ucfirst($request->status));
        }

        if ($request->filled('store')) {
            $formattedSubscriptions = $formattedSubscriptions->where('store_platform', $request->store);
        }

        // 4. Calculate Metric Cards
        $metrics = [
            'active'          => $formattedSubscriptions->where('status', 'Active')->count(),
            'expired'         => $formattedSubscriptions->where('status', 'Expired')->count(),
            'cancelled'       => $formattedSubscriptions->where('status', 'Canceled')->count() + $formattedSubscriptions->where('status', 'Cancelled')->count(),
            'monthly_revenue' => $formattedSubscriptions->where('status', 'Active')->sum('price'),
        ];

        return view('admin.subscriptions.index', compact('formattedSubscriptions', 'metrics') + ['activePage' => 'subscriptions']);
    }
}
