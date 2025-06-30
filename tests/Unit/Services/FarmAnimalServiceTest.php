<?php

namespace Tests\Unit\Services;

use App\Models\Animal;
use App\Models\Farm;
use App\Models\User;
use App\Repositories\AnimalRepository;
use App\Repositories\FarmAnimalRepository;
use App\Repositories\UserFarmRepository;
use App\Services\FarmAnimalService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;

class FarmAnimalServiceTest extends TestCase
{
    private AnimalRepository $animalRepository;
    private UserFarmRepository $userFarmRepository;
    private FarmAnimalRepository $farmAnimalRepository;
    private FarmAnimalService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->animalRepository = Mockery::mock(AnimalRepository::class);
        $this->userFarmRepository = Mockery::mock(UserFarmRepository::class);
        $this->farmAnimalRepository = Mockery::mock(FarmAnimalRepository::class);

        $this->service = new FarmAnimalService(
            $this->animalRepository,
            $this->userFarmRepository,
            $this->farmAnimalRepository
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_transferAnimalToAnotherFarm_success(): void
    {
        $user = new User();
        $animal = new Animal();
        $farm = new Farm();

        $farm->setRelation('animals', new Collection([
            new Animal(),
            new Animal(),
        ]));

        $this->animalRepository
            ->shouldReceive('getUserAnimal')
            ->once()
            ->with($user, 1)
            ->andReturn($animal);

        $this->userFarmRepository
            ->shouldReceive('getUserFarm')
            ->once()
            ->with($user, 2)
            ->andReturn($farm);

        $this->farmAnimalRepository
            ->shouldReceive('transferAnimalToFarm')
            ->once()
            ->with($animal, $farm);

        self::expectNotToPerformAssertions();

        $this->service->transferAnimalToAnotherFarm($user, 1, 2);
    }

    public function test_transferAnimalToAnotherFarm_animalNotFound(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Animal not found for this user.');

        $user = new User();

        $this->animalRepository
            ->shouldReceive('getUserAnimal')
            ->once()
            ->with($user, 1)
            ->andReturn(null);

        $this->userFarmRepository->shouldNotReceive('getUserFarm');
        $this->farmAnimalRepository->shouldNotReceive('transferAnimalToFarm');

        $this->service->transferAnimalToAnotherFarm($user, 1, 2);
    }

    public function test_transferAnimalToAnotherFarm_farmNotFound(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Farm not found for this user.');

        $user = new User();
        $animal = new Animal();

        $this->animalRepository
            ->shouldReceive('getUserAnimal')
            ->once()
            ->with($user, 1)
            ->andReturn($animal);

        $this->userFarmRepository
            ->shouldReceive('getUserFarm')
            ->once()
            ->with($user, 2)
            ->andReturn(null);

        $this->farmAnimalRepository->shouldNotReceive('transferAnimalToFarm');

        $this->service->transferAnimalToAnotherFarm($user, 1, 2);
    }

    public function test_transferAnimalToAnotherFarm_farmLimitReached(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Farm animals limit reached.');

        $user = new User();
        $animal = new Animal();
        $farm = new Farm();

        $farm->setRelation('animals', new Collection([
            new Animal(),
            new Animal(),
            new Animal(),
        ]));

        $this->animalRepository
            ->shouldReceive('getUserAnimal')
            ->once()
            ->with($user, 1)
            ->andReturn($animal);

        $this->userFarmRepository
            ->shouldReceive('getUserFarm')
            ->once()
            ->with($user, 2)
            ->andReturn($farm);

        $this->farmAnimalRepository->shouldNotReceive('transferAnimalToFarm');

        $this->service->transferAnimalToAnotherFarm($user, 1, 2);
    }
}
