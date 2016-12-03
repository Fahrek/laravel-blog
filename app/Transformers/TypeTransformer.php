<?php

namespace App\Transformers;

use App\Type;
use League\Fractal\TransformerAbstract;

class TypeTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param \App\Type $type
     * @return array
     */
    public function transform(Type $type) : array
    {
        return [
            'id' => (int) $type->id,
            'name' => (string) $type->name,
            'slug' => (string) $type->slug
        ];
    }
}
