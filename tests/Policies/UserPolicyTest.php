<?php

use App\User;

class UserPolicyTest extends TestCase
{
    public function test_unauthenticated_can_view_users()
    {
        $this->json('GET', '/api/users')
            ->assertResponseStatus(200);
    }

    public function test_unauthenticated_can_view_user()
    {
        $this->json('GET', '/api/users/1')
            ->assertResponseStatus(200);
    }

    public function test_unauthenticated_cannot_create_user()
    {
        $newUser = [
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $this->json('POST', '/api/users', $newUser)
            ->assertResponseStatus(401)
            ->seeJson($this->unauthorizedResponse);
    }

    public function test_authenticated_can_create_user()
    {
        $newUser = [
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $this->actingAs(factory(User::class)->create(), 'api')
            ->json('POST', '/api/users', $newUser)
            ->assertResponseStatus(201);
    }

    public function test_unauthenticated_cannot_update_user()
    {
        $oldUser = factory(User::class)->create();

        $updatedUser = [
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $this->json('PUT', '/api/users/' . $oldUser->id, $updatedUser)
            ->assertResponseStatus(401)
            ->seeJson($this->unauthorizedResponse);
    }

    public function test_authenticated_cannot_update_other_user()
    {
        $oldUser = factory(User::class)->create();

        $updatedUser = [
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $this->actingAs(factory(User::class)->create(), 'api')
            ->json('PUT', '/api/users/' . $oldUser->id, $updatedUser)
            ->assertResponseStatus(403)
            ->seeJson($this->forbiddenResponse);
    }

    public function test_authenticated_can_update_itself()
    {
        $oldUser = factory(User::class)->create();

        $updatedUser = [
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $this->actingAs($oldUser, 'api')
            ->json('PUT', '/api/users/' . $oldUser->id, $updatedUser)
            ->assertResponseStatus(202);
    }

    public function test_unauthenticated_cannot_delete_user()
    {
        $user = factory(User::class)->create();

        $this->json('DELETE', '/api/users/' . $user->id)
            ->assertResponseStatus(401)
            ->seeJson($this->unauthorizedResponse);
    }

    public function test_authenticated_cannot_delete_user_if_he_is_not_first_user()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('DELETE', '/api/users/' . $user->id)
            ->assertResponseStatus(403)
            ->seeJson($this->forbiddenResponse);
    }

    public function test_authenticated_first_user_can_delete_other_user()
    {
        $user = factory(User::class)->create();

        $this->actingAs(User::admin()->first(), 'api')
            ->json('DELETE', '/api/users/' . $user->id)
            ->assertResponseStatus(204);
    }
}
