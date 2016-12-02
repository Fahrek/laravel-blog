<?php

use App\Post;
use App\Transformers\PostTransformer;

class PostTransformerTest extends TestCase
{
    public function test_it_should_transform_post()
    {
        $post = factory(Post::class)->create();

        $transformed = (new PostTransformer())->transform($post);

        $this->assertEquals([
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'content' => $post->content,
            'user_id' => $post->user_id,
            'type_id' => $post->type_id,
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at
        ], $transformed);
        $this->assertInternalType('int', $transformed['id']);
        $this->assertInternalType('string', $transformed['title']);
        $this->assertInternalType('string', $transformed['slug']);
        $this->assertInternalType('string', $transformed['content']);
        $this->assertInternalType('int', $transformed['user_id']);
        $this->assertInternalType('int', $transformed['type_id']);
        $this->assertInternalType('string', $transformed['created_at']);
        $this->assertInternalType('string', $transformed['updated_at']);
    }
}
