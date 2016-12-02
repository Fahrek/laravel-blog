<?php

use App\Transformers\TypeTransformer;
use App\Type;

class TypeTransformerTest extends TestCase
{
    public function test_it_should_transform_type()
    {
        $type = factory(Type::class)->create();

        $transformed = (new TypeTransformer())->transform($type);

        $this->assertEquals([
            'id' => $type->id,
            'name' => $type->name,
            'slug' => $type->slug
        ], $transformed);
        $this->assertInternalType('int', $transformed['id']);
        $this->assertInternalType('string', $transformed['name']);
        $this->assertInternalType('string', $transformed['slug']);
    }
}
