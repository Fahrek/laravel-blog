<?php

use App\Type;
use App\User;

class TypeControllerTest extends TestCase
{
    protected $responseStructure = [
        'data' => [
            '*' => [
                'type',
                'id',
                'attributes' => ['name', 'slug'],
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
        $type = factory(Type::class)->create();

        $this->json('GET', '/api/types')
            ->assertResponseOk()
            ->seeJsonStructure($this->responseStructure)
            ->seeJson([
                'type' => $type->getTable(),
                'id' => (string) $type->id,
                'attributes' => [
                    'name' => $type->name,
                    'slug' => (string) $type->slug
                ],
                'links' => [
                    'self' => url('api/types/' . $type->id)
                ]
            ]);
    }

    public function test_show()
    {
        $type = factory(Type::class)->create();

        $this->json('GET', '/api/types/' . $type->id)
            ->assertResponseOk()
            ->seeJson([
                'data' => [
                    'type' => $type->getTable(),
                    'id' => (string) $type->id,
                    'attributes' => [
                        'name' => $type->name,
                        'slug' => (string) $type->slug
                    ],
                    'links' => [
                        'self' => url('api/types/' . $type->id)
                    ]
                ]
            ]);
    }

    public function test_store()
    {
        $type = [
            'name' => 'Type name',
            'slug' => 'type-name'
        ];

        $this->actingAs(factory(User::class)->create(['is_admin' => true]), 'api')
            ->json('POST', '/api/types', $type)
            ->seeStatusCode(201)
            ->seeJson($type)
            ->seeInDatabase('types', $type);
    }

    public function test_update()
    {
        $user = factory(User::class)->create(['is_admin' => true]);
        $type = factory(Type::class)->create();

        $update = [
            'name' => 'Update name',
            'slug' => 'update-name'
        ];

        $this->actingAs($user, 'api')
            ->json('PUT', '/api/types/' . $type->id, $update)
            ->seeStatusCode(202)
            ->seeJson($update)
            ->seeInDatabase('types', $update);
    }

    public function test_destroy()
    {
        $user = factory(User::class)->create(['is_admin' => true]);
        $type = factory(Type::class)->create();

        $this->actingAs($user, 'api')
            ->json('DELETE', '/api/types/' . $type->id)
            ->seeStatusCode(204)
            ->dontSeeInDatabase('types', [
                'id' => $type->id,
                'name' => $type->name,
                'slug' => $type->slug
            ]);
    }
}
