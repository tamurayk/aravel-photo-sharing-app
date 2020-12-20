<?php
declare(strict_types=1);

namespace App\Http\UseCases\User\Post;

use App\Http\UseCases\User\Post\Interfaces\ImageStoreInterface;
use App\Models\Constants\PostConstants;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageStore implements ImageStoreInterface
{
    public function __construct()
    {
    }

    public function __invoke(
        int $useId,
        UploadedFile $image,
        ?string $saveAs = null
    ): string {
        $savePath = sprintf('%s/%s', PostConstants::BASE_DIR ,$useId);

        $saveName = is_null($saveAs)
            ? sprintf('%s.%s', Str::uuid()->toString(), $image->extension())
            : $saveAs;

        // app/public/$savePath/$saveAs に保存
        // See: src/config/filesystems.php disk.public
        $savedPath = $image->storeAs($savePath, $saveName, ['disk' => 'public']);
        $basename = basename($savedPath);

        return $basename;
    }
}
