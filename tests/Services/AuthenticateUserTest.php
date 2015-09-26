<?php

use App\Services\AuthenticateUser;
use Tests\BaseTestCase;

class AuthenticateUserTest extends BaseTestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->socialite = $this->getMock('Laravel\Socialite\Contracts\Factory');
        $this->auth = $this->getMock('Illuminate\Contracts\Auth\Guard');
        $this->users = $this->getMock('App\Contracts\Repository\UserRepositoryInterface');

        $this->authorizedRequest = ['non-empty request array'];
        $this->unauthorizedRequest = [];
    }
    /**
     * @test
     */
    function it_authenticates_a_user()
    {
        $authenticator = new AuthenticateUser(
            $this->socialite, $this->auth, $this->users
        );

        $this->socialite->expects($this->atLeastOnce())
            ->method('driver')
            ->will($this->returnValue(new ProviderStub));

        $this->users->expects($this->once())
            ->method('findUserByProviderOrCreate')
            ->will($this->returnValue(new UserStub));

        $this->auth->expects($this->once())
            ->method('login')
            ->willReturn(true);

        $listener = $this->getMock('App\Contracts\Auth\AuthenticateUserListener');

        $listener->expects($this->once())
            ->method('userHasLoggedIn')
            ->willReturn(true);

        $authenticator->execute($this->authorizedRequest, $listener, 'provider');
    }

    /**
     * @test
     */
    function it_requests_for_authentication_for_an_unauthorized_user()
    {
        $authenticator = new AuthenticateUser(
            $this->socialite, $this->auth, $this->users
        );

        $this->socialite->expects($this->atLeastOnce())
            ->method('driver')
            ->will($this->returnValue(new ProviderStub));

        $listener = $this->getMock('App\Contracts\Auth\AuthenticateUserListener');

        $authenticator->execute($this->unauthorizedRequest, $listener, 'provider');
    }
}

class ProviderStub implements Laravel\Socialite\Two\ProviderInterface
{
    public function redirect()
    {
        return true;
    }

    public function user()
    {
        return new SocialUserStub;
    }
}

class UserStub extends App\Models\User
{

}
