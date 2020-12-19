<?php
declare(strict_types=1);

namespace Test\Unit\app\Http\Request\User\Post;

use App\Http\Requests\User\Post\PostStoreRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\AppTestCase;
use Tests\Traits\ValidationTestTrait;

class PostStoreRequestTest extends AppTestCase
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
        $request = new PostStoreRequest();

        $imageFile = UploadedFile::fake()->image('test.jpg');

        /**
         * 正常
         */
        $data = [
            'caption' => str_repeat('a', 255),
            'image' => $imageFile,
        ];
        $expected = [
            'isPass' => true,
            'errMsg' => [],
        ];
        $this->assertValidationRules(
            $request,
            $data,
            $expected
        );

        /**
         * 必須項目エラー
         */
        $data = [
        ];
        $expected = [
            'isPass' => false,
            'errMsg' => [
                'caption' => [
                    'Caption is required.',
                ],
                'image' => [
                    'Image is required.',
                ],
            ],
        ];
        $this->assertValidationRules(
            $request,
            $data,
            $expected
        );

        /**
         * 文字数オーバー
         */
        $data = [
            'caption' => str_repeat('a', 256),
            'image' => $imageFile,
        ];
        $expected = [
            'isPass' => false,
            'errMsg' => [
                'caption' => [
                    'Caption may not be greater than 255 characters.',
                ],
            ],
        ];
        $this->assertValidationRules(
            $request,
            $data,
            $expected
        );

        /**
         * 画像が不正
         */
        $data = [
            'caption' => str_repeat('a', 255),
            'image' => 'test.jpg',
        ];
        $expected = [
            'isPass' => false,
            'errMsg' => [
                'image' => [
                    'Image must be image file.',
                ],
            ],
        ];
        $this->assertValidationRules(
            $request,
            $data,
            $expected
        );
    }
}
