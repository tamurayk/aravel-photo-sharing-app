<?php
declare(strict_types=1);

namespace Test\Unit\app\Http\Request\User\Task;

use App\Http\Requests\User\Task\TaskStoreRequest;
use Tests\AppTestCase;
use Tests\Traits\ValidationTestTrait;

class TaskStoreRequestTest extends AppTestCase
{
    use ValidationTestTrait;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testValidation()
    {
        $taskStoreRequest = new TaskStoreRequest();

        /**
         * 正常
         */
        $data = [
            'name' => str_repeat('a', 10)
        ];
        $expected = [
            'isPass' => true,
            'errMsg' => [],
        ];
        $this->assertValidationRules(
            $taskStoreRequest,
            $data,
            $expected
        );

        /**
         * 必須項目エラー(name)
         */
        $data = [
        ];
        $expected = [
            'isPass' => false,
            'errMsg' => [
                'name' => [
                    'Task Name is required.',
                ],
            ],
        ];
        $this->assertValidationRules(
            $taskStoreRequest,
            $data,
            $expected
        );

        /**
         * 文字数オーバー(name)
         */
        $data = [
            'name' => str_repeat('a', 11)
        ];
        $expected = [
            'isPass' => false,
            'errMsg' => [
                'name' => [
                    'Task Name may not be greater than 10 characters.',
                ],
            ],
        ];
        $this->assertValidationRules(
            $taskStoreRequest,
            $data,
            $expected
        );
    }
}
