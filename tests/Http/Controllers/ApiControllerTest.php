<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;

class ApiControllerTest extends TestCase
{
    /**
     * @var \App\Http\Controllers\ApiController
     */
    protected $api;

    public function setUp()
    {
        parent::setUp();

        $this->api = new ApiController();
    }

    public function test_it_should_have_static_error_codes()
    {
        $this->assertNotEmpty(ApiController::CODE_WRONG_ARGS);
        $this->assertNotEmpty(ApiController::CODE_NOT_FOUND);
        $this->assertNotEmpty(ApiController::CODE_INTERNAL_ERROR);
        $this->assertNotEmpty(ApiController::CODE_UNAUTHORIZED);
        $this->assertNotEmpty(ApiController::CODE_FORBIDDEN);
        $this->assertNotEmpty(ApiController::CODE_INVALID_MIME_TYPE);
        $this->assertNotEmpty(ApiController::CODE_UNPROCESSABLE_ENTITY);
    }

    public function test_get_status_code()
    {
        $this->assertEquals(200, $this->api->getStatusCode());
    }

    public function test_set_status_code()
    {
        $this->api->setStatusCode(404);

        $this->assertEquals(404, $this->api->getStatusCode());
    }



    public function test_error_not_found()
    {
        $response = $this->api->errorNotFound();
        $data = $response->getData();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertObjectHasAttribute('error', $data);
        $this->assertObjectHasAttribute('code', $data->error);
        $this->assertObjectHasAttribute('status', $data->error);
        $this->assertObjectHasAttribute('messages', $data->error);
        $this->assertInternalType('array', $data->error->messages);
        $this->assertEquals(['Resource not found.'], $data->error->messages);
        $this->assertEquals(ApiController::CODE_NOT_FOUND, $data->error->code);

        $response = $this->api->errorNotFound('Custom message.');
        $data = $response->getData();
        $this->assertEquals(['Custom message.'], $data->error->messages);
    }

    public function test_error_forbidden()
    {
        $response = $this->api->errorForbidden();
        $data = $response->getData();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertObjectHasAttribute('error', $data);
        $this->assertObjectHasAttribute('code', $data->error);
        $this->assertObjectHasAttribute('status', $data->error);
        $this->assertObjectHasAttribute('messages', $data->error);
        $this->assertInternalType('array', $data->error->messages);
        $this->assertEquals(['Forbidden.'], $data->error->messages);
        $this->assertEquals(ApiController::CODE_FORBIDDEN, $data->error->code);

        $response = $this->api->errorNotFound('Custom message.');
        $data = $response->getData();
        $this->assertEquals(['Custom message.'], $data->error->messages);
    }

    public function test_error_internal_error()
    {
        $response = $this->api->errorInternalError();
        $data = $response->getData();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertObjectHasAttribute('error', $data);
        $this->assertObjectHasAttribute('code', $data->error);
        $this->assertObjectHasAttribute('status', $data->error);
        $this->assertObjectHasAttribute('messages', $data->error);
        $this->assertInternalType('array', $data->error->messages);
        $this->assertEquals(['Internal error.'], $data->error->messages);
        $this->assertEquals(ApiController::CODE_INTERNAL_ERROR, $data->error->code);

        $response = $this->api->errorNotFound('Custom message.');
        $data = $response->getData();
        $this->assertEquals(['Custom message.'], $data->error->messages);
    }

    public function test_error_unauthorized()
    {
        $response = $this->api->errorUnauthorized();
        $data = $response->getData();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertObjectHasAttribute('error', $data);
        $this->assertObjectHasAttribute('code', $data->error);
        $this->assertObjectHasAttribute('status', $data->error);
        $this->assertObjectHasAttribute('messages', $data->error);
        $this->assertInternalType('array', $data->error->messages);
        $this->assertEquals(['Unauthorized.'], $data->error->messages);
        $this->assertEquals(ApiController::CODE_UNAUTHORIZED, $data->error->code);

        $response = $this->api->errorNotFound('Custom message.');
        $data = $response->getData();
        $this->assertEquals(['Custom message.'], $data->error->messages);
    }

    public function test_error_wrong_args()
    {
        $response = $this->api->errorWrongArgs();
        $data = $response->getData();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertObjectHasAttribute('error', $data);
        $this->assertObjectHasAttribute('code', $data->error);
        $this->assertObjectHasAttribute('status', $data->error);
        $this->assertObjectHasAttribute('messages', $data->error);
        $this->assertInternalType('array', $data->error->messages);
        $this->assertEquals(['Wrong arguments.'], $data->error->messages);
        $this->assertEquals(ApiController::CODE_WRONG_ARGS, $data->error->code);

        $response = $this->api->errorNotFound('Custom message.');
        $data = $response->getData();
        $this->assertEquals(['Custom message.'], $data->error->messages);
    }

    public function test_error_unprocessable_entity()
    {
        $response = $this->api->errorUnprocessableEntity(new MessageBag(['Custom message.']));
        $data = $response->getData();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertObjectHasAttribute('error', $data);
        $this->assertObjectHasAttribute('code', $data->error);
        $this->assertObjectHasAttribute('status', $data->error);
        $this->assertObjectHasAttribute('messages', $data->error);
        $this->assertInternalType('array', $data->error->messages);
        $this->assertEquals([['Custom message.']], $data->error->messages);
        $this->assertEquals(ApiController::CODE_UNPROCESSABLE_ENTITY, $data->error->code);
    }
}
