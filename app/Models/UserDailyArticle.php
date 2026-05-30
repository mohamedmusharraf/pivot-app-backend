<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDailyArticle extends Model
{
    protected $table = 'user_daily_articles';

    protected $fillable = [
        'user_id',
        'article_id',
        'assigned_date',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
