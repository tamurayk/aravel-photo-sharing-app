<?php
declare(strict_types=1);

namespace App\Http\UseCases\User\Auth\Interfaces;

use App\Models\Eloquents\User;
use App\Models\Interfaces\UserInterface;

interface RegisterInterface
{
    public function __construct(
        UserInterface $user
    );

    public function __invoke(
        array $data
    ): User;
}
