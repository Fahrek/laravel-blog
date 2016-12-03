<?php

use App\Http\Controllers\ApiController;
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    use DatabaseMigrations;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    protected $resourceNotFoundResponse = [
        'error' => [
            'code' => ApiController::CODE_NOT_FOUND,
            'status' => 404,
            'messages' => [
                'Resource not found.'
            ]
        ]
    ];

    protected $endpointNotFoundResponse = [
        'error' => [
            'code' => ApiController::CODE_NOT_FOUND,
            'status' => 404,
            'messages' => [
                'Endpoint not found.'
            ]
        ]
    ];

    protected $unauthorizedResponse = [
        'error' => [
            'code' => ApiController::CODE_UNAUTHORIZED,
            'status' => 401,
            'messages' => [
                'Unauthorized.'
            ]
        ]
    ];

    protected $forbiddenResponse = [
        'error' => [
            'code' => ApiController::CODE_FORBIDDEN,
            'status' => 403,
            'messages' => [
                'Forbidden.'
            ]
        ]
    ];

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
}
