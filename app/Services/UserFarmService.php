<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Farm;
use App\Models\User;
use App\Repositories\UserFarmRepository;
use App\Structures\FarmStructure;
use Illuminate\Pagination\LengthAwarePaginator;

class UserFarmService
{
    public function __construct(
        private readonly UserFarmRepository $farmRepository
    ) {
    }

    public function getPaginatedUserFarms(User $user): LengthAwarePaginator
    {
        return $this->farmRepository->getPaginatedUserFarms($user);
    }

    public function getUserFarmsWithSpace(User $user): array
    {
        $farms = $this->farmRepository->getUserFarms($user);

        return array_filter(
            $farms,
            static fn (Farm $farm) => $farm->animals()->count() < AnimalService::MAX_ANIMAL_COUNT_PER_FARM,
        );
    }

    public function getUserFarm(User $user, int $farmId): Farm
    {
        return $this->farmRepository->getUserFarm($user, $farmId);
    }

    public function createUserFarm(User $user, FarmStructure $structure): Farm
    {
        return $this->farmRepository->createUserFarm($user, $structure);
    }

    public function updateUserFarm(User $user, int $farmId, FarmStructure $structure): Farm
    {
        return $this->farmRepository->updateUserFarm($user, $farmId, $structure);
    }

    public function deleteUserFarm(User $user, int $farmId): void
    {
        $this->farmRepository->deleteUserFarm($user, $farmId);
    }
}
