<?php

namespace App\Providers;

use App\Policies\PostPolicy;
use App\Policies\TypePolicy;
use App\Policies\UserPolicy;
use App\Post;
use App\Type;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Type::class => TypePolicy::class,
        Post::class => PostPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Passport::routes();

        // Passport::tokensCan([
        //     'admin-scope' => 'Do everything',
        //     'editor-scope' => 'Do some thing',
        // ]);
    }
}
