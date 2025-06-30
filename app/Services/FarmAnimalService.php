<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AnimalRepository;
use App\Repositories\FarmAnimalRepository;
use App\Repositories\UserFarmRepository;
use Exception;

class FarmAnimalService
{
    public function __construct(
        private readonly AnimalRepository $animalRepository,
        private readonly UserFarmRepository $userFarmRepository,
        private readonly FarmAnimalRepository $farmAnimalRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function transferAnimalToAnotherFarm(User $user, int $animalId, int $farmId): void
    {
        $animal = $this->animalRepository->getUserAnimal($user, $animalId);
        if (!$animal) {
            throw new Exception('Animal not found for this user.');
        }

        $farm = $this->userFarmRepository->getUserFarm($user, $farmId);
        if (!$farm) {
            throw new Exception('Farm not found for this user.');
        }

        if (count($farm->animals) >= AnimalService::MAX_ANIMAL_COUNT_PER_FARM) {
            throw new Exception("Farm animals limit reached.");
        }

        $this->farmAnimalRepository->transferAnimalToFarm($animal, $farm);
    }
}
