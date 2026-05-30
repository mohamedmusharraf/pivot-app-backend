<?php

namespace App\Http\Controllers;

use App\Models\Research;
use App\Models\UserDailyArticle;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserDailyArticlesController extends Controller
{
    public function getDailyArticle(Request $request)
{
    $user = $request->user();

    $timezone = $request->header('Timezone') ?? config('app.timezone');
    $today = Carbon::today($timezone)->toDateString();

    $existingAssignment = UserDailyArticle::where('user_id', $user->id)
        ->where('assigned_date', $today)
        ->first();

    if ($existingAssignment) {
        $article = Research::find($existingAssignment->article_id);
        return response()->json($article);
    }

    $alreadySeenIds = UserDailyArticle::where('user_id', $user->id)
        ->pluck('article_id');

    $newArticle = Research::whereNotIn('id', $alreadySeenIds)
        ->inRandomOrder()
        ->first();

    if (!$newArticle) {
        $newArticle = Research::inRandomOrder()->first();
    }

    if (!$newArticle) {
        return response()->json(['message' => 'No articles available'], 404);
    }

    UserDailyArticle::create([
        'user_id' => $user->id,
        'article_id' => $newArticle->id,
        'assigned_date' => $today,
    ]);

    return response()->json($newArticle);
}
}
