<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePetRequest extends FormRequest
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
            'name' => 'required ',
            'breed_id' => 'required | exists:App\Models\Breed,id',
            'size_id' => 'required | exists:App\Models\Size,id',
            'birth_date' => 'required | date',
        ];
    }
}
