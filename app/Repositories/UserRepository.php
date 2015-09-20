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
    protected $credentials;

    public function __construct(User $user, CredentialRepositoryInterface $credentials)
    {
        $this->user = $user;
        $this->credentials = $credentials;
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
    public function findUserByProviderOrCreate($provider, SocialUser $userData)
    {
        $providerId = $userData->id;
        $credentials = $this->credentials->getUserCredentialByProvider(
            $provider, $providerId
        );

        if ($credentials) {
            // user exists, update provider credentials
            try {
                $credentials['token'] = $userData->token;
                $credentials['token_secret'] = $userData->tokenSecret;
            } catch (\Exception $e) {
                //
            }

            $credentials->save();

            return $this->user->find($credentials['user_id']);
        }

        // fresh credentials, attach them to logged in user or create new user
        if (Auth::id()) {
            $user = $this->user->find(Auth::id());
        } else {
            // try to find user by email address
            $user = $this->findUserByEmail($userData->email);

            // user email not in the system, create new user
            if (! $user) {
                $user = new User;
                $user->name     = $userData->name;
                $user->nickname = $userData->nickname;
                $user->avatar   = $userData->avatar;
                $user->email    = $userData->email;

                $user->save();
            }
        }

        // create credentials from supplied user data
        try {
            $token = $userData->token;
            $token_secret = $userData->tokenSecret;
        } catch (\Exception $e) {
            if ( ! isset($token)) $token = '';
            if ( ! isset($token_secret)) $token_secret = '';
        }

        $credentials = new Credential([
            'provider'      =>  $provider,
            'provider_id'   =>  $providerId,
            'token'         =>  $token,
            'token_secret'  =>  $token_secret,
        ]);

        // attach credentials to user
        $user->credentials()->save($credentials);

        return $user;
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
