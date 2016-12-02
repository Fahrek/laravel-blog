<?php

use App\Http\Controllers\ApiController;

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
}
