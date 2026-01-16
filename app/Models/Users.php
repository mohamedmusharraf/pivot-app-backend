<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Users extends Model
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'last_login_at',
    ];

    protected $hidden = [
        'password_hash',
    ];

    /**
     * Tell Laravel which column is the password
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    protected $casts = [
        'last_login_at' => 'datetime',
    ];
}
