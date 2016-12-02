<?php

namespace App\Http\Requests\Type;

use App\Type;
use Illuminate\Foundation\Http\FormRequest;

class StoreTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Type::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:types,name|max:255',
            'slug' => 'required|alpha_dash|unique:types,slug|max:255'
        ];
    }
}
