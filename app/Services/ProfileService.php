<?php

namespace App\Services;

use App\Models\Users;
use App\Models\UserProfile;

class ProfileService
{
    public function save(Users $user, array $data): UserProfile
    {
        $profile = UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'country'              => $data['country'],
                'gender'                  => $data['gender'],
                'age_range'            => $data['age_range'],
                'screen_goal_hours'    => $data['screen_goal_hours'],
                'onboarding_completed' => $data['onboarding_completed'] ?? false,
            ]
        );

        return $profile;
    }
}
