<?php

use App\Post;

class PostTypeControllerTest extends TestCase
{
    public function test_show()
    {
        $post = factory(Post::class)->create();

        $this->json('GET', '/api/posts/' . $post->id . '/type')
            ->assertResponseOk()
            ->seeJson([
                'type' => $post->type->getTable(),
                'id' => (string) $post->type->id,
                'attributes' => [
                    'name' => $post->type->name,
                    'slug' => $post->type->slug
                ],
                'links' => [
                    'self' => url('api/types/' . $post->type->id)
                ]
            ]);
    }
}
