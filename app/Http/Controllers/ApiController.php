<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Resource\Item as FractalItem;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\TransformerAbstract;

class ApiController extends Controller
{
    const CODE_WRONG_ARGS = 'WRONG_ARGS';
    const CODE_NOT_FOUND = 'NOT_FOUND';
    const CODE_INTERNAL_ERROR = 'INTERNAL_ERROR';
    const CODE_UNAUTHORIZED = 'UNAUTHORIZED';
    const CODE_FORBIDDEN = 'FORBIDDEN';
    const CODE_INVALID_MIME_TYPE = 'INVALID_MIME_TYPE';
    const CODE_UNPROCESSABLE_ENTITY = 'UNPROCESSABLE_ENTITY';

    /**
     * A fractal manager instance.
     *
     * @var \League\Fractal\Manager
     */
    protected $fractal;

    /**
     * Default status code.
     *
     * @var int
     */
    protected $statusCode = 200;

    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        $this->fractal = new Manager();
        $this->fractal->setSerializer(new JsonApiSerializer(url('api')));

        if (isset($_GET['include'])) {
            $this->fractal->parseIncludes($_GET['include']);
        }
    }

    /**
     * Get the status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Set the status code.
     *
     * @param int $statusCode
     * @return self
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Respond with a single item.
     *
     * @param \Illuminate\Database\Eloquent\Model $item
     * @param \League\Fractal\TransformerAbstract $callback
     * @param string $key
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithItem(Model $item, TransformerAbstract $callback, string $key = null): JsonResponse
    {
        $resource = new FractalItem($item, $callback, $key);
        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * Respond with a collection of items.
     *
     * @param \Illuminate\Database\Eloquent\Collection $item
     * @param \League\Fractal\TransformerAbstract $callback
     * @param string $key
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithCollection(
        Collection $item,
        TransformerAbstract $callback,
        string $key
    ): JsonResponse
    {
        $resource = new FractalCollection($item, $callback, $key);
        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray(), [
            'X-Total-Count' => $item->count(),
            'X-Total-Page' => $item->count() ? 1 : 0
        ]);
    }

    /**
     * Respond with a paginated collection of items.
     *
     * @param \Illuminate\Database\Eloquent\Collection $item
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator
     * @param \League\Fractal\TransformerAbstract $callback
     * @param string $key
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithPaginatedCollection(
        Collection $item,
        LengthAwarePaginator $paginator,
        TransformerAbstract $callback,
        string $key
    ): JsonResponse
    {
        $resource = new FractalCollection($item, $callback, $key);

        // preserve query params in pagination links
        $queryParams = array_diff_key($_GET, array_flip(['page']));
        $paginator->appends($queryParams);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray(), [
            'X-Total-Count' => $paginator->total(),
            'X-Total-Page' => $paginator->lastPage()
        ]);
    }

    /**
     * Generate the json Response array.
     *
     * @param array $array
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithArray(array $array, array $headers = []): JsonResponse
    {
        return response()
            ->json($array, $this->statusCode, $headers);
    }

    /**
     * Generate an error Response.
     *
     * @param string $message
     * @param string $errorCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithError(string $message, string $errorCode): JsonResponse
    {
        return $this->respondWithArray([
            'error' => [
                'code' => $errorCode,
                'http_code' => $this->statusCode,
                'message' => $message,
            ]
        ]);
    }

    /**
     * Generate a response with no content.
     *
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithNoContent(array $headers = []): JsonResponse
    {
        return response()->json(null, 204, $headers);
    }

    /**
     * Generates a Response with a 403 HTTP header and a given message.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorForbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->setStatusCode(403)
            ->respondWithError($message, self::CODE_FORBIDDEN);
    }

    /**
     * Generates a Response with a 500 HTTP header and a given message.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorInternalError(string $message = 'Internal Error'): JsonResponse
    {
        return $this->setStatusCode(500)
            ->respondWithError($message, self::CODE_INTERNAL_ERROR);
    }

    /**
     * Generates a Response with a 404 HTTP header and a given message.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorNotFound(string $message = 'Resource Not Found'): JsonResponse
    {
        return $this->setStatusCode(404)
            ->respondWithError($message, self::CODE_NOT_FOUND);
    }

    /**
     * Generates a Response with a 401 HTTP header and a given message.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorUnauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->setStatusCode(401)
            ->respondWithError($message, self::CODE_UNAUTHORIZED);
    }

    /**
     * Generates a Response with a 400 HTTP header and a given message.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorWrongArgs(string $message = 'Wrong Arguments'): JsonResponse
    {
        return $this->setStatusCode(400)
            ->respondWithError($message, self::CODE_WRONG_ARGS);
    }

    /**
     * Generates a Response with a 422 HTTP header and a given message array.
     *
     * @param \Illuminate\Contracts\Support\MessageBag $messages
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorUnprocessableEntity(MessageBag $messages): JsonResponse
    {
        return $this->setStatusCode(422)
            ->respondWithArray([
                'error' => [
                    'code' => self::CODE_UNPROCESSABLE_ENTITY,
                    'http_code' => 422,
                    'message' => $messages
                ]
            ]);
    }
}
