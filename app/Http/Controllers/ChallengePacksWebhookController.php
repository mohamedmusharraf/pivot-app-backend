<?php

namespace App\Http\Controllers;

use App\Models\ChallengePacksWebhook;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChallengePacksWebhookController extends Controller
{
    public function handleChallengePackWebhook(Request $request): JsonResponse
    {
        $event = $request->input('event');

        $allowedProducts = [
            '1_challenge_pack',
            '3_challenge_pack',
        ];

        if (!in_array($event['product_id'], $allowedProducts, true)) {
            return response()->json([], 200);
        }

        $total = match ($event['product_id']) {
            '1_challenge_pack' => 1,
            '3_challenge_pack' => 3,
            default => 0,
        };

        ChallengePacksWebhook::create([
            'app_id'               => $event['app_id'],
            'user_id'              => $event['app_user_id'],
            'revenuecat_event_id'  => $event['id'],
            'price'                => $event['price'],
            'product_id'           => $event['product_id'],
            'total'                => $total,
            'remaining'            => $total,
            'status'               => 'unused',
            'environment'          => $event['environment'],
            'store'                => $event['store'],
            'type'                 => $event['type'],
        ]);

        return response()->json([
            'message' => 'Challenge pack stored successfully.',
        ], 200);
    }
}