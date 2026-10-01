<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RevenueCatService;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    private const PER_PAGE = 15;

    public function index(Request $request, RevenueCatService $revenueCat)
    {
        $overview = $revenueCat->getMetricsOverview();

        // Paginate users who have a subscription, ordered by newest subscription first
        $paginator = User::whereHas('subscription')
            ->with('subscription.tier')
            ->join('subscriptions', 'users.id', '=', 'subscriptions.user_id')
            ->orderBy('subscriptions.created_at', 'desc')
            ->select('users.*')
            ->paginate(self::PER_PAGE, ['*'], 'page', (int) $request->query('page', 1));

        $currentPage = $paginator->currentPage();
        $totalPages  = $paginator->lastPage();
        $total       = $paginator->total();

        $realCustomers = collect($paginator->items())->map(function (User $user) use ($revenueCat) {
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

            // Tier — read directly from local DB (authoritative source)
            $tier = $user->subscription?->tier?->name ?? 'N/A';

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
            ];
        })->values();

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