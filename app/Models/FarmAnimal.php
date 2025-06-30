<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $farm_id
 * @property int $animal_id
 * @property string $created_at
 * @property string $updated_at
 */
class FarmAnimal extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'animal_id',
    ];

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class, 'farm_id', 'id');
    }

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'animal_id', 'id');
    }
}
