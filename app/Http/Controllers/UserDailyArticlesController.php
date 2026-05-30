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
    public function getArticleLibrary(Request $request)
    {
        $user = $request->user();

        // Fetch all articles this user has unlocked over time, sorted by newest first
        $unlockedArticles = Research::join('user_daily_articles', 'research_articles.id', '=', 'user_daily_articles.article_id')
            ->where('user_daily_articles.user_id', $user->id)
            ->orderBy('user_daily_articles.assigned_date', 'desc')
            ->select('research_articles.*', 'user_daily_articles.assigned_date', 'user_daily_articles.id as user_daily_article_id')
            ->get();

        return response()->json($unlockedArticles);
    }

    public function addArticleLibrary(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:research_articles,id'
        ]);

        $user = $request->user();
        $timezone = $request->header('Timezone') ?? config('app.timezone');
        $today = Carbon::today($timezone)->toDateString();

        $exists = UserDailyArticle::where('user_id', $user->id)
            ->where('article_id', $request->article_id)
            ->first();

        if ($exists) {
            return response()->json(['message' => 'Article already in library'], 400);
        }

        $assignedToday = UserDailyArticle::where('user_id', $user->id)
            ->where('assigned_date', $today)
            ->first();

        if ($assignedToday) {
            return response()->json(['message' => 'You can only add one article to your library per day.'], 400);
        }

        $userDailyArticle = UserDailyArticle::create([
            'user_id' => $user->id,
            'article_id' => $request->article_id,
            'assigned_date' => $today,
        ]);

        return response()->json($userDailyArticle, 201);
    }

    public function deleteArticleLibrary(Request $request, $id)
    {
        $user = $request->user();

        $deleted = UserDailyArticle::where('user_id', $user->id)
            ->where('article_id', $id)
            ->delete();

        if (!$deleted) {
            return response()->json(['message' => 'Article not found in library'], 404);
        }

        return response()->json(['message' => 'Article removed from library']);
    }
}
