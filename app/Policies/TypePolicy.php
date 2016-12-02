<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the type.
     *
     * @return bool
     */
    public function view()
    {
        return true;
    }

    /**
     * Determine whether the user can create types.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return (bool) $user->is_admin;
    }

    /**
     * Determine whether the user can update the type.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function update(User $user)
    {
        return (bool) $user->is_admin;
    }

    /**
     * Determine whether the user can delete the type.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function delete(User $user)
    {
        return (bool) $user->is_admin;
    }
}
