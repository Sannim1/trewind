<?php

namespace App\Contracts\Auth;

use App\Models\User;

/**
 * defines a contract for objects that cna be authenticated
 *
 * @package App
 * @author Abdulmusawwir Sanni<sanniabdulmusawwir@gmail.com>
 **/
interface AuthenticateUserListener
{
    /**
     * listener callback called upon succesful authentication
     * @return mixed
     */
    public function userHasLoggedIn(User $user);
} // END interface AuthenticateUserListener
