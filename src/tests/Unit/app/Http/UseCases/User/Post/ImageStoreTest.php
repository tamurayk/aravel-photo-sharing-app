<?php
declare(strict_types=1);

namespace Test\Unit\app\Http\UseCase\User\Task;

use App\Http\UseCases\User\Post\ImageStore;
use App\Models\Constants\PostConstants;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\AppTestCase;

class ImageStoreTest extends AppTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }


    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testUseCase()
    {
        // See: src/config/filesystems.php disks.public
        Storage::fake('public');

        $uploadedImage = UploadedFile::fake()->image('original.jpg');

        $useCase = new ImageStore();

        $userId = 1;
        $saveAs = sprintf('%s.%s', Str::uuid()->toString(), $uploadedImage->getExtension());
        $useCaseResult = $useCase($userId, $uploadedImage, $saveAs);

        $this->assertEquals($saveAs, $useCaseResult);

        Storage::disk('public')->assertExists(sprintf('%s/%s/%s', PostConstants::BASE_DIR, $userId, $saveAs));
        Storage::disk('public')->assertMissing(sprintf('%s/%s/%s', PostConstants::BASE_DIR, $userId, 'original.jpg'));
    }
}
