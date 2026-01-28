<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class country extends Model
{
    protected $table = 'countries';

    protected $fillable = [
        'iso_code',
        'name',
        'default_locale',
        'currency_code',
        'phone_code',
        'timezone',
        'is_active',
    ];
}
