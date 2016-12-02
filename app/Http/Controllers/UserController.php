<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\DestroyUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\JsonResponse;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() : JsonResponse
    {
        $paginator = User::paginate();
        $users = $paginator->getCollection();

        return $this->respondWithPaginatedCollection($users, $paginator, new UserTransformer(), 'users');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\User\StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request) : JsonResponse
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);

        return $this->setStatusCode(201)->show($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user) : JsonResponse
    {
        return $this->respondWithItem($user, new UserTransformer(), 'users');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\User\UpdateUserRequest $request
     * @param \App\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user) : JsonResponse
    {
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return $this->setStatusCode(202)->show($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Http\Requests\User\DestroyUserRequest $request
     * @param \App\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyUserRequest $request, User $user) : JsonResponse
    {
        $user->delete();

        return $this->respondWithNoContent();
    }
}
