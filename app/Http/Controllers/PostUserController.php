<?php

namespace App\Http\Controllers;

use App\Post;
use App\Transformers\UserTransformer;
use Illuminate\Http\JsonResponse;

class PostUserController extends ApiController
{
    /**
     * Display the specified resource.
     *
     * @param \App\Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Post $post) : JsonResponse
    {
        return $this->respondWithItem($post->user, new UserTransformer(), 'users');
    }
}
