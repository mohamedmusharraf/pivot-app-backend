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
        'status',
        'provider',
        'provider_id',
        'last_login_at',
    ];

    protected $hidden = [
        'password_hash',
    ];


    public function getAuthPassword()
    {
        return $this->password_hash;
    }
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    public function hobbies()
    {
        return $this->belongsToMany(Hobby::class, 'user_hobbies', 'user_id', 'hobby_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'user_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function userProfile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'user_id', 'id');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'user_id', 'id')->where('active', true)->latest();
    }

    protected $casts = [
        'last_login_at' => 'datetime',
    ];
}
