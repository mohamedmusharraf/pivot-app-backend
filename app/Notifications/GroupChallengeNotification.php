<?php

namespace App\Notifications;

use DevKandil\NotiFire\Enums\MessagePriority;
use DevKandil\NotiFire\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GroupChallengeNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['fcm'];
    }

    public function toFcm(object $notifiable): FcmMessage
    {
        return FcmMessage::create(
            'Challenge Invite!',
            'You have been invited to join a team challenge.'
        )
            ->priority(MessagePriority::HIGH)
            ->data([
                'type' => 'group_challenge_invite',
                'timestamp' => now()->toIso8601String(),
            ]);
    }
}