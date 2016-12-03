<?php

namespace App\Transformers;

use App\Post;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = [
        'user', 'type'
    ];

    /**
     * Turn this item object into a generic array.
     *
     * @param \App\Post $post
     * @return array
     */
    public function transform(Post $post) : array
    {
        return [
            'id' => (int) $post->id,
            'title' => (string) $post->title,
            'slug' => (string) $post->slug,
            'content' => (string) $post->content,
            'user_id' => (int) $post->user_id,
            'type_id' => (int) $post->type_id,
            'created_at' => (string) $post->created_at,
            'updated_at' => (string) $post->updated_at
        ];
    }

    /**
     * Include user foreach post.
     *
     * @param \App\Post $post
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Post $post): Item
    {
        return $this->item($post->user, new UserTransformer(), 'users');
    }

    /**
     * Include type foreach post.
     *
     * @param \App\Post $post
     * @return \League\Fractal\Resource\Item
     */
    public function includeType(Post $post): Item
    {
        return $this->item($post->type, new TypeTransformer(), 'types');
    }
}
