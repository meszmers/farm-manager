<?php

namespace Tests\Unit\Services;

use App\Models\Farm;
use App\Models\User;
use App\Repositories\UserFarmRepository;
use App\Services\UserFarmService;
use App\Services\AnimalService;
use App\Structures\FarmStructure;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use PHPUnit\Framework\TestCase;

class UserFarmServiceTest extends TestCase
{
    private UserFarmRepository $farmRepository;
    private UserFarmService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->farmRepository = Mockery::mock(UserFarmRepository::class);
        $this->service = new UserFarmService($this->farmRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_getPaginatedUserFarms_returnsPaginator(): void
    {
        $user = new User();
        $paginator = Mockery::mock(LengthAwarePaginator::class);

        $this->farmRepository
            ->shouldReceive('getPaginatedUserFarms')
            ->once()
            ->with($user)
            ->andReturn($paginator);

        $result = $this->service->getPaginatedUserFarms($user);

        $this->assertSame($paginator, $result);
    }

    public function test_getUserFarmsWithSpace_filtersFarmsCorrectly(): void
    {
        $user = new User();

        $farmWithSpace = new Farm();
        $farmWithSpace->setRelation('animals', collect(array_fill(0, AnimalService::MAX_ANIMAL_COUNT_PER_FARM - 1, new \stdClass())));

        $farmFull = new Farm();
        $farmFull->setRelation('animals', collect(array_fill(0, AnimalService::MAX_ANIMAL_COUNT_PER_FARM, new \stdClass())));

        $farms = [$farmWithSpace, $farmFull];

        $this->farmRepository
            ->shouldReceive('getUserFarms')
            ->once()
            ->with($user)
            ->andReturn($farms);

        $result = $this->service->getUserFarmsWithSpace($user);

        $this->assertCount(1, $result);
        $this->assertContains($farmWithSpace, $result);
        $this->assertNotContains($farmFull, $result);
    }

    public function test_getUserFarm_returnsFarm(): void
    {
        $user = new User();
        $farmId = 5;
        $farm = new Farm();

        $this->farmRepository
            ->shouldReceive('getUserFarm')
            ->once()
            ->with($user, $farmId)
            ->andReturn($farm);

        $result = $this->service->getUserFarm($user, $farmId);

        $this->assertSame($farm, $result);
    }

    public function test_createUserFarm_returnsNewFarm(): void
    {
        $user = new User();
        $structure = new FarmStructure();
        $farm = new Farm();

        $this->farmRepository
            ->shouldReceive('createUserFarm')
            ->once()
            ->with($user, $structure)
            ->andReturn($farm);

        $result = $this->service->createUserFarm($user, $structure);

        $this->assertSame($farm, $result);
    }

    public function test_updateUserFarm_returnsUpdatedFarm(): void
    {
        $user = new User();
        $farmId = 7;
        $structure = new FarmStructure();
        $farm = new Farm();

        $this->farmRepository
            ->shouldReceive('updateUserFarm')
            ->once()
            ->with($user, $farmId, $structure)
            ->andReturn($farm);

        $result = $this->service->updateUserFarm($user, $farmId, $structure);

        $this->assertSame($farm, $result);
    }

    public function test_deleteUserFarm_callsRepositoryDelete(): void
    {
        $user = new User();
        $farmId = 9;

        $this->farmRepository
            ->shouldReceive('deleteUserFarm')
            ->once()
            ->with($user, $farmId);

        $this->service->deleteUserFarm($user, $farmId);

        $this->assertTrue(true);
    }
}
