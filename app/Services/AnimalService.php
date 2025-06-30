<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Farm;
use App\Models\User;
use App\Repositories\AnimalRepository;
use App\Repositories\FarmAnimalRepository;
use App\Repositories\UserFarmRepository;
use App\Structures\AnimalStructure;
use Exception;

class AnimalService
{
    public const MAX_ANIMAL_COUNT_PER_FARM = 3;

    public function __construct(
        private readonly AnimalRepository $animalRepository,
        private readonly UserFarmRepository $userFarmRepository,
        private readonly FarmAnimalRepository $farmAnimalRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function createAnimalForFarm(User $user, int $farmId, AnimalStructure $structure): void
    {
        $farm = $this->userFarmRepository->getUserFarm($user, $farmId);

        if (!$farm) {
            throw new Exception('Farm not found.');
        }

        if (!$this->canAnimalBeAddedToTheFarm($farm)) {
            throw new Exception('Farm has maximum of allowed animals.');
        }

        $animal = $this->animalRepository->createUserAnimal($user, $structure);

        $this->farmAnimalRepository->addAnimalToFarm($animal, $farm);
    }

    /**
     * @throws Exception
     */
    public function deleteAnimalFromFarm(User $user, int $farmId, int $animalId): void
    {
        $animal = $this->animalRepository->getUserAnimal($user, $animalId);

        if (!$animal) {
            throw new Exception('Animal not found for this user.');
        }

        $this->farmAnimalRepository->removeAnimalFromFarm($animal, $farmId);
        $this->animalRepository->deleteAnimal($animal);
    }

    private function canAnimalBeAddedToTheFarm(Farm $farm): bool
    {
        return $farm->animals()->count() < self::MAX_ANIMAL_COUNT_PER_FARM;
    }
}
