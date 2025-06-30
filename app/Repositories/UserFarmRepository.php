<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Farm;
use App\Models\User;
use App\Structures\FarmStructure;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class UserFarmRepository
{
    public function getUserFarms(User $user): array
    {
        return Farm::query()->where('user_id', $user->id)->get()->all();
    }

    public function getPaginatedUserFarms(User $user): LengthAwarePaginator
    {
        return Farm::query()
            ->where('user_id', $user->id)
            ->orderBy('created_at')
            ->paginate(10);
    }

    public function getUserFarm(User $user, int $id): ?Farm
    {
        return Farm::query()->with('animals')->where('user_id', $user->id)->find($id);
    }

    public function createUserFarm(User $user, FarmStructure $structure): Farm
    {
        $currentTime = Carbon::now();

        return Farm::create([
            'user_id' => $user->id,
            'name' => $structure->name,
            'email' => $structure->email,
            'website' => $structure->website,
            'created_at' => $currentTime,
            'updated_at' => $currentTime,
        ]);
    }

    public function updateUserFarm(User $user, int $id, FarmStructure $structure): Farm
    {
        $farm = $this->getUserFarm($user, $id);

        $farm->update([
            'name' => $structure->name,
            'email' => $structure->email,
            'website' => $structure->website,
            'updated_at' => Carbon::now(),
        ]);

        $farm->refresh();

        return $farm;
    }

    public function deleteUserFarm(User $user, int $id): void
    {
        $farm = $this->getUserFarm($user, $id);
        $farm->delete();
    }
}
