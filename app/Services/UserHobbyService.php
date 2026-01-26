<?php

namespace App\Services;

use App\Models\Users;

class UserHobbyService
{
   public function sync(Users $user, array $hobbyIds)
    {
        $user->hobbies()->sync($hobbyIds);
    }
}