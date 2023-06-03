<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModelCarRequest extends FormRequest
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
            'name' => 'max:40',
            "brand_id" => 'numeric', // ToDo fix validation for relation
        ];
    }
}
