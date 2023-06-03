<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "brand_id" => 'string',
            "sale" => 'string',
            "price" => 'string',
            "mileage" => 'string',
            "location" => 'string',
            "year" => 'string',
            "box" => 'string',
            "sedan" => 'string',
            "petrol" => 'string',
            "model" => 'string',
        ];
    }
}