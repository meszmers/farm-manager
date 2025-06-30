<?php

namespace Tests\Unit\Services;

use App\Models\Animal;
use App\Models\Farm;
use App\Models\User;
use App\Repositories\AnimalRepository;
use App\Repositories\FarmAnimalRepository;
use App\Repositories\UserFarmRepository;
use App\Services\AnimalService;
use App\Structures\AnimalStructure;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;

class AnimalServiceTest extends TestCase
{
    private AnimalRepository $animalRepository;
    private UserFarmRepository $userFarmRepository;
    private FarmAnimalRepository $farmAnimalRepository;
    private AnimalService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->animalRepository = Mockery::mock(AnimalRepository::class);
        $this->userFarmRepository = Mockery::mock(UserFarmRepository::class);
        $this->farmAnimalRepository = Mockery::mock(FarmAnimalRepository::class);

        $this->service = new AnimalService(
            $this->animalRepository,
            $this->userFarmRepository,
            $this->farmAnimalRepository,
        );
    }

    public function test_createAnimalForFarm_success(): void
    {
        $user = new User();
        $farm = new Farm();
        $structure = Mockery::mock(AnimalStructure::class);
        $animal = new Animal();

        $farm->setRelation('animals', new Collection([
            new Animal(),
            new Animal(),
        ]));

        $this->userFarmRepository
            ->shouldReceive('getUserFarm')
            ->once()
            ->with($user, 1)
            ->andReturn($farm);

        $this->animalRepository
            ->shouldReceive('createUserAnimal')
            ->once()
            ->with($user, $structure)
            ->andReturn($animal);

        $this->farmAnimalRepository
            ->shouldReceive('addAnimalToFarm')
            ->once()
            ->with($animal, $farm);

        $this->service->createAnimalForFarm($user, 1, $structure);
    }

    public function test_createAnimalForFarm_farmNotFound(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Farm not found.');

        $user = new User();
        $structure = Mockery::mock(AnimalStructure::class);

        $this->userFarmRepository
            ->shouldReceive('getUserFarm')
            ->once()
            ->with($user, 1)
            ->andReturn(null);

        $this->service->createAnimalForFarm($user, 1, $structure);
    }

    public function test_createAnimalForFarm_maxAnimalsReached(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Farm has maximum of allowed animals.');

        $user = new User();
        $structure = Mockery::mock(AnimalStructure::class);
        $farm = new Farm();

        $farm->setRelation('animals', new Collection([
            new Animal(),
            new Animal(),
            new Animal(),
        ]));

        $this->userFarmRepository
            ->shouldReceive('getUserFarm')
            ->once()
            ->with($user, 1)
            ->andReturn($farm);

        $this->service->createAnimalForFarm($user, 1, $structure);
    }

    /**
     * @throws Exception
     */
    public function test_deleteAnimalFromFarm_success(): void
    {
        $user = new User();
        $animal = new Animal();

        $this->animalRepository
            ->shouldReceive('getUserAnimal')
            ->once()
            ->with($user, 2)
            ->andReturn($animal);

        $this->farmAnimalRepository
            ->shouldReceive('removeAnimalFromFarm')
            ->once()
            ->with($animal, 1);

        $this->animalRepository
            ->shouldReceive('deleteAnimal')
            ->once()
            ->with($animal);

        $this->service->deleteAnimalFromFarm($user, 1, 2);
    }

    public function test_deleteAnimalFromFarm_animalNotFound(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Animal not found for this user.');

        $user = new User();

        $this->animalRepository
            ->shouldReceive('getUserAnimal')
            ->once()
            ->with($user, 2)
            ->andReturn(null);

        $this->service->deleteAnimalFromFarm($user, 1, 2);
    }
}
