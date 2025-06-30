<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\Farm;
use App\Models\FarmAnimal;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserFarmAnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->count(2)
            ->create()
            ->each(function ($user) {
                Farm::factory()
                    ->count(2)
                    ->create(['user_id' => $user->id])
                    ->each(function ($farm) use ($user) {
                        Animal::factory()
                            ->count(rand(1, 3))
                            ->create(['user_id' => $user->id])
                            ->each(function ($animal) use ($farm) {
                                FarmAnimal::factory()
                                    ->count(1)
                                    ->create(['animal_id' => $animal->id, 'farm_id' => $farm->id]);
                            });
                    });
            });
    }
}
