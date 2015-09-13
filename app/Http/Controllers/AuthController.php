<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\AuthenticateUser;
use App\Http\Controllers\Controller;
use App\Contracts\Auth\AuthenticateUserListener;

class AuthController extends Controller implements AuthenticateUserListener
{
    /**
     * log users in
     *
     * @return void
     * @author Abdulmusawwir Sanni<sanniabdulmusawwir@gmail.com>
     **/
    public function login(
        AuthenticateUser $authenticateUser,
        Request $request,
        $provider = null
    )
    {
        return $authenticateUser->execute($request->all(), $this, $provider);
    }

    /**
     * redirects user upon completion of authentication details
     *
     * @return void
     * @author Abdulmusawwir Sanni<sanniabdulmusawwir@gmail.com>
     **/
    public function userHasLoggedIn(User $user)
    {
        return redirect('/');
    }
}
