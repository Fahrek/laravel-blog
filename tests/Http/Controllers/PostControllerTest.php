<?php

use App\Post;
use App\Type;
use App\User;

class PostControllerTest extends TestCase
{
    protected $responseStructure = [
        'data' => [
            '*' => [
                'type',
                'id',
                'attributes' => ['title', 'slug', 'content', 'user_id', 'type_id', 'created_at', 'updated_at'],
                'links' => ['self']
            ]
        ],
        'meta' => [
            'pagination' => ['total', 'count', 'per_page', 'current_page', 'total_pages']
        ],
        'links' => ['self', 'first', 'last']
    ];

    public function test_index()
    {
        $post = factory(Post::class)->create();

        $this->json('GET', '/api/posts')
            ->assertResponseOk()
            ->seeJsonStructure($this->responseStructure)
            ->seeJson([
                'type' => $post->getTable(),
                'id' => (string) $post->id,
                'attributes' => [
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'content' => $post->content,
                    'user_id' => $post->user_id,
                    'type_id' => $post->type_id,
                    'created_at' => (string) $post->created_at,
                    'updated_at' => (string) $post->updated_at
                ],
                'links' => [
                    'self' => url('api/posts/' . $post->id)
                ]
            ]);
    }

    public function test_show()
    {
        $post = factory(Post::class)->create();

        $this->json('GET', '/api/posts/' . $post->id)
            ->assertResponseOk()
            ->seeJson([
                'type' => $post->getTable(),
                'id' => (string) $post->id,
                'attributes' => [
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'content' => $post->content,
                    'user_id' => $post->user_id,
                    'type_id' => $post->type_id,
                    'created_at' => (string) $post->created_at,
                    'updated_at' => (string) $post->updated_at
                ],
                'links' => [
                    'self' => url('api/posts/' . $post->id)
                ]
            ]);
    }

    public function test_store()
    {
        $user = factory(User::class)->create();
        $type = factory(Type::class)->create();

        $post = [
            'title' => 'Post title',
            'slug' => 'post-title',
            'content' => 'Post content',
            'type_id' => $type->id,
        ];

        $this->actingAs($user, 'api')
            ->json('POST', '/api/posts', $post)
            ->seeStatusCode(201)
            ->seeJson($post)
            ->seeInDatabase('posts', $post);
    }

    public function test_update()
    {
        $post = factory(Post::class)->create();

        $update = [
            'title' => 'Post title',
            'slug' => 'post-title',
            'content' => 'Post content',
            'type_id' => $post->type->id
        ];

        $this->actingAs($post->user, 'api')
            ->json('PUT', '/api/posts/' . $post->id, $update)
            ->seeStatusCode(202)
            ->seeJson($update)
            ->seeInDatabase('posts', $update);
    }

    public function test_destroy()
    {
        $post = factory(Post::class)->create();

        $this->actingAs($post->user, 'api')
            ->json('DELETE', '/api/posts/' . $post->id)
            ->seeStatusCode(204)
            ->dontSeeInDatabase('posts', [
                'id' => $post->id
            ]);
    }
}
