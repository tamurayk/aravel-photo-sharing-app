<?php
declare(strict_types=1);

namespace App\Http\UseCases\User\Post\Interfaces;

use Illuminate\Http\UploadedFile;

interface ImageStoreInterface
{
    public function __construct();

    public function __invoke(int $userId, UploadedFile $image, string $savePath): string;
}
