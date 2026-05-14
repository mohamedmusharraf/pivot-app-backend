<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceFingerprint extends Model
{
   protected $fillable = [
        'user_id',
        'device_id',
        'device_name',
        'platform',
        'ip_address',
    ];
}
