<?php
declare(strict_types=1);

namespace Test\Unit\app\Models\Eloquents;

use App\Models\Eloquents\Eloquent;
use App\Models\Eloquents\User;
use App\Models\Eloquents\UserProfile;
use App\Models\Interfaces\BaseInterface;
use App\Models\Interfaces\UserProfileInterface;
use Tests\AppTestCase;

class UserProfileTest extends AppTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }


    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testExtendAndImplements()
    {
        $eloquent = new UserProfile();
        $this->assertTrue(is_subclass_of($eloquent, Eloquent::class));
        $this->assertTrue(is_subclass_of($eloquent, BaseInterface::class));
        $this->assertTrue(is_subclass_of($eloquent, UserProfileInterface::class));
    }

    public function testRelation()
    {
        factory(User::class)->create([
            'id' => 1,
        ]);
        factory(UserProfile::class)->create([
            'id' => 1,
            'user_id' => 1,
        ]);

        $userProfileEloquent = new UserProfile();
        /** @var UserProfile $userProfile */
        $userProfile = $userProfileEloquent->newQuery()->find(1);

        $this->assertEquals(1, $userProfile->user->id);
    }
}
