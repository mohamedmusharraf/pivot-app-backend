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
        $overview = $revenueCat->getMetricsOverview();

        $customersResponse = $revenueCat->getCustomers(25);
        $customerItems = $customersResponse['items'] ?? [];

        $realCustomers = collect($customerItems)->map(function ($customer) use ($revenueCat) {
            $customerId = $customer['id'] ?? null;

            $subData = $customerId ? $revenueCat->getCustomerSubscriptions($customerId) : [];
            $subscriptions = $subData['items'] ?? [];
            $primarySub = $subscriptions[0] ?? [];

            $user = null;

            if ($customerId && is_numeric($customerId)) {
                $user = User::find($customerId);
            } elseif ($customerId) {
                $user = User::where('revenuecat_user_id', $customerId)
                    ->orWhere('email', $customerId)
                    ->first();
            }

            return [
                'customer_id' => $customerId ?? 'N/A',
                'user_name'   => $user?->name ?? 'Customer (' . substr($customerId ?? 'Unknown', 0, 12) . ')',
                'user_email'  => $user?->email ?? 'N/A',
                'status'      => ucfirst(strtolower($primarySub['status'] ?? 'Active')),
                'product_id'  => $primarySub['product_id'] ?? 'N/A',
                'store'       => $primarySub['store'] ?? 'N/A',
            ];
        });

        return view('admin.subscriptions.index', [
            'overview' => $overview,
            'subscriptions' => ['items' => $realCustomers->all()],
        ]);
    }
}
