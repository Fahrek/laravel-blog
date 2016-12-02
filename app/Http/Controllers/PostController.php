<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\DestroyPostRequest;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Post;
use App\Transformers\PostTransformer;
use Illuminate\Http\JsonResponse;

class PostController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $paginator = Post::paginate();
        $posts = $paginator->getCollection();

        return $this->respondWithPaginatedCollection($posts, $paginator, new PostTransformer(), 'posts');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Post\StorePostRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $post = Post::create([
            'title' => $request->input('title'),
            'slug' => $request->input('slug'),
            'content' => $request->input('content'),
            'user_id' => $request->user()->id,
            'type_id' => $request->input('type_id')
        ]);

        return $this->setStatusCode(201)->show($post);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Post $post): JsonResponse
    {
        return $this->respondWithItem($post, new PostTransformer(), 'posts');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Post\UpdatePostRequest $request
     * @param \App\Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $post->title = $request->input('title');
        $post->slug = $request->input('slug');
        $post->content = $request->input('content');
        $post->user_id = $request->user()->id;
        $post->type_id = $request->input('type_id');
        $post->save();

        return $this->setStatusCode(202)->show($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Http\Requests\Post\DestroyPostRequest $request
     * @param \App\Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyPostRequest $request, Post $post): JsonResponse
    {
        $post->delete();

        return $this->respondWithNoContent();
    }
}
