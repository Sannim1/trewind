<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use App\Contracts\Auth\AuthenticateUserListener;
use App\Contracts\Repository\UserRepositoryInterface;
use Laravel\Socialite\Contracts\Factory as Socialite;

/**
 * authenticates a user
 *
 * @package App
 * @author
 **/
class AuthenticateUser
{
    protected $socialite;
    protected $auth;
    protected $users;


    public function __construct(
        Socialite $socialite,
        Guard $auth,
        UserRepositoryInterface $users
    )
    {
        $this->socialite = $socialite;
        $this->auth = $auth;
        $this->users = $users;
    }

    /**
     * executes user authentication
     *
     * @return void
     * @author
     **/
    public function execute(
        array $request,
        AuthenticateUserListener $listener,
        $provider
    )
    {
        if (! $request) {
            return $this->getAuthorizationFirst($provider);
        }

        $user = $this->users->findUserByProviderOrCreate(
            $provider,
            $this->getUser($provider)
        );

        $this->auth->login($user, true);

        return $listener->userHasLoggedIn($user);
    }

    /**
     * redirects to provider for authorization
     *
     * @return void
     * @author
     **/
    private function getAuthorizationFirst($driver)
    {
        return $this->socialite->driver($driver)->redirect();
    }

    /**
     * gets a user's details from the oauth provider
     *
     * @return mixed
     * @author
     **/
    private function getUser($driver)
    {
        return $this->socialite->driver($driver)->user();
    }


} // END class AuthenticateUser
