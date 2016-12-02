<?php

use App\Post;
use App\Type;
use App\User;

class PostPolicyTest extends TestCase
{
    public function test_unauthenticated_can_view_post()
    {
        $this->json('GET', '/api/posts')
            ->assertResponseStatus(200);
    }

    public function test_unauthenticated_can_view_posts()
    {
        $post = factory(Post::class)->create();

        $this->json('GET', '/api/posts/' . $post->id)
            ->assertResponseStatus(200);
    }

    public function test_unauthenticated_cannot_create_post()
    {
        $user = factory(User::class)->create();
        $type = factory(Type::class)->create();

        $post = [
            'title' => 'Post title',
            'slug' => 'post-slug',
            'content' => 'post-content',
            'user_id' => $user->id,
            'type_id' => $type->id
        ];

        $this->json('POST', '/api/posts', $post)
            ->assertResponseStatus(401);
    }

    public function test_authenticated_can_create_post()
    {
        $user = factory(User::class)->create();
        $type = factory(Type::class)->create();

        $post = [
            'title' => 'Post title',
            'slug' => 'post-slug',
            'content' => 'post-content',
            'user_id' => $user->id,
            'type_id' => $type->id
        ];

        $this->actingAs($user, 'api')
            ->json('POST', '/api/posts', $post)
            ->assertResponseStatus(201);
    }

    public function test_unauthenticated_cannot_update_post()
    {
        $post = factory(Post::class)->create();

        $update = [
            'title' => 'Post title',
            'slug' => 'post-slug',
            'content' => 'post-content',
            'user_id' => $post->user_id,
            'type_id' => $post->type_id
        ];

        $this->json('PUT', '/api/posts/' . $post->id, $update)
            ->assertResponseStatus(401);
    }

    public function test_authenticated_can_update_its_post()
    {
        $post = factory(Post::class)->create();

        $update = [
            'title' => 'Post title',
            'slug' => 'post-slug',
            'content' => 'post-content',
            'user_id' => $post->user_id,
            'type_id' => $post->type_id
        ];

        $this->actingAs($post->user, 'api')
            ->json('PUT', '/api/posts/' . $post->id, $update)
            ->assertResponseStatus(202);
    }

    public function test_authenticated_cannot_update_others_post()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user2->id]);

        $update = [
            'title' => 'Post title',
            'slug' => 'post-slug',
            'content' => 'post-content',
            'user_id' => $post->user_id,
            'type_id' => $post->type_id
        ];

        $this->actingAs($user1, 'api')
            ->json('PUT', '/api/posts/' . $post->id, $update)
            ->assertResponseStatus(403);
    }

    public function test_admin_can_update_others_post()
    {
        $post = factory(Post::class)->create();

        $update = [
            'title' => 'Post title',
            'slug' => 'post-slug',
            'content' => 'post-content',
            'user_id' => $post->user_id,
            'type_id' => $post->type_id
        ];

        $this->actingAs(factory(User::class)->create(['is_admin' => true]), 'api')
            ->json('PUT', '/api/posts/' . $post->id, $update)
            ->assertResponseStatus(202);
    }

    public function test_unauthenticated_cannot_delete_post()
    {
        $post = factory(Post::class)->create();

        $this->json('DELETE', '/api/posts/' . $post->id)
            ->assertResponseStatus(401);
    }

    public function test_authenticated_can_delete_post_its_post()
    {
        $post = factory(Post::class)->create();

        $this->actingAs($post->user, 'api')
            ->json('DELETE', '/api/posts/' . $post->id)
            ->assertResponseStatus(204);
    }

    public function test_authenticated_cannot_delete_others_post()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user2->id]);

        $this->actingAs($user1, 'api')
            ->json('DELETE', '/api/posts/' . $post->id)
            ->assertResponseStatus(403);
    }

    public function test_admin_can_delete_others_post()
    {
        $post = factory(Post::class)->create();

        $this->actingAs(factory(User::class)->create(['is_admin' => true]), 'api')
            ->json('DELETE', '/api/posts/' . $post->id)
            ->assertResponseStatus(204);
    }
}
