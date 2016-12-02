<?php

namespace App\Policies;

use App\User;
use App\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the post.
     *
     * @return bool
     */
    public function view()
    {
        return true;
    }

    /**
     * Determine whether the user can create posts.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user === auth()->user();
    }

    /**
     * Determine whether the user can update the post.
     *
     * @param  \App\User $user
     * @param  \App\Post $post
     * @return bool
     */
    public function update(User $user, Post $post)
    {
        return $user->is_admin
            ? true
            : $user->id == $post->user_id;
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param  \App\User $user
     * @param  \App\Post $post
     * @return bool
     */
    public function delete(User $user, Post $post)
    {
        return $user->is_admin
            ? true
            : $user->id == $post->user_id;
    }
}
