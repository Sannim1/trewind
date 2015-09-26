<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Credential;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\AbstractUser as SocialUser;
use App\Contracts\Repository\UserRepositoryInterface;
use App\Contracts\Repository\CredentialRepositoryInterface;

/**
 * users repository
 *
 * @package App
 * @author Abdulmusawwir Sanni<sanniabdulmusawwir@gmail.com>
 **/
class UserRepository implements UserRepositoryInterface
{
    protected $user;
    protected $credential;

    public function __construct(User $user, Credential $credential)
    {
        $this->user = $user;
        $this->credential = $credential;
    }

    /**
     * get all models
     * @param  array  $columns
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getAll($columns = ['*'])
    {
        return $this->user->all();
    }

    /**
     * get a particular model by id
     * @param  int $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getById($id)
    {
        return $this->user->find($id);
    }

    /**
     * finds a user with the supplied provider ID, creates a new user
     * if they don't exist.
     *
     * @return App\Models\User
     * @author Abdulmusawwir Sanni<sanniabdulmusawwir@gmail.com>
     **/
    public function findUserByProviderOrCreate(
        $provider,
        SocialUser $userData,
        $authenticatedUserId=null
    )
    {
        $providerId = $userData->id;
        $credential = $this->credential->where('provider', '=', $provider)
            ->where('provider_id', '=', $providerId)
            ->first();

        if ($credential) {
            // user exists, update provider credential
            $this->credential = $credential;
            try {
                $this->credential->token = $userData->token;
                $this->credential->token_secret = $userData->tokenSecret;
            } catch (\Exception $e) {
                //
            }

            $this->credential->save();

            return $this->user->find($this->credential->user_id);
        }

        // fresh credential, attach them to user
        if ($authenticatedUserId) {
            // get logged in user
            $authenticatedUser = $this->user->find($authenticatedUserId);
        }

        if (isset($authenticatedUser)) {
            $this->user = $authenticatedUser;
        } else {
            // user with supplied ID doesn't exist
            // try to find user by email address
            $user = $this->findUserByEmail($userData->email);

            // user with this email already exists in the system
            if ($user) {
                $this->user = $user;
            } else {
                // user not found, create new user
                $this->user->name     = $userData->name;
                $this->user->nickname = $userData->nickname;
                $this->user->avatar   = $userData->avatar;
                $this->user->email    = $userData->email;

                $this->user->save();
            }
        }

        // create new credential from supplied user data
        try {
            $token = $userData->token;
            $token_secret = $userData->tokenSecret;
        } catch (\Exception $e) {
            if ( ! isset($token)) $token = '';
            if ( ! isset($token_secret)) $token_secret = '';
        }

        $this->credential->provider      =  $provider;
        $this->credential->provider_id   =  $providerId;
        $this->credential->token         =  $token;
        $this->credential->token_secret  =  $token_secret;

        // attach credential to user
        $this->user->credentials()->save($this->credential);

        return $this->user;
    }

    /**
     * finds a user by their email address
     * @param  string $email
     * @return App\Models\User
     */
    public function findUserByEmail($email)
    {
        return $this->user->where('email', '=', $email)->first();
    }
} // END class UserRepository
