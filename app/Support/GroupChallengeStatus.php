<?php

namespace App\Support;

class GroupChallengeStatus
{
    public const GROUP_CHALLENGE_STATUS_NOT_READY = 'not_ready';
    public const GROUP_CHALLENGE_STATUS_READY = 'ready';
    public const GROUP_CHALLENGE_STATUS_IN_CHALLENGE = 'in_challenge';

    public const GroupChallengeStatusNotready = self::GROUP_CHALLENGE_STATUS_NOT_READY;
    public const GroupChallengeStatusReady = self::GROUP_CHALLENGE_STATUS_READY;
}
