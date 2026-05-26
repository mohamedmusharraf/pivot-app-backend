<?php

namespace App\DTO;

class TeamConnectionDTO
{
    public function __construct(
        public int $inviteId,
        public int $inviterId,
        public string $inviterName,
        public ?string $inviterAvatar,

        public int $connectedUserId,
        public string $connectedUserName,
        public ?string $connectedUserAvatar,

        public int $teamMemberCount,
        public string $status,
        public string $acceptedAt,
    ) {}

    public function toArray(): array
    {
        return [
            'invite_id' => $this->inviteId,

            'inviter' => [
                'id' => $this->inviterId,
                'name' => $this->inviterName,
                'avatar' => $this->inviterAvatar,
            ],

            'connected_user' => [
                'id' => $this->connectedUserId,
                'name' => $this->connectedUserName,
                'avatar' => $this->connectedUserAvatar,
            ],

            'team_member_count' => $this->teamMemberCount,
            'status' => $this->status,
            'accepted_at' => $this->acceptedAt,
        ];
    }
}