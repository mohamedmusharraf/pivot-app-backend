<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserArticle extends Model
{
    protected $table = 'user_articles';

    protected $fillable = [
        'user_id',
        'article_id',
    ];

    public function article()
    {
        return $this->belongsTo(Research::class, 'article_id');
    }
}
