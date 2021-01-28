<?php
declare(strict_types=1);

namespace Tests\Feature\app\Http\Controllers\User\Auth;

use Illuminate\Support\Facades\DB;
use Tests\AppTestCase;
use Tests\Traits\RoutingTestTrait;

class RegisterControllerTest extends AppTestCase
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

    public function testRouting()
    {
        $this->markTestIncomplete();
    }

    public function testMiddleware()
    {
        $this->markTestIncomplete();
    }

    public function testRegister()
    {
        $data = [
            'email' => 'hoge@example.com',
            'name' => 'hoge',
            'password' => 'passw0rd',
            'password_confirmation' => 'passw0rd'
        ];
        $response = $this->post('/register', $data);
        $response->assertStatus(302);
        $response->assertLocation('http://localhost/home');
        $response->assertRedirect('http://localhost/home');

        $user = DB::table('users')->where('email', 'hoge@example.com')->first();
        $user_profile = DB::table('user_profiles')->where('user_id', $user->id)->first();
        $this->assertEquals('hoge', $user_profile->name);
    }
}
