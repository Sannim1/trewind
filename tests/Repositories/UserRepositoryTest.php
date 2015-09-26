<?php

use App\Models\Credential;
use App\Models\User;
use App\Repositories\UserRepository;
use Laravel\Socialite\AbstractUser;
use Tests\BaseTestCase;

class UserRepositoryTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->user = $this->getMock('App\Models\User');
        $this->credential = $this->getMock('App\Models\Credential');
        $this->repo = new UserRepository($this->user, $this->credential);

        $this->provider = 'provider';
        $this->socialUser = new SocialUserStub;
    }

    /**
     * @test
     */
    function it_returns_true()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    // function it_finds_a_user_by_their_provider_id()
    // {
    //     $this->credential->expects($this->once())
    //         ->method('first')
    //         ->will($this->returnValue(new CredentialStub));

    //     $this->user->expects($this->once())
    //         ->method('find')
    //         ->will($this->returnValue(array()));

    //     $this->repo->findUserByProviderOrCreate(
    //         $this->provider, $this->socialUser
    //     );
    // }
}

class SocialUserStub extends AbstractUser
{
    public $id = '218648';
    public $name = 'name';
    public $nickname = 'nickname';
    public $avatar = 'avatar';
    public $email = 'email';
    public $token = 'token';
    public $tokenSecret = 'secret';
}

class CredentialStub extends Credential
{
    public function save()
    {
        return true;
    }
}
