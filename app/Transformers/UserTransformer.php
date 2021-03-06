<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param \App\User $user
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id' => (int) $user->id,
            'name' => (string) $user->name,
            'created_at' => (string) $user->created_at,
            'updated_at' => (string) $user->updated_at
        ];
    }
}
