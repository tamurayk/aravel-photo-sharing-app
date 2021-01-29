<?php
declare(strict_types=1);

namespace Test\Unit\app\Http\UseCase\User\Post;

use App\Http\UseCases\User\Auth\Register;
use App\Models\Eloquents\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\AppTestCase;

class RegisterTest extends AppTestCase
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
        $useCase = new Register(new User());
        $data = [
            'email' => 'test@example.com',
            'password' => 'passw0rd',
            'name' => 'hoge',
        ];
        $result = $useCase($data);

        $this->assertEquals('test@example.com', $result->email);
        $this->assertTrue(Hash::check('passw0rd', $result->password));
        $this->assertEquals('hoge', $result->user_profile->name);

        $user = DB::table('users')->where('email', 'test@example.com')->first();
        $this->assertEquals($user->id, $result->id);
        $this->assertEquals($user->email, $result->email);

        $userProfile = DB::table('user_profiles')->where('user_id', $result->id)->first();
        $this->assertEquals($userProfile->name, $result->user_profile->name);
    }
}
