<?php

namespace App\Transformers;

use App\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    public function transform(Post $post)
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
}
