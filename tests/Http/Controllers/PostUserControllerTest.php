<?php

use App\Post;

class PostUserControllerTest extends TestCase
{
    public function test_show()
    {
        $post = factory(Post::class)->create();

        $this->json('GET', '/api/posts/' . $post->id . '/user')
            ->assertResponseOk()
            ->seeJson([
                'type' => $post->user->getTable(),
                'id' => (string) $post->user->id,
                'attributes' => [
                    'name' => $post->user->name,
                    'created_at' => (string) $post->user->created_at,
                    'updated_at' => (string) $post->user->updated_at
                ],
                'links' => [
                    'self' => url('api/users/' . $post->user->id)
                ]
            ]);
    }
}
