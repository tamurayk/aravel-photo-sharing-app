<?php

namespace Tests\Feature\app\Http\Controllers\User\Auth;

use App\Http\Controllers\User\Home\HomeIndexController;
use Tests\AppTestCase;
use Tests\Traits\RoutingTestTrait;

class HomeIndexControllerTest extends AppTestCase
{
    use RoutingTestTrait;

    public function setUp(): void
    {
        parent::setUp();
    }


    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Override to \Tests\Traits\RoutingTestTrait::RoutingTestDataProvider
     * @return array
     */
    public function RoutingTestDataProvider()
    {
        $this->createApplication();
        $baseUrl = config('app.url');

        return [
            [
                'GET',
                $baseUrl. '/home',
                HomeIndexController::class,
                'home.index',
            ],
        ];
    }
}
