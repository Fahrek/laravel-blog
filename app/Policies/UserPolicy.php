<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the user.
     *
     * @param  \App\User $user
     * @param  \App\User $userResource
     * @return bool
     */
    public function view(User $user, User $userResource)
    {
        return true;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user === auth()->user();
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  \App\User $user
     * @param  \App\User $userResource
     * @return bool
     */
    public function update(User $user, User $userResource)
    {
        return $user->id === $userResource->id;
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function delete(User $user)
    {
        return (bool) $user->is_admin;
    }
}
