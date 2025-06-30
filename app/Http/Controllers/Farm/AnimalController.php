<?php

declare(strict_types=1);

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Services\AnimalService;
use App\Services\FarmAnimalService;
use App\Structures\AnimalStructure;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function __construct(
        private readonly AnimalService $animalService,
        private readonly FarmAnimalService $farmAnimalService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function create(Request $request, int $farmId): JsonResponse
    {
        $request->validate([
            'animal_number' => ['required', 'int', 'max:255'],
            'animal_type' => ['required', 'string', 'max:255'],
            'years' => ['required', 'int', 'max:255'],
        ]);

        $structure = new AnimalStructure();
        $structure->animal_number = (int) $request->input("animal_number");
        $structure->animal_type = $request->input("animal_type");
        $structure->years = (int) $request->input("years");

        $this->animalService->createAnimalForFarm($request->user(), $farmId, $structure);

        return response()->json(['success' => true]);
    }

    /**
     * @throws Exception
     */
    public function delete(Request $request, int $farmId, int $animalId): JsonResponse
    {
        $this->animalService->deleteAnimalFromFarm($request->user(), $farmId, $animalId);

        return response()->json(['success' => true]);
    }

    /**
     * @throws Exception
     */
    public function transfer(Request $request, int $farmId, int $animalId): JsonResponse
    {
        $request->validate([
            'transfer_farm_id' => ['required', 'int', 'max:255'],
        ]);

        $this->farmAnimalService->transferAnimalToAnotherFarm(
            $request->user(),
            $animalId,
            (int) $request->get('transfer_farm_id'),
        );

        return response()->json(['success' => true]);
    }
}
