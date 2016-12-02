<?php

use App\Transformers\UserTransformer;
use App\User;

class UserTransformerTest extends TestCase
{
    public function test_it_should_transform_user()
    {
        $user = factory(User::class)->create();

        $transformed = (new UserTransformer())->transform($user);

        $this->assertEquals([
            'id' => $user->id,
            'name' => $user->name,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ], $transformed);
        $this->assertInternalType('int', $transformed['id']);
        $this->assertInternalType('string', $transformed['name']);
        $this->assertInternalType('string', $transformed['created_at']);
        $this->assertInternalType('string', $transformed['updated_at']);
    }
}
