<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property ?string $email
 * @property ?string $website
 * @property string $created_at
 * @property string $updated_at
 */
class Farm extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'website',
    ];

    public function animals(): HasManyThrough
    {
        return $this->hasManyThrough(
            Animal::class,
            FarmAnimal::class,
            'farm_id',
            'id',
            'id',
            'animal_id',
        );
    }
}
