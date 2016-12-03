<?php

namespace App\Http\Requests;

use App\Http\Controllers\ApiController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class ApiRequest extends FormRequest
{
    /**
     * Get the response for a forbidden operation.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forbiddenResponse() : JsonResponse
    {
        return (new ApiController())->errorForbidden();
    }
}
