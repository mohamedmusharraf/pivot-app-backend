<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Research extends Model
{
    protected $table = 'research_articles';

    protected $fillable = [
        'title',
        'research_summary',
        'research_full_text',
        'files',
        'category',
    ];
}
