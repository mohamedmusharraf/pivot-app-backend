<?php

namespace App\Services;

use App\Models\Hobby;
use App\Models\UserHobby;
use App\Models\Users;
use App\Repositories\Contracts\UserHobbyRepositoryInterface;
use Illuminate\Http\Request;

class UserHobbyService
{
    public function __construct(
        protected UserHobbyRepositoryInterface $userHobbyRepository
    ) {}

    public function selectHobbies(Users $user, array $hobbyIds)
    {
        $user->hobbies()->sync($hobbyIds);
    }

    public function getUserHobbies()
    {
        return $this->userHobbyRepository->all();
    }

    public function getUserHobby(Request $request, UserHobby $user_hobby)
    {
        if ($user_hobby->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this hobby');
        }

        $user_hobby->load('hobby.activities');

        return $user_hobby->hobby;
    }

    public function update(Request $request, UserHobby $userHobby, array $data)
    {
        $this->authorize($request, $userHobby);

        $this->userHobbyRepository->update($userHobby, $data);

        $userHobby->load('hobby.activities');

        return $userHobby->hobby;
    }

    public function delete(Request $request, UserHobby $userHobby): void
    {
        $this->authorize($request, $userHobby);

        $this->userHobbyRepository->delete($userHobby);
    }

    private function authorize(Request $request, UserHobby $userHobby): void
    {
        if ($userHobby->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this hobby');
        }
    }
}
