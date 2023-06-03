<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static find($id)
 * @method static where(string $string, $id)
 * @method static create($data)
 * @method static orderBy(string $string, string $string1)
 */
class Car extends Model
{
    use HasFactory;
    protected $fillable = [
        'brand_id', 'sale', "price", 'mileage', 'location', 'year', 'box', 'sedan', 'petrol', 'model', 'images'
    ];


    /**
     * @return BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return HasMany
     */
    public function images()
    {
        return $this->hasMany(CarImage::class);
    }
}
