<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @property mixed type
 * @property mixed location
 * @property mixed id
 * @property mixed sale
 * @property mixed price
 * @property mixed mileage
 * @property mixed year
 * @property mixed box
 * @property mixed sedan
 * @property mixed petrol
 * @property mixed model
 * @property mixed images
 * @property mixed created_at
 * @property mixed brand
 */
class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'brand' => new BrandResource($this->brand),
            'model' => $this->model,
            'sale' => $this->sale,
            'price' => $this->price,
            'mileage' => $this->mileage,
            'location' => $this->location,
            'year' => $this->year,
            'box' => $this->box,
            'sedan' => $this->sedan,
            'petrol' => $this->petrol,
            'images' => ImagesResource::collection($this->images),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
