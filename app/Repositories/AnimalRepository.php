<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Animal;
use App\Models\User;
use App\Structures\AnimalStructure;
use Carbon\Carbon;

class AnimalRepository
{
    public function getUserAnimal(User $user, int $id): ?Animal
    {
        return Animal::query()->where('user_id', $user->id)->find($id);
    }

    public function createUserAnimal(User $user, AnimalStructure $structure)
    {
        $currentTime = Carbon::now();

        return Animal::create([
            'user_id' => $user->id,
            'animal_number' => $structure->animal_number,
            'type_name' => $structure->animal_type,
            'years' => $structure->years,
            'created_at' => $currentTime,
            'updated_at' => $currentTime,
        ]);
    }

    public function deleteAnimal(Animal $animal): void
    {
        $animal->delete();
    }
}
