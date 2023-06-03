<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static insert(array $imageData)
 */
class CarImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'car_id'
    ];
}
