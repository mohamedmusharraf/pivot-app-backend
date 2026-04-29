<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Research extends Model
{
    protected $table = 'research_articles';

    protected $fillable = [
        'fun_facts',
        'summary',
        'full_content',
        'files',
    ];
}
