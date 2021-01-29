<?php
declare(strict_types=1);

namespace App\Http\UseCases\User\Auth;

use App\Http\UseCases\User\Auth\Exceptions\RegisterException;
use App\Http\UseCases\User\Auth\Interfaces\RegisterInterface;
use App\Models\Eloquents\User;
use App\Models\Interfaces\UserInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Register implements RegisterInterface
{
    /** @var User */
    private $userEloquent;

    public function __construct(
        UserInterface $user
    ) {
        $this->userEloquent = $user;
    }

    /**
     * @param array $data
     * @return User
     * @throws RegisterException
     */
    public function __invoke(
        array $data
    ): User {
        DB::beginTransaction();
        try {
            $userFill = [
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ];
            $userProfileFill = [
                'name' => $data['name'],
            ];

            $user = $this->userEloquent->newInstance($userFill);
            if (!$user->save()) {
                throw new \Exception('Failed to create user when sign up.');
            }
            $userProfile = $user->user_profile()->create($userProfileFill);
            if (!$userProfile) {
                throw new \Exception('Failed to create userProfile when sign up.');
            }

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new RegisterException(
                'Failed to sign up.',
                is_numeric($e->getCode()) ? $e->getCode() : 500,
                $e //Throwable $previous
            );
        }
    }
}
