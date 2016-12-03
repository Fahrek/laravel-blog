<?php

use App\Type;
use App\User;

class TypePolicyTest extends TestCase
{
    public function test_unauthenticated_can_view_type()
    {
        $this->json('GET', '/api/types')
            ->assertResponseStatus(200);
    }

    public function test_unauthenticated_can_view_types()
    {
        $type = factory(Type::class)->create();

        $this->json('GET', '/api/types/' . $type->id)
            ->assertResponseStatus(200);
    }

    public function test_unauthenticated_cannot_create_type()
    {
        $newType = [
            'name' => 'Type name',
            'slug' => 'type-name'
        ];

        $this->json('POST', '/api/types', $newType)
            ->assertResponseStatus(401)
            ->seeJson($this->unauthorizedResponse);
    }

    public function test_authenticated_cannot_create_type()
    {
        $newType = [
            'name' => 'Type name',
            'slug' => 'type-name'
        ];

        $this->actingAs(factory(User::class)->create(), 'api')
            ->json('POST', '/api/types', $newType)
            ->assertResponseStatus(403)
            ->seeJson($this->forbiddenResponse);
    }

    public function test_admin_can_create_type()
    {
        $newType = [
            'name' => 'Type name',
            'slug' => 'type-name'
        ];

        $this->actingAs(factory(User::class)->create(['is_admin' => true]), 'api')
            ->json('POST', '/api/types', $newType)
            ->assertResponseStatus(201);
    }

    public function test_unauthenticated_cannot_update_type()
    {
        $type = factory(Type::class)->create();

        $update = [
            'name' => 'Updated name',
            'slug' => 'updated-slug'
        ];

        $this->json('PUT', '/api/types/' . $type->id, $update)
            ->assertResponseStatus(401)
            ->seeJson($this->unauthorizedResponse);
    }

    public function test_authenticated_cannot_update_type()
    {
        $type = factory(Type::class)->create();

        $update = [
            'name' => 'Updated name',
            'slug' => 'updated-slug'
        ];

        $this->actingAs(factory(User::class)->create(), 'api')
            ->json('PUT', '/api/types/' . $type->id, $update)
            ->assertResponseStatus(403)
            ->seeJson($this->forbiddenResponse);
    }

    public function test_admin_can_update_type()
    {
        $type = factory(Type::class)->create();

        $update = [
            'name' => 'Updated name',
            'slug' => 'updated-slug'
        ];

        $this->actingAs(factory(User::class)->create(['is_admin' => true]), 'api')
            ->json('PUT', '/api/types/' . $type->id, $update)
            ->assertResponseStatus(202);
    }

    public function test_unauthenticated_cannot_delete_type()
    {
        $type = factory(Type::class)->create();

        $this->json('DELETE', '/api/types/' . $type->id)
            ->assertResponseStatus(401)
            ->seeJson($this->unauthorizedResponse);
    }

    public function test_authenticated_cannot_delete_type()
    {
        $type = factory(Type::class)->create();

        $this->actingAs(factory(User::class)->create(), 'api')
            ->json('DELETE', '/api/types/' . $type->id)
            ->assertResponseStatus(403)
            ->seeJson($this->forbiddenResponse);
    }

    public function test_admin_can_delete_type()
    {
        $type = factory(Type::class)->create();

        $this->actingAs(factory(User::class)->create(['is_admin' => true]), 'api')
            ->json('DELETE', '/api/types/' . $type->id)
            ->assertResponseStatus(204);
    }
}
