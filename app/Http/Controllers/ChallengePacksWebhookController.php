<?php

namespace App\Http\Controllers;

use App\Models\ChallengePacksWebhook;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChallengePacksWebhookController extends Controller
{
    public function handleChallengePackWebhook(Request $request)
    {
        $event = $request->input('event');

        ChallengePacksWebhook::create([
            'app_id'       => $event['app_id'],
            'user_id'  => $event['app_user_id'],
            'price'        => $event['price'],
            'product_id'   => $event['product_id'],
            'environment'  => $event['environment'],
            'store'        => $event['store'],
            'type'         => $event['type'],
        ]);

        return response()->json([
            'message' => 'Challenge pack stored successfully.',
        ], 200);
    }
}
