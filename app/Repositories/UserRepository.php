<?php

namespace App\Repositories;

use App\Models\User;

/**
 * users repository
 *
 * @package App
 * @author Abdulmusawwir Sanni<sanniabdulmusawwir@gmail.com>
 **/
class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * finds a user with the supplied data, creates a new user if they don't
     * exist.
     *
     * @return App\Models\User
     * @author Abdulmusawwir Sanni<sanniabdulmusawwir@gmail.com>
     **/
    public function findByUsernameOrCreate($userData)
    {
        return User::firstOrCreate([
            'username'  =>  $userData->nickname,
            'email'     =>  $userData->email,
            'avatar'    =>  $userData->avatar
        ]);
    }
} // END class UserRepository