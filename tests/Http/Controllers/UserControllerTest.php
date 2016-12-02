<?php

use App\User;

class UserControllerTest extends TestCase
{
    protected $responseStructure = [
        'data' => [
            '*' => [
                'type',
                'id',
                'attributes' => ['name', 'created_at', 'updated_at'],
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
        $user = factory(User::class)->create();

        $this->json('GET', '/api/users')
            ->assertResponseOk()
            ->seeJsonStructure($this->responseStructure)
            ->seeJson([
                'type' => $user->getTable(),
                'id' => (string) $user->id,
                'attributes' => [
                    'name' => $user->name,
                    'created_at' => (string) $user->created_at,
                    'updated_at' => (string) $user->updated_at
                ],
                'links' => [
                    'self' => url('api/users/' . $user->id)
                ]
            ]);
    }

    public function test_show()
    {
        $user = factory(User::class)->create();

        $this->json('GET', '/api/users/' . $user->id)
            ->assertResponseOk()
            ->seeJson([
                'data' => [
                    'type' => $user->getTable(),
                    'id' => (string) $user->id,
                    'attributes' => [
                        'name' => $user->name,
                        'created_at' => (string) $user->created_at,
                        'updated_at' => (string) $user->updated_at
                    ],
                    'links' => [
                        'self' => url('api/users/' . $user->id)
                    ]
                ]
            ]);
    }

    public function test_store()
    {
        $user = factory(User::class)->create();

        $newUser = [
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $this->actingAs($user, 'api')
            ->json('POST', '/api/users', $newUser)
            ->seeStatusCode(201)
            ->seeJson(['name' => 'User'])
            ->seeInDatabase('users', [
                'name' => 'User',
                'email' => 'user@example.com'
            ])
            ->assertTrue(
                password_verify(
                    $newUser['password'],
                    User::where('email', $newUser['email'])->first()->password
                )
            );
    }

    public function test_update()
    {
        $user = factory(User::class)->create();

        $update = [
            'name' => 'User updated name',
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $this->actingAs($user, 'api')
            ->json('PUT', '/api/users/' . $user->id, $update)
            ->seeStatusCode(202)
            ->seeJson(['name' => 'User updated name'])
            ->seeInDatabase('users', [
                'id' => $user->id,
                'name' => 'User updated name',
                'email' => 'user@example.com',
            ])
            ->assertTrue(
                password_verify(
                    $update['password'],
                    User::find($user->id)->password
                )
            );
    }

    public function test_destroy()
    {
        $user = factory(User::class)->create();

        $this->actingAs(User::find(1), 'api')
            ->json('DELETE', '/api/users/' . $user->id)
            ->seeStatusCode(204)
            ->dontSeeInDatabase('users', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }
}
