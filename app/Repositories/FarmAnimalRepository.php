<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Animal;
use App\Models\Farm;
use App\Models\FarmAnimal;
use Carbon\Carbon;

class FarmAnimalRepository
{
    public function addAnimalToFarm(Animal $animal, Farm $farm)
    {
        $currentTime = Carbon::now();

        return FarmAnimal::create([
            'farm_id' => $farm->id,
            'animal_id' => $animal->id,
            'created_at' => $currentTime,
            'updated_at' => $currentTime,
        ]);
    }

    public function removeAnimalFromFarm(Animal $animal, int $farmId): void
    {
        FarmAnimal::query()->where([
            ['farm_id', '=', $farmId],
            ['animal_id', '=', $animal->id],
        ])->first()?->delete();
    }

    public function transferAnimalToFarm(Animal $animal, Farm $farm): void
    {
        $farmAnimal = FarmAnimal::query()->where('animal_id', $animal->id)->first();

        $farmAnimal->update([
            'farm_id' => $farm->id,
        ]);
    }
}
