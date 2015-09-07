<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Guard;
use App\Contracts\Auth\AuthenticateUserListener;
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


    public function __construct(Socialite $socialite, Guard $auth, UserRepository $users)
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
    public function execute($hasCode, AuthenticateUserListener $listener)
    {
        if ( ! $hasCode) return $this->getAuthorizationFirst();

        $user = $this->users->findByUsernameOrCreate($this->getGithubUser());

        $this->auth->login($user, true);

        return $listener->userHasLoggedIn($user);
    }

    /**
     * redirects to github for authorization
     *
     * @return void
     * @author
     **/
    private function getAuthorizationFirst()
    {
        return $this->socialite->driver('github')->redirect();
    }

    /**
     * gets a github user details
     *
     * @return mixed
     * @author
     **/
    private function getGithubUser()
    {
        return $this->socialite->driver('github')->user();
    }


} // END class AuthenticateUser
