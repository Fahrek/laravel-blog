<?php

namespace App\Http\Requests\Post;

use App\Http\Requests\ApiRequest;
use App\Post;

class StorePostRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Post::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|alpha_dash|unique:posts|max:255',
            'content' => 'required|string',
            'type_id' => 'integer|exists:types,id'
        ];
    }
}
