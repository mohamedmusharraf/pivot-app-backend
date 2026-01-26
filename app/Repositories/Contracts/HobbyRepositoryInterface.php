<?php
namespace App\Repositories\Contracts;
use App\Models\Hobby;

interface HobbyRepositoryInterface
{
    public function all();
    public function create(array $data);
    public function update(Hobby $hobby, array $data): Hobby;
    public function delete(Hobby $hobby): void;
}