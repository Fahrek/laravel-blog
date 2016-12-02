<?php

namespace App\Http\Controllers;

use App\Http\Requests\Type\DestroyTypeRequest;
use App\Http\Requests\Type\StoreTypeRequest;
use App\Http\Requests\Type\UpdateTypeRequest;
use App\Transformers\TypeTransformer;
use App\Type;
use Illuminate\Http\JsonResponse;

class TypeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() : JsonResponse
    {
        $paginator = Type::paginate();
        $types = $paginator->getCollection();

        return $this->respondWithPaginatedCollection($types, $paginator, new TypeTransformer(), 'types');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Type\StoreTypeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTypeRequest $request) : JsonResponse
    {
        $type = Type::create([
            'name' => $request->input('name'),
            'slug' => $request->input('slug')
        ]);

        return $this->setStatusCode(201)->show($type);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Type $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Type $type) : JsonResponse
    {
        return $type
            ? $this->respondWithItem($type, new TypeTransformer(), 'types')
            : $this->errorNotFound();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Type\UpdateTypeRequest $request
     * @param \App\Type $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTypeRequest $request, Type $type) : JsonResponse
    {
        $type->name = $request->input('name');
        $type->slug = $request->input('slug');
        $type->save();

        return $this->setStatusCode(202)->show($type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Http\Requests\Type\DestroyTypeRequest $request
     * @param \App\Type $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyTypeRequest $request, Type $type) : JsonResponse
    {
        $type->delete();

        return $this->respondWithNoContent();
    }
}
