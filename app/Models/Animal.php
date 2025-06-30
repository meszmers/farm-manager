<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $animal_number
 * @property string $type_name
 * @property int $years
 * @property string $created_at
 * @property string $updated_at
 */
class Animal extends Model
{
    protected $fillable = [
        'user_id',
        'animal_number',
        'type_name',
        'years',
    ];
}
