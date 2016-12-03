<?php

use App\Post;
use App\Type;
use App\User;

class HandlerTest extends TestCase
{
    public function test_it_should_respond_with_not_found_if_user_does_not_exist()
    {
        $user = factory(User::class)->make();

        $userRequest = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password
        ];

        $this->json('GET', '/api/users/999999999')
            ->assertResponseStatus(404)
            ->seeJson(['error' => 'Resource not found.']);

        $this->actingAs(factory(User::class)->create(['is_admin' => true]), 'api')
            ->json('PUT', '/api/users/999999999', $userRequest)
            ->assertResponseStatus(404)
            ->seeJson(['error' => 'Resource not found.']);

        $this->actingAs(factory(User::class)->create(['is_admin' => true]), 'api')
            ->json('DELETE', '/api/users/999999999')
            ->assertResponseStatus(404)
            ->seeJson(['error' => 'Resource not found.']);
    }

    public function test_it_should_respond_with_not_found_if_type_does_not_exist()
    {
        $this->json('GET', '/api/types/999999999')
            ->assertResponseStatus(404)
            ->seeJson(['error' => 'Resource not found.']);

        $this->actingAs(factory(User::class)->create(['is_admin' => true]), 'api')
            ->json('PUT', '/api/types/999999999', factory(Type::class)->make()->toArray())
            ->assertResponseStatus(404)
            ->seeJson(['error' => 'Resource not found.']);

        $this->actingAs(factory(User::class)->create(['is_admin' => true]), 'api')
            ->json('DELETE', '/api/types/999999999')
            ->assertResponseStatus(404)
            ->seeJson(['error' => 'Resource not found.']);
    }

    public function test_it_should_respond_with_not_found_if_post_does_not_exist()
    {
        $this->json('GET', '/api/posts/999999999')
            ->assertResponseStatus(404)
            ->seeJson(['error' => 'Resource not found.']);

        $this->actingAs(factory(User::class)->create(['is_admin' => true]), 'api')
            ->json('PUT', '/api/posts/999999999', factory(Post::class)->make()->toArray())
            ->assertResponseStatus(404)
            ->seeJson(['error' => 'Resource not found.']);

        $this->actingAs(factory(User::class)->create(['is_admin' => true]), 'api')
            ->json('DELETE', '/api/posts/999999999')
            ->assertResponseStatus(404)
            ->seeJson(['error' => 'Resource not found.']);
    }

    public function test_it_should_respond_with_not_found_if_endpoint_does_not_exist()
    {
        $this->json('GET', '/api/a-route-that-does-not-exist')
            ->assertResponseStatus(404)
            ->seeJson(['error' => 'Endpoint not found.']);
    }
}
