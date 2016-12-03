<?php

namespace App\Http\Controllers;

use App\Post;
use App\Transformers\TypeTransformer;
use Illuminate\Http\JsonResponse;

class PostTypeController extends ApiController
{
    /**
     * Display the specified resource.
     *
     * @param \App\Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Post $post) : JsonResponse
    {
        return $this->respondWithItem($post->type, new TypeTransformer(), 'types');
    }
}
