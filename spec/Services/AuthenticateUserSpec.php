<?php

namespace spec\App\Services;

use App\Models\User;
use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Laravel\Socialite\AbstractUser as SocialUser;
use Illuminate\Contracts\Auth\Guard;
use Laravel\Socialite\Contracts\Factory;
use Laravel\Socialite\Two\ProviderInterface;
use App\Contracts\Auth\AuthenticateUserListener;
use App\Contracts\Repository\UserRepositoryInterface;

class AuthenticateUserSpec extends ObjectBehavior
{
    // $this->authorized_request = ;

    function let(Factory $socialite, Guard $auth, UserRepositoryInterface $users)
    {
        $this->beConstructedWith($socialite, $auth, $users);
    }

    function it_requests_authorization_from_auth_provider(
        Factory $socialite,
        ProviderInterface $provider,
        AuthenticateUserListener $listener
    )
    {
        $driver = 'dummydriver';
        $emptyRequest = [];

        $provider->redirect()->shouldBeCalled();
        $socialite->driver($driver)->willReturn($provider);

        $this->execute($emptyRequest, $listener, $driver);
    }

    function it_creates_a_user_if_authorization_is_granted(
        Factory $socialite,
        UserRepositoryInterface $users,
        Guard $auth,
        User $user,
        AuthenticateUserListener $listener
    )
    {
        $provider = 'dummyprovider';
        $authenticatedRequest = ['token' => 'dummytoken'];

        $providerStub = new ProviderStub;

        $socialite->driver($provider)->willReturn($providerStub);
        $users->findUserByProviderOrCreate($provider, $providerStub->user())
            ->willReturn($user);
        $auth->login($user, true)->shouldBeCalled();
        $listener->userHasLoggedIn($user)->shouldBeCalled();

        $this->execute($authenticatedRequest, $listener, $provider);
    }
}

class ProviderStub
{
    public function user()
    {
        return new UserStub;
    }
}

class UserStub extends SocialUser{}
